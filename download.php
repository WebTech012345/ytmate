<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $youtubeUrl = $_POST['youtubeUrl'];
    $format = $_POST['format'];

    if ($format == 'mp4') {
        $videoId = getYouTubeVideoId($youtubeUrl);
        $videoInfo = getYouTubeVideoInfo($videoId);
        $videoTitle = sanitizeFilename($videoInfo['title']);
        $videoUrl = $videoInfo['url'];

        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $videoTitle . ".mp4\"");

        readfile($videoUrl);
        exit;
    } elseif ($format == 'mp3') {
        // Convert to MP3 using ffmpeg or similar tools
        // This example assumes you have a local ffmpeg installation
        $videoId = getYouTubeVideoId($youtubeUrl);
        $videoInfo = getYouTubeVideoInfo($videoId);
        $videoTitle = sanitizeFilename($videoInfo['title']);
        $videoUrl = $videoInfo['url'];

        $tempMp4File = tempnam(sys_get_temp_dir(), 'video') . '.mp4';
        $tempMp3File = tempnam(sys_get_temp_dir(), 'audio') . '.mp3';

        file_put_contents($tempMp4File, file_get_contents($videoUrl));

        // Replace with the actual path to ffmpeg
        $ffmpegPath = '/path/to/ffmpeg'; // Example: '/usr/local/bin/ffmpeg'

        exec("$ffmpegPath -i $tempMp4File -vn -ar 44100 -ac 2 -ab 192k $tempMp3File");

        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $videoTitle . ".mp3\"");

        readfile($tempMp3File);

        // Clean up temporary files
        unlink($tempMp4File);
        unlink($tempMp3File);

        exit;
    }
}

function getYouTubeVideoId($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $params);
    return $params['v'] ?? '';
}

function getYouTubeVideoInfo($videoId) {
    $videoInfo = [];

    if (!empty($videoId)) {
        $videoInfo['url'] = "https://www.youtube.com/watch?v=$videoId";

        // Example: You could use a library like `youtube-dl` in PHP or another method to fetch video title and download URL
        // For simplicity, this example uses a direct URL
        $videoInfo['title'] = 'Sample Video Title'; // Replace with actual video title
    }

    return $videoInfo;
}

function sanitizeFilename($filename) {
    // Sanitize filename for security and usability
    return preg_replace('/[^\w\s-]/', '', $filename);
}

?>
