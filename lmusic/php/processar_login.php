<?php
include_once 'funcoes.php';

/* O operador null coalescing (??) verifica se a expressão à esquerda existe e não é null.
Se existir e não for null, retorna esse valor; caso contrário, retorna o valor à direita.
Exemplo: $usuario = $_POST['usuario'] ?? '';
Se $_POST['usuario'] estiver definido, $usuario recebe esse valor.
Se não estiver definido, $usuario recebe string vazia '' (evita warnings e garante valor).
*/

$conexao = conectarBanco();

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';


$idUsuario = IdUsuario($conexao, $usuario); // Obtém o ID do usuário recém-criado.


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
                <a href="index.php?idUsuario=<?php echo htmlspecialchars($idUsuario); ?>" class="link">
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
            <?php
            $idUsuario = IdUsuario($conexao, $usuario);
            $tipoUsuario = Administrador($conexao, $usuario);

            if ($tipoUsuario) {
            ?>
                <?php
                if (autenticarUsuario($conexao, $usuario, $senha)) {
                    echo "Login bem-sucedido! Bem-vindo(a) $usuario.";

                ?>
                    
                    <a href="form_upload.php?idUsuario=<?php echo htmlspecialchars($idUsuario); ?>" id="aref">Inserir Álbuns</a>
                    <a href="index.php?idUsuario=<?php echo htmlspecialchars($idUsuario); ?>" id="aref">Página Inicial</a>
                    <a href="albuns.php?idUsuario=<?php echo htmlspecialchars($idUsuario); ?>" id="aref">Álbuns</a>

                <?php
                } else {
                    echo "Falha no login. Usuário ou senha incorretos!";
                ?>
                    <div id="aref">
                        <a href="login.php">Voltar</a>
                    </div>
                <?php
                }
                ?>

                <?php

            } else {
                if (autenticarUsuario($conexao, $usuario, $senha)) {
                    echo "Login bem-sucedido! Bem-vindo(a) " . mb_strtoupper($usuario, 'UTF-8') . "!";
                ?>
                    <a href="index.php?idUsuario=<?php echo htmlspecialchars($idUsuario); ?>" id="aref">Página Inicial</a>
                <?php
                } else {
                    echo "Falha no login. Usuário ou senha incorretos!";
                ?>
                    <div id="aref">
                        <a href="login.php">Voltar</a>
                    </div>
                <?php
                }
                ?>

            <?php
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