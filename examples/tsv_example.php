<?php

require __DIR__ . '/../vendor/autoload.php';

$iter = new Chrisguitarguy\FileIterator\SplitterIterator(__DIR__ . '/test.tsv', "\t");

foreach ($iter as $index => $line) {
    echo "Line {$index}", PHP_EOL;
    foreach ($line as $item) {
        echo "\t", $item, PHP_EOL;
    }
}

