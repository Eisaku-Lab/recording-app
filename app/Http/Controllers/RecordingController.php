<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use Illuminate\Http\Request;

class RecordingController extends Controller
{
// Web画面用：録音一覧をブラウザに表示する
public function index()
{
    // セッションチェック：ログインしていない場合はログイン画面へ
    if (!session('user_id')) {
        return redirect('/login');
    }

    $recordings = Recording::latest()->get();
    return view('recordings.index', compact('recordings'));
}
    // API用：録音一覧をJSONで返す
    public function list()
    {
        $recordings = Recording::latest()->get();
        return response()->json($recordings);
    }

    // API用：録音ファイルを受け取って保存する
public function upload(Request $request)
{
    $file = $request->file('audio');

    if (!$file) {
        \Log::info('ファイルなし');
        return response()->json(['message' => 'ファイルが受信できませんでした'], 422);
    }

    \Log::info('受信ファイルサイズ: ' . $file->getSize() . ' bytes');
    \Log::info('受信ファイル名: ' . $file->getClientOriginalName());
    \Log::info('MIMEタイプ: ' . $file->getMimeType());

    $path = $file->store('recordings', 'local');

    $recording = Recording::create([
        'file_path'     => $path,
        'original_name' => $file->getClientOriginalName(),
    ]);

    return response()->json([
        'message' => '保存しました',
        'id'      => $recording->id,
    ]);
}

// 録音データを削除する
public function destroy($id)
{
    $recording = Recording::findOrFail($id);

    // 音声ファイルも削除する
    $filePath = storage_path('app/private/' . $recording->file_path);
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // DBからレコードを削除
    $recording->delete();

    return response()->json(['message' => '削除しました']);
}
}
