<?php
function getYouTubeVideoId($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $params);
    return $params['v'] ?? '';
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

    if (!empty($videoId)) {
        $videoInfo = getYouTubeVideoInfo($videoId);
        echo json_encode(['title' => $videoInfo['title'], 'thumbnail' => $videoInfo['thumbnail_url']]);
    } else {
        echo json_encode(['title' => '', 'thumbnail' => '']);
    }
}
?>
