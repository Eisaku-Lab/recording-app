<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use Illuminate\Http\Request;
use OpenAI;

class SummaryController extends Controller
{
    public function summarize(Request $request, $id)
    {
        $recording = Recording::findOrFail($id);
        $client = OpenAI::client(config('services.openai.key'));
        $filePath = storage_path('app/private/' . $recording->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => '音声ファイルが見つかりません'], 404);
        }

        try {
// ファイルの存在確認ログ
\Log::info('File path: ' . $filePath);
\Log::info('File exists: ' . (file_exists($filePath) ? 'yes' : 'no'));
\Log::info('File size: ' . (file_exists($filePath) ? filesize($filePath) : 0));

// ① Whisperで文字起こし
$transcriptionResponse = $client->audio()->transcribe([
    'model'    => 'whisper-1',
    'file'     => fopen($filePath, 'r'),
    'language' => 'ja',
    'response_format' => 'text',
]);
$transcription = $transcriptionResponse->text ?? $transcriptionResponse;

            // ② リクエストから設定を取得
            $style  = $request->input('summary_style',  $recording->summary_style);
            $length = $request->input('summary_length', $recording->summary_length);
            $scene  = $request->input('summary_scene',  $recording->summary_scene);

            // ③ プロンプトを組み立てる
            $prompt = $this->buildPrompt($style, $length, $scene);

            // ④ GPT-4oで要約
            $summaryResponse = $client->chat()->create([
                'model'    => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user',   'content' => $transcription],
                ],
            ]);
            $summary = $summaryResponse->choices[0]->message->content;

            // ⑤ DBに保存
            $recording->update([
                'transcription'  => $transcription,
                'summary'        => $summary,
                'summary_style'  => $style,
                'summary_length' => $length,
                'summary_scene'  => $scene,
            ]);

            return response()->json([
                'transcription' => $transcription,
                'summary'       => $summary,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 設定に応じてプロンプトを組み立てる関数
    private function buildPrompt($style, $length, $scene)
    {
        $styleText = match ($style) {
            'bullets'   => '箇条書き（・）で',
            'paragraph' => '文章形式で',
            'keywords'  => 'キーワードのみを列挙して',
            default     => '箇条書きで',
        };

        $lengthText = match ($length) {
            'short'  => '3点以内で簡潔に',
            'medium' => '3〜5点で',
            'long'   => '5〜8点で詳しく',
            default  => '3〜5点で',
        };

        $sceneText = match ($scene) {
            'meeting' => '会議・ミーティングの内容として',
            'nursing' => '介護・看護記録として',
            'lecture' => '授業・講義の内容として',
            'general' => '一般的な内容として',
            default   => '一般的な内容として',
        };

        return "以下の文字起こしを{$sceneText}、{$styleText}{$lengthText}日本語で要約してください。";
    }
}
