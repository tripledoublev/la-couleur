<?php
// Set the appropriate CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Get the image URL from the query string
$imageUrl = $_GET['url'];

// Check if the URL is empty or not valid
if (empty($imageUrl) || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo 'Invalid URL';
    exit;
}

// Fetch the image from the given URL
$imageData = @file_get_contents($imageUrl);

// Check if the image was fetched successfully
if ($imageData === false) {
    http_response_code(404);
    echo 'Image not found';
    exit;
}

// Get the content type of the image
$contentType = get_headers($imageUrl, 1)['Content-Type'];

// Set the content type header and output the image data
header('Content-Type: ' . $contentType);
echo $imageData;
