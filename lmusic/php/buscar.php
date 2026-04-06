<?php
include 'funcoes.php';
$conexao = conectarBanco();

$idUsuario = $_GET['idUsuario'] ?? 0;
$termo = $_GET['termo'] ?? '';
$idServico = $_GET['idServico'] ?? 0;
$favorito = $_GET['favorito'] ?? 0;

if ($favorito == 1) {
    $adicionarFavorito = AdicionarFavorito($conexao, $idUsuario, $idServico);
}

if ($favorito == 2) {
    $removerFavorito = DeletarFavorito($conexao, $idUsuario, $idServico);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Álbuns</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <script src="../script.js"></script>
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
        <div id="navLinks">
            <nav id="links">
                <a href="albuns.php?idUsuario=<?php echo htmlspecialchars($idUsuario); ?>" class="link">Álbuns</a>
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
    </div>
    <div id="content">
        <div id="services">
            <?php
            echo "<h1>BUSCANDO POR: $termo <br><br></h1>";
            $servicos = Buscar($conexao, $idUsuario, $termo);
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