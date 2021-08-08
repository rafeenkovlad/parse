<?php
require_once("../vendor/autoload.php");
use Stomshop\Stomshop;

$url="https://stomshop.pro/stomatologicheskoye-oborudovaniye/?page=1";

function stomshop ($url){
    $goutte = new Stomshop($url);
}

do{
    stomshop ($url);
    preg_match('/(?<=)(\d+)/sxi', $url, $i);
    $url = preg_replace('/(?<=)(\d+)/sxi', $i[0]+1, $url);
    print_r($i[0]);
    echo '][';
    print_r($url);
}while($i[0]<18);

