<?php

$fh = fopen(__DIR__ . '/list.csv', 'r');
$rawPath = __DIR__ . '/raw/' . date('Ymd');
if (!file_exists($rawPath)) {
    mkdir($rawPath, 0777, true);
}
$header = '';
while ($line = fgetcsv($fh, 2048)) {
    if (!empty($line[1])) {
        $line[1] = trim($line[1]);
        if (!file_exists($rawPath . '/' . $line[1])) {
            file_put_contents($rawPath . '/' . $line[1], file_get_contents('http://opendata.cwb.gov.tw/opendataapi?dataid=' . $line[1] . '&authorizationkey=CWB-15296E81-2B15-4277-BE49-549BCEAFD553'));
        }
        $size = filesize($rawPath . '/' . $line[1]) / 1024 / 1024;
        if ($size > 100) {
            $zip = new ZipArchive();
            $zip->open(__DIR__ . '/tmp/zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile($rawPath . '/' . $line[1]);
            $zip->close();
            copy(__DIR__ . '/tmp/zip', $rawPath . '/' . $line[1]);
        }
    }
}