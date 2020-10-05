<?php

$json = file_get_contents(__DIR__ . '/store_1000_docs.json');
$data = json_decode($json);

foreach ($data as $i => $obj) {
    $data[$i]->pets = rand(0, 9) < 2 ? null : rand(1, 3);
    if (rand(0, 9) < 3) {
        $data[$i]->suprises = !!rand(0, 1);
    }
}

$json = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/store_1000_docs.json', $json);