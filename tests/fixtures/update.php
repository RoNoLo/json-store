<?php

$json = file_get_contents(__DIR__ . '/store_1000_docs.json');
$data = json_decode($json);

foreach ($data as $i => $obj) {
    $data[$i]->quote = rand(0, 9) < 2 ? "" : "Carpe diem.";
}

$json = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/store_1000_docs.json', $json);