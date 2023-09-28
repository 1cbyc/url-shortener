<?php
// Handle URL shortening
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalUrl = $_POST['url'];
    $shortCode = generateShortCode(); // Implement this function

    // Store the mapping between short code and original URL (e.g., in a database or file)
    storeUrlMapping($shortCode, $originalUrl); // Implement this function

    echo "Shortened URL: http://your-short-domain/{$shortCode}";
    exit;
}

// Handle URL redirection
$shortCode = $_GET['code'] ?? '';
if (!empty($shortCode)) {
    $originalUrl = getOriginalUrl($shortCode); // Implement this function

    if ($originalUrl) {
        header("Location: {$originalUrl}", true, 302);
        exit;
    } else {
        echo "Short URL not found.";
        exit;
    }
}
?>


<form method="POST">
    <input type="text" name="url" placeholder="Enter your long URL">
    <input type="submit" value="Shorten">
</form>
