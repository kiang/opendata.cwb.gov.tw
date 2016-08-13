<?php

$jsonPath = __DIR__ . '/json';
if (!file_exists($jsonPath)) {
    mkdir($jsonPath, 0777, true);
}
foreach (glob(__DIR__ . '/raw/*') AS $rawFile) {
    $p = pathinfo($rawFile);
    switch (mime_content_type($rawFile)) {
        case 'application/xml':
            file_put_contents($jsonPath . '/' . $p['basename'] . '.json', json_encode(simplexml_load_file($rawFile), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            break;
        case 'application/zip':
//            $tmpPath = __DIR__ . '/tmp/' . date('Ymd') . '/' . $p['basename'];
//            if (!file_exists($tmpPath)) {
//                mkdir($tmpPath, 0777, true);
//            }
//            $zip = new ZipArchive();
//            $zip->open($rawFile);
//            $zip->extractTo($tmpPath);
            break;
    }
}