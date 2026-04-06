<?php

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar usuário</title>
    <link rel="stylesheet" href="../style.css">
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
        <div id="user2">

        </div>
    </div>
    <div id="content">
        <div id="forms">
            <form action="processar_singin.php" method="post">
                <legend>Cadastrar novo usuário</legend><br><br>

                <label>Usuário:</label><br>
                <input type="text" name="usuario" required><br><br>
                <label>Senha:</label><br>
                <input type="password" name="senha" required><br><br>
                <label>
                    <input type="checkbox" name="tipo" value="1">
                    Administrador
                </label>
                <br><br>
                <input type="submit" value="Cadastrar"><br><br>

                <label>Já tem uma conta? <a href="login.php">Entre aqui!</a></label>
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