<?php

require __DIR__ . '/vendor/autoload.php';

use DiDom\Document;

$word = isset($_GET['word']) ? trim($_GET['word']) : 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
$document = new Document("http://wooordhunt.ru/word/{$word}", true);

if ($document->find('#word_not_found')) {
    $result['status'] = 404;
} else {
    $translate = $document->find('.t_inline_en');
    $parts = $document->find('h4');
    $partExamples = $document->find('.tr');

    foreach($partExamples as $key => $post) {
        $index = $parts[$key]->text();
        $result['parts'][$index] = [];
        $elements = $post->find('span');
        foreach ($elements as $element) {
            array_push($result['parts'][$index], $element->getNode()->textContent);
        }
    }
    $phrases = $document->find('.phrases');
    foreach($phrases as $key => $post) {
        $post = explode('   ', $post->text());


        $lastIndex = count($post) - 1;
        $post[$lastIndex] = str_replace('Воспользуйтесь поиском для того, чтобы найти нужное словосочетание, или посмотрите все.', '', $post[$lastIndex]);

        $post = array_map(function($elem) {
            return trim($elem);
        }, $post);

        $post = array_filter($post, function($elem) {
            return $elem;
        });

        $result['phrases'] = $post;
    }

    $examplesRu = $document->find('.ex_t.human');
    $examplesEn = $document->find('.ex_o');

    foreach ($examplesRu as $key => $exampleRu) {
        $result['examples'][] = [
            'en' => $examplesEn[$key]->text(),
            'ru' => $exampleRu->text()
        ];
    }

    $result = [
        'status' => 200,
        'data' => $result
    ];
}

echo json_encode($result);