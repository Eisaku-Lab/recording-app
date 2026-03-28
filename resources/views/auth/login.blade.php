<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            border-radius: 12px;
            padding: 40px;
            width: 360px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 24px;
            font-size: 20px;
        }
        label {
            display: block;
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007aff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .error {
            background: #fff0f0;
            border-left: 4px solid #ff3b30;
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            color: #cc0000;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>🎤 録音管理システム</h1>

        {{-- エラーメッセージ --}}
        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <label>メールアドレス</label>
            <input type="email" name="email" placeholder="admin@example.com" required>

            <label>パスワード</label>
            <input type="password" name="password" placeholder="パスワード" required>

            <button type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>
