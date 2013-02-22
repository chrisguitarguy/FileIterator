<?php

require __DIR__ . '/../vendor/autoload.php';

$iter = new Chrisguitarguy\FileIterator\FileIterator(__DIR__ . '/test.txt');

foreach ($iter as $line) {
    echo $line, PHP_EOL;
}
