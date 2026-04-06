<?php
include 'funcoes.php'; // Inclui o arquivo com funções auxiliares, como conectar ao banco.
$conexao = conectarBanco(); // Abre a conexão com o banco de dados.

$usuario = $_POST['usuario'] ?? ''; // Recebe o nome digitado no formulário.
$senha = $_POST['senha'] ?? '';     // Recebe a senha digitada no formulário.
$tipo = isset($_POST['tipo']) ? intval($_POST['tipo']) : 0; // Recebe o tipo de usuário, padrão 0.



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
                <figure>
                    <img
                        src="../images/logo.png"
                        alt="Imagem não encontrada!"
                        width="100px" />
                </figure>
            </div>
        </div>
        <div id="user2">
           
        </div>
    </div>
    <div id="content">
        <div id="forms">
            <?php
            if (usuarioExiste($conexao, $usuario)) {
                echo "Usuário já existe! Escolha outro nome de Usuário!"; // Se já existe, mostra mensagem e para.
            ?>
                <div id="aref">
                    <br><a href="singin.php">Entrar</a>
                </div>
                <?php
            } else {

                if (inserirUsuario($conexao, $usuario, $senha, $tipo)) {
                    echo "Usuário cadastrado com sucesso!"; // Se deu certo, mostra mensagem de sucesso.
                ?>
                    <div id="aref">
                        <br><a href="login.php">Entrar</a>
                    </div>
                <?php
                } else {
                    echo "Erro ao cadastrar usuário!"; // Se deu erro, mostra mensagem de erro.
                ?>
                    <div id="aref">
                        <br><a href="singin.php">Entrar</a>
                    </div>
            <?php
                }
            }
            ?>
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