<?php

$word = isset($_GET['query']) ? trim($_GET['query']) : 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

$tips = file_get_contents("http://wooordhunt.ru/get_tips.php?abc={$word}");

if ($tips) {
    $tips = json_decode($tips, true);
    $result = [
        'status' => 200,
        'data' => [
            'tips' => $tips['tips']
        ]
    ];
} else {
    $result['status'] = 404;
}

echo json_encode($result);