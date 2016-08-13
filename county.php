<?php

$base = json_decode(file_get_contents(__DIR__ . '/json/W-C0033-001.json')); //天氣特報-各別縣市地區目前之天氣警特報情形
$result = array();
foreach ($base->dataset->location AS $area) {
    $area->helper = array();
    $result[fixName($area->locationName)] = $area;
}

$data = json_decode(file_get_contents(__DIR__ . '/json/F-C0032-001.json')); //一般天氣預報-今明36小時天氣預報
foreach ($data->dataset->location AS $area) {
    $result[fixName($area->locationName)]->recent = $area->weatherElement;
}

$data = json_decode(file_get_contents(__DIR__ . '/json/F-C0032-005.json')); //一般天氣預報-一週縣市天氣預報
foreach ($data->dataset->location AS $area) {
    $result[fixName($area->locationName)]->week = $area->weatherElement;
}

for ($i = 9; $i <= 30; $i ++) {
    $f = __DIR__ . '/json/F-C0032-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.json'; // 一般天氣預報-天氣小幫手
    if (file_exists($f)) {
        $data = json_decode(file_get_contents($f));
        foreach ($data->dataset->parameterSet->parameter AS $p) {
            $result[fixName($data->dataset->location->locationName)]->helper[] = $p->parameterValue;
        }
    }
}

$targetPath = __DIR__ . '/json/area';
if (!file_exists($targetPath)) {
    mkdir($targetPath, 0777, true);
}

foreach ($result AS $county => $data) {
    file_put_contents($targetPath . '/' . $county . '.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function fixName($name) {
    return str_replace('台', '臺', $name);
}
