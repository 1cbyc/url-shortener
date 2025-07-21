<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f7f8fa; margin: 0; padding: 0; }
        .container { max-width: 420px; margin: 60px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 32px; }
        h1 { font-size: 2rem; font-weight: 600; margin-bottom: 24px; color: #222; }
        .stat { font-size: 1.2rem; margin-bottom: 16px; }
        .stat span { font-weight: 600; color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="stat">Total Users: <span><?= $userCount ?></span></div>
        <div class="stat">Total URLs: <span><?= $urlCount ?></span></div>
        <div class="stat">Total Clicks: <span><?= $clickCount ?></span></div>
    </div>
</body>
</html> 