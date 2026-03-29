<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>録音一覧 - 録音管理システム</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
        }

        /* サイドバー */
        .sidebar {
            width: 220px;
            background: white;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 15px;
            font-weight: 600;
            color: #1a56db;
            letter-spacing: 0.3px;
        }

        .sidebar-logo span {
            display: inline-block;
            width: 28px;
            height: 28px;
            background: #1a56db;
            border-radius: 7px;
            margin-right: 8px;
            vertical-align: middle;
            color: white;
            text-align: center;
            line-height: 28px;
            font-size: 14px;
        }

        .sidebar-nav {
            padding: 12px 10px;
            flex: 1;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 500;
            color: #9ca3af;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 8px 10px 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .nav-item.active {
            background: #eff6ff;
            color: #1a56db;
            font-weight: 500;
        }

        .nav-item:hover:not(.active) {
            background: #f9fafb;
        }

        .nav-icon {
            width: 16px;
            height: 16px;
            opacity: 0.6;
        }

        .nav-item.active .nav-icon { opacity: 1; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #f3f4f6;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: #1a56db;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
        }

        .user-role {
            font-size: 11px;
            color: #9ca3af;
        }

        .btn-logout {
            width: 100%;
            padding: 7px;
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            font-size: 12px;
            color: #6b7280;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-logout:hover {
            background: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        /* メインコンテンツ */
        .main {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* トップバー */
        .topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge {
            font-size: 11px;
            background: #eff6ff;
            color: #1a56db;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 500;
        }

        /* コンテンツエリア */
        .content {
            padding: 24px 28px;
            flex: 1;
        }

        /* 設定パネル */
        .settings-panel {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .settings-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .settings-title {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .setting-item label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 5px;
            letter-spacing: 0.3px;
        }

        .setting-item select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            font-size: 13px;
            font-family: inherit;
            color: #374151;
            background: #f9fafb;
            cursor: pointer;
            outline: none;
            transition: all 0.15s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
        }

        .setting-item select:focus {
            border-color: #1a56db;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(26,86,219,0.08);
        }

        /* 録音一覧 */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .recording-count {
            font-size: 12px;
            color: #9ca3af;
        }

        .recording-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .recording-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px 20px;
            transition: box-shadow 0.15s;
        }

        .recording-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .recording-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .recording-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon {
            width: 32px;
            height: 32px;
            background: #eff6ff;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-icon svg {
            width: 16px;
            height: 16px;
            fill: #1a56db;
        }

        .recording-name {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .recording-date {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .recording-badges {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tag {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 5px;
            font-weight: 500;
        }

        .tag-blue { background: #eff6ff; color: #1a56db; }
        .tag-green { background: #f0fdf4; color: #16a34a; }
        .tag-gray { background: #f3f4f6; color: #6b7280; }

        .btn-summarize {
            padding: 7px 16px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s;
        }
.btn-delete {
    padding: 7px 14px;
    background: none;
    color: #ef4444;
    border: 1px solid #fecaca;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 500;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.15s;
}

/* アップロードエリア */
.upload-panel {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 20px;
}
.upload-area {
    border: 2px dashed #e5e7eb;
    border-radius: 10px;
    padding: 28px;
    text-align: center;
    cursor: pointer;
    transition: all 0.15s;
}
.upload-area:hover, .upload-area.dragover {
    border-color: #1a56db;
    background: #eff6ff;
}
.upload-area input[type="file"] {
    display: none;
}
.upload-area-text {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
}
.upload-area-sub {
    font-size: 11px;
    color: #9ca3af;
}
.btn-upload-select {
    display: inline-block;
    padding: 7px 16px;
    background: #1a56db;
    color: white;
    border: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 500;
    font-family: inherit;
    cursor: pointer;
    transition: background 0.15s;
    margin-bottom: 10px;
}
.btn-upload-select:hover { background: #1648c0; }
.upload-progress {
    display: none;
    margin-top: 12px;
}
.progress-bar-wrap {
    background: #f3f4f6;
    border-radius: 99px;
    height: 6px;
    overflow: hidden;
}
.progress-bar {
    height: 6px;
    background: #1a56db;
    border-radius: 99px;
    width: 0%;
    transition: width 0.2s;
}
.upload-status {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
    text-align: center;
}
.upload-success {
    font-size: 12px;
    color: #16a34a;
    margin-top: 6px;
    text-align: center;
}


.btn-delete:hover {
    background: #fef2f2;
    border-color: #ef4444;
}

        .btn-summarize:hover { background: #1648c0; }

        .btn-summarize:disabled {
            background: #93c5fd;
            cursor: not-allowed;
        }

        /* 要約結果 */
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #1a56db;
            border-radius: 0 8px 8px 0;
            padding: 12px 16px;
            margin-top: 12px;
            font-size: 13px;
            color: #374151;
            line-height: 1.8;
            white-space: pre-wrap;
        }

        .summary-label {
            font-size: 11px;
            font-weight: 600;
            color: #1a56db;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        /* 空状態 */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state svg {
            width: 40px;
            height: 40px;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 13px;
        }

        /* ローディング */
        .loading-dots {
            display: inline-flex;
            gap: 3px;
            align-items: center;
        }

        .loading-dots span {
            width: 4px;
            height: 4px;
            background: white;
            border-radius: 50%;
            animation: dot 1.2s infinite;
        }

        .loading-dots span:nth-child(2) { animation-delay: 0.2s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes dot {
            0%, 80%, 100% { opacity: 0.2; transform: scale(0.8); }
            40% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>

    {{-- サイドバー --}}
    <div class="sidebar">
        <div class="sidebar-logo">
            <span>R</span>録音管理
        </div>
        <div class="sidebar-nav">
            <div class="nav-label">メニュー</div>
            <a class="nav-item active" href="/">
                <svg class="nav-icon" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 1a3 3 0 100 6 3 3 0 000-6zM4 8a4 4 0 118 0v1H4V8z"/>
                    <path d="M2 11a6 6 0 0112 0H2z"/>
                </svg>
                録音一覧
            </a>
        </div>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ mb_substr(session('user_name', '?'), 0, 1) }}
                </div>
                <div>
                    <div class="user-name">{{ session('user_name') }}</div>
                    <div class="user-role">
                        {{ session('kanri_flg') == 1 ? '管理者' : '一般ユーザー' }}
                    </div>
                </div>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="btn-logout">ログアウト</button>
            </form>
        </div>
    </div>

    {{-- メインコンテンツ --}}
    <div class="main">

        {{-- トップバー --}}
        <div class="topbar">
            <div class="topbar-title">録音一覧</div>
            <div class="topbar-right">
                <span class="badge">{{ $recordings->count() }} 件</span>
            </div>
        </div>

        {{-- コンテンツ --}}
        <div class="content">

{{-- アップロードパネル --}}
<div class="upload-panel">
    <div class="settings-header" style="margin-bottom:14px;">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="#6b7280">
            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
        </svg>
        <span class="settings-title">録音ファイルをアップロード</span>
    </div>

    <div class="upload-area" id="upload-area" onclick="document.getElementById('file-input').click()"
         ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)">
        <input type="file" id="file-input" accept=".m4a,.wav,.mp3,.mp4,.aac"
               onchange="onFileSelect(this.files)">
        <button class="btn-upload-select" onclick="event.stopPropagation(); document.getElementById('file-input').click()">
            ファイルを選択
        </button>
        <div class="upload-area-text">またはここにファイルをドラッグ&amp;ドロップ</div>
        <div class="upload-area-sub">対応形式：m4a / wav / mp3 / mp4 / aac</div>
    </div>

    <div class="upload-progress" id="upload-progress">
        <div class="progress-bar-wrap">
            <div class="progress-bar" id="progress-bar"></div>
        </div>
        <div class="upload-status" id="upload-status">アップロード中...</div>
    </div>
    <div class="upload-success" id="upload-success" style="display:none;"></div>
</div>

            {{-- 要約設定パネル --}}
            <div class="settings-panel">
                <div class="settings-header">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="#6b7280">
                        <path d="M8 4.754a3.246 3.246 0 100 6.492 3.246 3.246 0 000-6.492zM5.754 8a2.246 2.246 0 114.492 0 2.246 2.246 0 01-4.492 0z"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 01-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 01-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 01.52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 011.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 011.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 01.52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 01-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 01-1.255-.52l-.094-.319z"/>
                    </svg>
                    <span class="settings-title">要約設定</span>
                </div>
                <div class="settings-grid">
                    <div class="setting-item">
                        <label>要約スタイル</label>
                        <select id="summary_style">
                            <option value="bullets">箇条書き</option>
                            <option value="paragraph">文章形式</option>
                            <option value="keywords">キーワード</option>
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

            {{-- 録音一覧 --}}
            <div class="section-header">
                <span class="section-title">録音データ</span>
                <span class="recording-count">{{ $recordings->count() }}件の録音</span>
            </div>

            @if ($recordings->isEmpty())
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/>
                    </svg>
                    <p>録音データがありません</p>
                </div>
            @else
                <div class="recording-list">
                    @foreach ($recordings as $recording)
                    <div class="recording-card">
                        <div class="recording-meta">
                            <div class="recording-info">
                                <div class="file-icon">
                                    <svg viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="recording-name">{{ $recording->original_name }}</div>
                                    <div class="recording-date">{{ $recording->created_at->format('Y年m月d日 H:i') }}</div>
                                </div>
                            </div>
                            <div class="recording-badges">
{{-- 削除ボタン（常に表示） --}}
    <button
        class="btn-delete"
        id="del-{{ $recording->id }}"
        onclick="deleteRecording({{ $recording->id }})">
        削除
    </button>

    @if ($recording->summary)
        <span class="tag tag-green">要約済み</span>
        <span class="tag tag-blue">{{ $recording->summary_scene }}</span>
    @else
        <button
            class="btn-summarize"
            id="btn-{{ $recording->id }}"
            onclick="summarize({{ $recording->id }})">
            要約する
        </button>
    @endif
</div>
                        </div>

                        @if ($recording->summary)
                            <div class="summary-box">
                                <div class="summary-label">要約結果</div>
                                {{ $recording->summary }}
                            </div>
                        @else
                            <div id="result-{{ $recording->id }}"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        function summarize(id) {
            const btn = document.getElementById('btn-' + id);
            btn.disabled = true;
            btn.innerHTML = '<span class="loading-dots"><span></span><span></span><span></span></span>';

            const settings = {
                summary_style:  document.getElementById('summary_style').value,
                summary_length: document.getElementById('summary_length').value,
                summary_scene:  document.getElementById('summary_scene').value,
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
                            <div class="summary-label">要約結果</div>
                            ${data.summary}
                        </div>
                    `;
                    btn.style.display = 'none';
                } else if (data.error) {
                    btn.textContent = 'エラーが発生しました';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                btn.textContent = 'エラーが発生しました';
                btn.disabled = false;
            });
        }
function deleteRecording(id) {
    if (!confirm('この録音データを削除しますか？')) return;

    const card = document.getElementById('del-' + id).closest('.recording-card');
    const delBtn = document.getElementById('del-' + id);
    delBtn.disabled = true;
    delBtn.textContent = '削除中...';

    fetch('/api/recordings/' + id, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('削除失敗');
        return response.json();
    })
    .then(() => {
        // カードをフェードアウトして削除
        card.style.transition = 'opacity 0.3s';
        card.style.opacity = '0';
        setTimeout(() => card.remove(), 300);
    })
    .catch(() => {
        delBtn.textContent = 'エラー';
        delBtn.disabled = false;
    });
}
// ドラッグ&ドロップ
function onDragOver(e) {
    e.preventDefault();
    document.getElementById('upload-area').classList.add('dragover');
}
function onDragLeave(e) {
    document.getElementById('upload-area').classList.remove('dragover');
}
function onDrop(e) {
    e.preventDefault();
    document.getElementById('upload-area').classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) onFileSelect(files);
}

// ファイル選択時
function onFileSelect(files) {
    if (!files || files.length === 0) return;
    const file = files[0];
    uploadFile(file);
}

// アップロード処理
function uploadFile(file) {
    const progress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const status = document.getElementById('upload-status');
    const success = document.getElementById('upload-success');

    // 表示リセット
    success.style.display = 'none';
    progress.style.display = 'block';
    progressBar.style.width = '0%';
    status.textContent = 'アップロード中...';

    const formData = new FormData();
    formData.append('audio', file);

    const xhr = new XMLHttpRequest();

    // 進捗バー更新
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const pct = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = pct + '%';
            status.textContent = `アップロード中... ${pct}%`;
        }
    };

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            progress.style.display = 'none';
            success.style.display = 'block';
            success.textContent = `「${file.name}」をアップロードしました。ページを更新してください。`;
            document.getElementById('file-input').value = '';
            // 2秒後に自動リロード
            setTimeout(() => location.reload(), 2000);
        } else {
            status.textContent = 'アップロードに失敗しました';
        }
    };

    xhr.onerror = function() {
        status.textContent = 'エラーが発生しました';
    };

    xhr.open('POST', '/api/recordings/upload');
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.send(formData);
}
    </script>

</body>
</html>
