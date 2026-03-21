<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use Illuminate\Http\Request;

class RecordingController extends Controller
{
    // Web画面用：録音一覧をブラウザに表示する
    public function index()
    {
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
}