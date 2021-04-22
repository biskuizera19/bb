<?php
date_default_timezone_set('America/Sao_Paulo');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type");

require_once '../../validation/guzzle/autoload.php';

use GuzzleHttp\Client;

/***********************************Banco************************************************/
$client = new Client();
$datetime = date('Y-m-d H:i:s');
/***********************************Banco************************************************/

function api_login($usuario, $senha, $ip)
{
    $client = new Client();
    try {
        $res = $client->request('GET', 'https://app.gonder.com.br/eurotransfer/apinova/api/teste/usuario=' . $usuario . '&senha=' . $senha . '&ip=' . $ip . '', [
            'defaults' => [
                'exceptions' => true
            ],
            'headers' => [
                'Content-Type'     => 'application/json',
            ]
        ]);
        $data = json_decode((string) $res->getBody(), true);
        return $data;
    } catch (Exception $e) {
        $returnArr = array(
            "msg" => 'ERRO CONEXAO',
        );
        return json_encode($returnArr);
    }
}


function getIp()
{
    foreach (array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ) as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $IPaddress) {
                $IPaddress = trim($IPaddress);
                if (filter_var($IPaddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $IPaddress;
                }
            }
        }
    }
}

function iniciais($nome)
{
    $formatar1 = strtolower($nome);
    $result1 = preg_replace('~\b[a-z]{1,2}\b\s*~', '', $formatar1);
    $string = strtoupper($result1);

    if (!(empty($string))) {
        if (strpos($string, " ")) {
            $string = explode(" ", $string);
            $count = count($string);
            $new_string = ' ';
            for ($i = 0; $i < $count; $i++) {
                $first_letter = substr(ucwords($string[$i]), 0, 1);
                $new_string .= "$first_letter ";
            }
            return "$new_string";
        } else {
            $first_letter = substr(ucwords($string), 0, 1);
            $string = $first_letter;
            return $string;
        }
    } else {
        return "empty string!";
    }
}
