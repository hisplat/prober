#! /usr/bin/php
<?php

$plain = file_get_contents("http://leopard.hismarttv.com/hisplat/prober/?action=api.v1.info.listdeprecated");

$deprecated = json_decode($plain, true);


if (empty($deprecated)) {
    exit(0);
}

$total = count($deprecated);
$index = 1;
foreach ($deprecated as $node) {
    $id = $node["id"];
    $file = $node["filename"];
    $url = "http://leopard.hismarttv.com/hisplat/prober/?action=api.v1.info.remove&id=$id";
    $c = file_get_contents($url);
    echo "($index/$total) clear $id -- $file.\n";
    $index++;
    // echo $c;
}

