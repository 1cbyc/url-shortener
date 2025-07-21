<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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