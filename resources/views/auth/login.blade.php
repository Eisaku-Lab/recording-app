<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - 録音管理システム</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: #f0f2f5;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* 左側のブランドパネル */
        .brand-panel {
            width: 420px;
            background: #1a56db;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            flex-shrink: 0;
        }

        .brand-logo {
            font-size: 22px;
            font-weight: 600;
            color: white;
            letter-spacing: 0.5px;
            margin-bottom: 48px;
        }

        .brand-logo span {
            display: inline-block;
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            margin-right: 10px;
            vertical-align: middle;
            line-height: 32px;
            text-align: center;
            font-size: 16px;
        }

        .brand-title {
            font-size: 32px;
            font-weight: 600;
            color: white;
            line-height: 1.3;
            margin-bottom: 16px;
        }

        .brand-desc {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            line-height: 1.7;
        }

        .brand-features {
            margin-top: 48px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.85);
            font-size: 13px;
        }

        .feature-dot {
            width: 6px;
            height: 6px;
            background: rgba(255,255,255,0.6);
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* 右側のログインフォーム */
        .login-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            padding: 48px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }

        .login-header {
            margin-bottom: 32px;
        }

        .login-header h2 {
            font-size: 22px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 6px;
        }

        .login-header p {
            font-size: 13px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #111827;
            background: #f9fafb;
            transition: all 0.15s;
            outline: none;
        }

        input:focus {
            border-color: #1a56db;
            background: white;
            box-shadow: 0 0 0 3px rgba(26,86,219,0.1);
        }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 20px;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s;
            letter-spacing: 0.3px;
        }

        .btn-login:hover { background: #1648c0; }

        .divider {
            height: 1px;
            background: #f3f4f6;
            margin: 24px 0;
        }

        .login-footer {
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="brand-panel">
        <div class="brand-logo">
            <span>R</span>録音管理
        </div>
        <div class="brand-title">音声データを<br>スマートに管理</div>
        <div class="brand-desc">
            Apple Watchで録音したデータを<br>
            AIが自動で文字起こし・要約します
        </div>
        <div class="brand-features">
            <div class="feature-item">
                <div class="feature-dot"></div>
                Apple Watchからワンタップで録音
            </div>
            <div class="feature-item">
                <div class="feature-dot"></div>
                Whisper AIによる高精度な文字起こし
            </div>
            <div class="feature-item">
                <div class="feature-dot"></div>
                GPT-4oによる自動要約・整理
            </div>
            <div class="feature-item">
                <div class="feature-dot"></div>
                シーン別・スタイル別の要約設定
            </div>
        </div>
    </div>

    <div class="login-panel">
        <div class="login-card">
            <div class="login-header">
                <h2>ログイン</h2>
                <p>アカウント情報を入力してください</p>
            </div>

            @if (session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="form-group">
                    <label>メールアドレス</label>
                    <input type="email" name="email" placeholder="例: admin@example.com" required>
                </div>
                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" name="password" placeholder="パスワードを入力" required>
                </div>
                <button type="submit" class="btn-login">ログイン</button>
            </form>

            <div class="divider"></div>
            <div class="login-footer">録音管理システム v1.0</div>
        </div>
    </div>

</body>
</html>
