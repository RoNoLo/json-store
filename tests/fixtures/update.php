<?php

$json = file_get_contents(__DIR__ . '/store_1000_docs.json');
$data = json_decode($json);

foreach ($data as $i => $obj) {
    $foo = range(rand(0, 4), rand(4, 20));
    sort($foo);
    $data[$i]->range = $foo;
}

$json = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents(__DIR__ . '/store_1000_docs.json', $json);