<?php

function getRandomHex($lenght) {
    $string = substr(str_shuffle(str_repeat(str_shuffle("012345679abcdef"),$lenght)),$lenght,$lenght);
    $hex='';
    for ($i=0; $i < strlen($string); $i++){$hex .= dechex(ord($string[$i]));}
    return $hex;
}

function connexion_bdd(){
    $db = new PDO('mysql:host=localhost;dbname=ldap2', 'root', 'btssio');
    return $db;
}
