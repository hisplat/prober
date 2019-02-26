<?php

function iplookup($ip) {
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=$ip";
    $c = file_get_contents($url);
    $c = json_decode($c, true);

    $s = $c["data"]["region"] . $c["data"]["city"] . "(" . $c["data"]["isp"] . ")";
    return $s;
}


// $c = iplookup("112.6.118.226");
// print_r($c);

