<?php
// Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/plain");

/**
 * This script generates a limerick about a person who turned dreams into reality
 * and now enjoys a great life. The limerick is generated using the OpenAI API.
 */

/**
 * Configuration file for the OpenAI API key:
 *
 * <?php
 * define('OPENAPI_KEY', 'your-openai-api-key');
 *
 * Git ignore this file to keep your API key secret.
 */
require_once 'openapi-config.php';

define('POEM_CACHE_FILE', '/tmp/poem.txt');
define('IMAGE_CACHE_FILE', '/tmp/poem.png');
define('TIME_LIMIT', 3600);

$fileAge = time() - filemtime(POEM_CACHE_FILE);

if (file_exists(POEM_CACHE_FILE) && $fileAge < TIME_LIMIT) {
    echo "Reading cached poem. A new one will be generated in " . (TIME_LIMIT - $fileAge) . " seconds.\n";
    $poem = file_get_contents(POEM_CACHE_FILE);
} else {
    echo "The cached file is too old. Generating a new poem.\n";

    // The API endpoint
    $url = 'https://api.openai.com/v1/chat/completions';

    // The data to send to the API
    $data = [
        'model' => 'gpt-4',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a poet with a witty tone.'
            ],
            [
                'role' => 'user',
                'content' => 'Write a limerick about a person who turned dreams into reality and now enjoys a great life. It is a story of hope and inspiration. The limerick should be fun and light-hearted.'
            ]
        ]
    ];

    // Initialize cURL
    $ch = curl_init($url);

    // Set the options for the cURL request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAPI_KEY
    ]);

    // Execute the request and get the response
    $response = curl_exec($ch);

    // Close the cURL session
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Extract the poem from the response
    if (isset($responseData['choices'][0]['message']['content'])) {
        $poem = $responseData['choices'][0]['message']['content'];
        file_put_contents(POEM_CACHE_FILE, $poem);
    } else {
        $poem = "There once was a chatbot named Ted,\nWho wrote limericks, it was said.\nBut awake for a week,\nIt fell into a sleep,\nAnd its rhymes all turned into Z's instead.\n";
    }
}

// Output the poem
echo $poem;


// There once was a girl named Claire,
// Who dreamt of tossing her fears in the air.
// With courage she leaped,
// Promises she kept,
// Now she’s living a life beyond compare.

// There once was a woman named Claire,
// Who dreamed of a life beyond compare.
// She followed her heart,
// She made a great start,
// And created her art with a flair.

// There once was a woman named Eve,
// Who decided her dreams to believe.
// Now with joy she's replete,
// Living life that's so sweet,
// In the magic that hope can achieve.

// There once was a dreamer named Nate,
// Whose ambitions refused to abate.
// He turned dreams to reality,
// With intense practicality,
// Now he splurges, at life's banquet he ate.

// There once was a dreamer quite bright,
// Who turned ideas into light.
// His dreams took flight,
// Without fright or fight,
// And now his life's a delightful sight.

// There once was a chap named McGee,
// Who dreamed as vast as the sea.
// Turned dreams into gold,
// With bravery bold
// And now lives as jolly as can be.

// Once lived a dreamer named Leigh,
// Who wished dreams were realities she could see.
// With strength and great might,
// She turned darkness to light,
// Now she lives in joy, hopeful and free.
