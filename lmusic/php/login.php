<?php
include 'funcoes.php';
$conexao = conectarBanco();
$idUsuario = 0;

?>

</html>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu Principal</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div id="menu">
        <div id="logo">
            <div id="lmusic">
                <a href="index.php">
                    <figure>
                        <img
                            src="../images/logo.png"
                            alt="Imagem não encontrada!"
                            width="100px" />
                    </figure>
                </a>
            </div>
        </div>

        <div id="user2"></div>
    </div>
    <div id="content">
        <div id="forms">
            <form action="processar_login.php" method="post">
                <legend>Entrar no LMusic</legend><br><br>

                <label>Usuário:</label><br>
                <input type="text" name="usuario" required><br><br>

                <label>Senha:</label><br>
                <input type="password" name="senha" required><br><br>

                <input type="submit" value="Entrar"><br><br>

                <label>Não tem uma conta? <a href="singin.php">Cadastre-se aqui!</a></label>
            </form>
        </div>
    </div>
    <div id="baseboard">
        <div id="transition"></div>
        <h1>
            Desenvolvido por Lucas Andriel Ferreira - IFSUL Venâncio Aires - INF3AM
            - 2025
        </h1>
    </div>
</body>

</html>