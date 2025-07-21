<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f7f8fa; margin: 0; padding: 0; }
        .container { max-width: 420px; margin: 60px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 32px; }
        h1 { font-size: 2rem; font-weight: 600; margin-bottom: 24px; color: #222; }
        form { display: flex; flex-direction: column; gap: 16px; }
        input[type="text"] { padding: 12px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 1rem; }
        input[type="submit"] { background: #2563eb; color: #fff; border: none; border-radius: 6px; padding: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        input[type="submit"]:hover { background: #1d4ed8; }
        .result { margin-top: 24px; font-size: 1.1rem; color: #2563eb; word-break: break-all; }
        .error { margin-top: 24px; color: #e11d48; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Shorten Your URL</h1>
        <form id="shorten-form">
            <input type="text" name="url" id="url" placeholder="Paste your long URL here" required>
            <input type="text" name="custom" id="custom" placeholder="Custom short code (optional)">
            <input type="submit" value="Shorten">
        </form>
        <div class="result" id="result"></div>
        <div class="error" id="error"></div>
    </div>
    <script>
        const form = document.getElementById('shorten-form');
        const result = document.getElementById('result');
        const error = document.getElementById('error');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            result.textContent = '';
            error.textContent = '';
            const formData = new FormData(form);
            const res = await fetch('/shorten', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.short_url) {
                result.innerHTML = `<a href="${data.short_url}" target="_blank">${data.short_url}</a>`;
            } else if (data.error) {
                error.textContent = data.error;
            }
        });
    </script>
</body>
</html> 