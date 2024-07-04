<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $youtubeUrl = $_POST['youtubeUrl'];
    $format = $_POST['format'];
    $fileName = !empty($_POST['fileName']) ? sanitizeFilename($_POST['fileName']) : 'downloaded_file';

    $apiUrl = "https://api.onlinevideoconverter.pro/api/convert";

    $postData = [
        'url' => $youtubeUrl,
        'format' => $format === 'mp3' ? 'mp3' : 'mp4'
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result && isset($result['download_url'])) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'. $fileName . '.' . $format .'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($result['download_url']);
        exit;
    } else {
        echo "Error downloading video.";
    }
}

function sanitizeFilename($filename) {
    return preg_replace('/[^\w\s-]/', '', $filename);
}

?>
