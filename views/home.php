<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --accent: #ff7139;
            --accent-dark: #e65c1a;
            --bg: #f7f8fa;
            --card-bg: #fff;
            --border: #e0e0e0;
            --text: #222;
            --error: #e11d48;
            --success: #22c55e;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); margin: 0; padding: 0; }
        .container { max-width: 440px; margin: 60px auto; background: var(--card-bg); border-radius: 18px; box-shadow: 0 4px 32px rgba(0,0,0,0.09); padding: 40px 32px 32px 32px; }
        h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 18px; color: var(--text); letter-spacing: -1px; }
        .subtitle { color: #666; font-size: 1.1rem; margin-bottom: 28px; }
        form { display: flex; flex-direction: column; gap: 18px; }
        .input-group { display: flex; gap: 10px; }
        input[type="text"], input[type="url"] { flex: 1; padding: 15px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 1.08rem; transition: border 0.2s; }
        input[type="text"]:focus, input[type="url"]:focus { border: 1.5px solid var(--accent); outline: none; }
        input[type="submit"] { background: var(--accent); color: #fff; border: none; border-radius: 8px; padding: 15px; font-size: 1.08rem; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        input[type="submit"]:hover { background: var(--accent-dark); }
        .result-section { margin-top: 32px; display: none; flex-direction: column; align-items: center; gap: 18px; animation: fadeIn 0.7s; }
        .short-url-box { display: flex; align-items: center; gap: 10px; background: #f3f4f6; border-radius: 8px; padding: 12px 18px; font-size: 1.08rem; }
        .copy-btn { background: var(--accent); color: #fff; border: none; border-radius: 6px; padding: 8px 12px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        .copy-btn:hover { background: var(--accent-dark); }
        .qr-section { display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .feedback { margin-top: 24px; text-align: center; font-weight: 600; min-height: 28px; transition: color 0.3s; }
        .feedback.error { color: var(--error); animation: shake 0.4s; }
        .feedback.success { color: var(--success); }
        @media (max-width: 600px) {
            .container { max-width: 98vw; padding: 18px 4vw; }
            h1 { font-size: 1.5rem; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-8px); }
            80% { transform: translateX(8px); }
            100% { transform: translateX(0); }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Shorten Your Link</h1>
        <div class="subtitle">Paste your long URL below and get a short, shareable link instantly.</div>
        <form id="shorten-form" method="POST">
            <div class="input-group">
                <input type="url" name="url" id="url" placeholder="Paste your long URL here" required>
            </div>
            <div class="input-group">
                <input type="text" name="custom" id="custom" placeholder="Custom short code (optional)">
            </div>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars(UrlShortener\Service\Csrf::token()) ?>">
            <input type="submit" value="Shorten URL">
        </form>
        <div class="result-section" id="result-section">
            <div class="short-url-box">
                <a href="#" id="short-url" target="_blank"></a>
                <button class="copy-btn" id="copy-btn"><i class="fa fa-copy"></i></button>
            </div>
            <div class="qr-section">
                <canvas id="qr-code"></canvas>
                <div style="font-size:0.95rem;color:#888;">Scan QR to open</div>
            </div>
        </div>
        <div class="feedback" id="feedback"></div>
    </div>
    <script>
        const form = document.getElementById('shorten-form');
        const resultSection = document.getElementById('result-section');
        const shortUrl = document.getElementById('short-url');
        const copyBtn = document.getElementById('copy-btn');
        const qrCode = document.getElementById('qr-code');
        const feedback = document.getElementById('feedback');
        const csrfToken = document.getElementById('csrf_token').value;

        function showFeedback(message, type) {
            feedback.textContent = message;
            feedback.className = 'feedback ' + type;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            resultSection.style.display = 'none';
            showFeedback('', '');
            const formData = new FormData(form);
            formData.set('csrf_token', csrfToken);
            try {
                const res = await fetch('/shorten', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.short_url) {
                    shortUrl.textContent = data.short_url;
                    shortUrl.href = data.short_url;
                    resultSection.style.display = 'flex';
                    new QRious({
                        element: qrCode,
                        value: data.short_url,
                        size: 120
                    });
                    showFeedback('Short URL created successfully!', 'success');
                } else if (data.error) {
                    showFeedback(data.error, 'error');
                } else {
                    showFeedback('Unexpected error. Please try again.', 'error');
                }
            } catch (err) {
                showFeedback('Network error. Please try again.', 'error');
            }
        });

        copyBtn.addEventListener('click', () => {
            if (shortUrl.textContent) {
                navigator.clipboard.writeText(shortUrl.textContent);
                showFeedback('Copied to clipboard!', 'success');
                copyBtn.textContent = 'Copied!';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fa fa-copy"></i>';
                }, 1200);
            }
        });
    </script>
</body>
</html> 