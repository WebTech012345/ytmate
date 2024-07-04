<?php
function getYouTubeVideoId($url) {
    if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } elseif (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } elseif (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } elseif (preg_match('/youtube\.com\/.+\#v=([^&\?\/]+)/', $url, $id)) {
        return $id[1];
    } else {
        return false;
    }
}

function getYouTubeVideoInfo($videoId) {
    $apiUrl = "https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=" . $videoId . "&format=json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0'));
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $url = $_GET['url'];
    $videoId = getYouTubeVideoId($url);

    if ($videoId) {
        $videoInfo = getYouTubeVideoInfo($videoId);
        echo json_encode(['title' => $videoInfo['title'], 'thumbnail' => $videoInfo['thumbnail_url']]);
    } else {
        echo json_encode(['title' => '', 'thumbnail' => '']);
    }
}
?>
