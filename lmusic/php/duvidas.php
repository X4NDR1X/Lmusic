<?php
include_once 'funcoes.php';

$idUsuario = $_GET['idUsuario'] ?? 0;
$conexao = conectarBanco();

$NomeUsuario = NomeUsuario($conexao, $idUsuario);
$tipoUsuario = Administrador($conexao, $NomeUsuario);



?>
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
                <a href="index.php<?= $idUsuario ? '?idUsuario=' . htmlspecialchars($idUsuario) : ''; ?>" class="link">
                    <figure>
                        <img
                            src="../images/logo.png"
                            alt="Imagem não encontrada!"
                            width="100px" />
                    </figure>
                </a>
            </div>
        </div>

        <div id="navLinks">
            <nav id="links">
                <a href="albuns.php<?= $idUsuario ? '?idUsuario=' . htmlspecialchars($idUsuario) : ''; ?>" class="link">Álbuns</a>
                <a href="faleconosco.php<?= $idUsuario ? '?idUsuario=' . htmlspecialchars($idUsuario) : ''; ?>" class="link">Fale Conosco</a>
            </nav>
        </div>

        <div id="search">
            <form action="buscar.php" method="get">
                <input type="text" placeholder="Buscar" name="termo" value="<?= htmlspecialchars($termo ?? '') ?>">
                <input type="number" name="idUsuario" value="<?php echo htmlspecialchars($idUsuario); ?>" hidden>
                <input type="submit" hidden>
            </form>
        </div>

        <!---- MENU DROPDOWN --->
        <script src="../script.js"></script>
        <div id="user">
            <div class="dropdown-wrapper">
                <button class="dropdown-trigger">
                    <span>Menu</span>
                    <svg class="chevron-icon" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M7 10l5 5 5-5z" fill="currentColor"></path>
                    </svg>
                </button>

                <div class="dropdown-menu">
                    <?php
                    // Se existe um id válido
                    if ($idUsuario) {
                        $usuario = NomeUsuario($conexao, $idUsuario);

                        if ($usuario) {
                            // Mostra mensagem de boas-vindas
                            echo "<div id='welcome'>";
                            echo "Bem vindo(a) " . htmlspecialchars(mb_strtoupper($usuario, 'UTF-8')) . "!";
                            echo "</div>";

                            // Se for administrador
                            if (Administrador($conexao, $usuario)) {
                                echo "<div class='dropdown-item'>
                        <span><a href='form_upload.php?idUsuario=" . htmlspecialchars($idUsuario) . "'>Inserir Álbum</a></span>
                      </div>";
                                echo "<div class='dropdown-item'>";
                                echo "<span><a href='duvidas.php?idUsuario=" . htmlspecialchars($idUsuario) . "'>Dúvidas</a></span>";
                                echo " </div>";
                            } else {
                                // Se for usuário comum
                                echo "<div class='dropdown-item'>
                        <span><a href='favoritos.php?idUsuario=" . htmlspecialchars($idUsuario) . "'>Favoritos</a></span>
                      </div>";
                                echo "<div class='dropdown-item'>";
                                echo "<span><a href='duvidas.php?idUsuario=" . htmlspecialchars($idUsuario) . "'>Minhas Dúvidas</a></span>";
                                echo " </div>";
                            }

                            // Botão de sair
                            echo "<div class='dropdown-item'>
                      <span><a href='login.php'>Sair</a></span>
                    </div>";
                        } else {
                            // Caso o idUsuario não tenha um nome correspondente
                            echo "<div class='dropdown-item'>
                      <span><a href='login.php'>Entrar</a></span>
                    </div>";
                        }
                    } else {
                        // Caso não tenha idUsuario na URL
                        echo "<div class='dropdown-item'>
                    <span><a href='login.php'>Entrar</a></span>
                  </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <!---- FIM DO MENU DROPDOWN --->

    </div>

    <div id="content">
        <div id="services">
            <?php
            echo "<h1>DUVIDAS E PERGUNTAS<br><br></h1>";

            if ($tipoUsuario) {
                $duvidas = ListarTodasDuvidas($conexao);
            } else {
                $duvidas = ListarDuvidasUsuario($conexao, $NomeUsuario);
            }

            ?>
        </div>
    </div>

    <div id="baseboard">
        <div id="transition"></div>
        <h1>
            Desenvolvido por Lucas Andriel Ferreira - IFSUL Venâncio Aires - INF3AM - 2025
        </h1>
    </div>
</body>

</html>