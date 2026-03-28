<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>録音一覧</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #333;
            border-bottom: 2px solid #007aff;
            padding-bottom: 10px;
        }

        .settings-panel {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .settings-panel h2 {
            margin: 0 0 16px 0;
            font-size: 16px;
            color: #333;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        .setting-item label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }

        .setting-item select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background: #f9f9f9;
        }

        .recording-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .recording-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
        }

        .recording-date {
            font-size: 12px;
            color: #999;
            margin-bottom: 12px;
        }

        .summarize-btn {
            background: #007aff;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
        }

        .summarize-btn:hover {
            background: #0056b3;
        }

        .summarize-btn:disabled {
            background: #aaa;
            cursor: not-allowed;
        }

        .summary-box {
            background: #f0f7ff;
            border-left: 4px solid #007aff;
            border-radius: 4px;
            padding: 12px;
            margin-top: 12px;
            font-size: 14px;
            color: #333;
            line-height: 1.8;
            white-space: pre-wrap;
        }

        .setting-badge {
            display: inline-block;
            font-size: 11px;
            background: #e8f0fe;
            color: #007aff;
            border-radius: 4px;
            padding: 2px 8px;
            margin-right: 4px;
            margin-bottom: 8px;
        }

        .empty {
            text-align: center;
            color: #999;
            margin-top: 60px;
        }
    </style>
</head>

<body>
 {{-- ヘッダー：ユーザー名とログアウトボタン --}}
    <div style="background:white; padding:12px 20px; margin-bottom:20px;
                box-shadow:0 1px 4px rgba(0,0,0,0.1); display:flex;
                justify-content:space-between; align-items:center;">
        <span style="font-size:14px; color:#666;">
            ようこそ、{{ session('user_name') }}さん
        </span>
        <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button type="submit" style="background:#ff3b30; color:white;
                    border:none; border-radius:6px; padding:6px 14px;
                    font-size:13px; cursor:pointer;">
                ログアウト
            </button>
        </form>
    </div>
    <h1>🎤 録音一覧</h1>

    <div class="settings-panel">
        <h2>⚙️ 要約設定</h2>
        <div class="settings-grid">
            <div class="setting-item">
                <label>要約スタイル</label>
                <select id="summary_style">
                    <option value="bullets">箇条書き</option>
                    <option value="paragraph">文章形式</option>
                    <option value="keywords">キーワードのみ</option>
                </select>
            </div>
            <div class="setting-item">
                <label>要約の長さ</label>
                <select id="summary_length">
                    <option value="short">簡潔（3点以内）</option>
                    <option value="medium" selected>標準（3〜5点）</option>
                    <option value="long">詳細（5〜8点）</option>
                </select>
            </div>
            <div class="setting-item">
                <label>対象シーン</label>
                <select id="summary_scene">
                    <option value="general" selected>一般</option>
                    <option value="meeting">会議・ミーティング</option>
                    <option value="nursing">介護・看護記録</option>
                    <option value="lecture">授業・講義</option>
                </select>
            </div>
        </div>
    </div>

    @if ($recordings->isEmpty())
    <div class="empty">
        <p>録音データがありません</p>
    </div>
    @else
    @foreach ($recordings as $recording)
    <div class="recording-card">
        <div class="recording-title">📁 {{ $recording->original_name }}</div>
        <div class="recording-date">{{ $recording->created_at->format('Y年m月d日 H:i') }}</div>

        @if ($recording->summary)
        <div>
            <span class="setting-badge">{{ $recording->summary_scene }}</span>
            <span class="setting-badge">{{ $recording->summary_style }}</span>
            <span class="setting-badge">{{ $recording->summary_length }}</span>
        </div>
        <div class="summary-box">✅ {{ $recording->summary }}</div>
        @else
        <button
            class="summarize-btn"
            id="btn-{{ $recording->id }}"
            onclick="summarize({{ $recording->id }})">
            要約する
        </button>
        <div id="result-{{ $recording->id }}"></div>
        @endif
    </div>
    @endforeach
    @endif

    <script>
        const csrfToken = '{{ csrf_token() }}';

        function summarize(id) {
            const btn = document.getElementById('btn-' + id);
            btn.disabled = true;
            btn.textContent = '処理中...';

            const settings = {
                summary_style: document.getElementById('summary_style').value,
                summary_length: document.getElementById('summary_length').value,
                summary_scene: document.getElementById('summary_scene').value,
            };

            fetch('/api/recordings/' + id + '/summarize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(settings)
                })
                .then(response => {
                    if (!response.ok) throw new Error('エラーが発生しました');
                    return response.json();
                })
                .then(data => {
                    const resultDiv = document.getElementById('result-' + id);
                    if (data.summary) {
                        resultDiv.innerHTML = `
                        <div class="summary-box">
                            ✅ ${data.summary}
                        </div>
                    `;
                        btn.style.display = 'none';
                    } else if (data.error) {
                        btn.textContent = 'エラー：' + data.error;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    btn.textContent = 'エラーが発生しました';
                    btn.disabled = false;
                    console.error(error);
                });
        }
    </script>

</body>

</html>
