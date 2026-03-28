<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ログイン画面を表示する
    public function showLogin()
    {
        return view('auth.login');
    }

    // ログイン処理
    public function login(Request $request)
    {
        // ①入力されたメールアドレスでユーザーを検索
        $user = User::where('email', $request->email)->first();

        // ②ユーザーが存在してパスワードが一致するか確認
        if ($user && Hash::check($request->password, $user->password)) {

            // ③セッションにユーザー情報を保存
            session([
                'user_id'   => $user->id,
                'user_name' => $user->name,
                'kanri_flg' => $user->kanri_flg,
            ]);

            // ④録音一覧画面にリダイレクト
            return redirect('/');

        } else {
            // ⑤ログイン失敗の場合はログイン画面に戻る
            return redirect('/login')->with('error', 'メールアドレスまたはパスワードが違います');
        }
    }

    // ログアウト処理
    public function logout()
    {
        // セッションを削除する
        session()->flush();
        return redirect('/login');
    }
}
