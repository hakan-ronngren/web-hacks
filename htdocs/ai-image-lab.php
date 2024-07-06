<?php
// Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/plain");

require_once 'openapi-config.php';

define('POEM_CACHE_FILE', '/tmp/poem.txt');
define('IMAGE_CACHE_FILE', '/tmp/poem.png');
define('TIME_LIMIT', 3600);


// The API endpoint for image generation
$url = 'https://api.openai.com/v1/images/generations';

// The data to send to the API
$data = [
    'prompt' => 'Draw a vibrant and colorful romance comic illustration inspired by the limerick about a bright dreamer. The scene should depict a dreamy, night-time setting where the dreamer is peacefully sleeping. Show the dreamer surrounded by whimsical dream elements turning into reality, symbolizing the transformation of dreams into facts. The dreamer should appear courageous and content, basking in the light of their accomplishments. Use expressive characters, detailed backgrounds, and vibrant colors to convey the romantic and delightful atmosphere of living in pure delight and light. Emphasize the romantic comic art style with bold lines and dynamic compositions.',
    'n' => 1,
    'size' => '1024x1024',
    'quality' => 'hd'
];

// Initialize cURL
$ch = curl_init($url);

// Set the options for the cURL request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: ' . 'Bearer ' . OPENAPI_KEY
]);

// Execute the request and get the response
$response = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);

// Check for errors
if (isset($responseData['error'])) {
    echo "Error: " . $responseData['error']['message'] . "\n";
} else {
    // Extract the image URL from the response
    $imageUrl = $responseData['data'][0]['url'];
    echo "Generated Image URL: " . $imageUrl . "\n";

    // Initialize a cURL session for downloading the image
    $ch = curl_init($imageUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow redirects
    $imageContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP status code
    curl_close($ch);

    // Check if the download was successful
    if ($httpCode == 200 && !empty($imageContent)) {
        file_put_contents(IMAGE_CACHE_FILE, $imageContent);
        echo "Image saved to: " . IMAGE_CACHE_FILE . "\n";
    } else {
        echo "Failed to download the image.\n";
    }
}
