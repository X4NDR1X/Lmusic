<?php
function conectarBanco(): object
{
    $localservidor = 'localhost';
    $usuario = 'root';
    $senha = "";
    $nome_bd = 'lmusic';

    try {
        $conexao = new PDO("mysql:host=$localservidor;dbname=$nome_bd;charset=utf8", $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $ex) {
        die('Falha na conexão: ' . $ex->getMessage());
    }
}

function InserirServico(object $conexao, String $nome, $descricao, $arquivo_blob, $data): bool
{
    $comandoSQL = "insert into servico (Nome, Descricao, Imagem, Data) values (?, ?, ?, ?)";
    $dados = $conexao->prepare($comandoSQL);
    $dados->bindParam(1, $nome);
    $dados->bindParam(2, $descricao);
    $dados->bindParam(3, $arquivo_blob);
    $dados->bindParam(4, $data);


    if ($dados->execute()) {
        return true;
    } else {
        return false;
    }
}


function inserirUsuario(object $conexao, string $usuario, string $senha, int $tipoUsuario): bool
{
    $dados = $conexao->prepare("INSERT INTO usuario (nome, senha, tipoUsuario) VALUES (?, ?, ?)");
    $dados->bindParam(1, $usuario);
    $dados->bindParam(2, $senha);
    $dados->bindParam(3, $tipoUsuario);

    if ($dados->execute()) {
        return true;
    } else {
        return false;
    }
}

function autenticarUsuario(PDO $conexao, string $usuario, string $senha): bool
{
    $dados = $conexao->prepare("SELECT senha, tipoUsuario FROM usuario WHERE nome = ?");
    $dados->execute([$usuario]);
    $resultado = $dados->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        return false;
    }

    if ($senha === $resultado['senha']) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['usuario'] = $usuario;
        $_SESSION['tipoUsuario'] = $resultado['tipoUsuario'];

        return true;
    }

    return false;
}

function usuarioExiste(object $conexao, string $usuario): bool
{
    $dados = $conexao->prepare("SELECT 1 FROM usuario WHERE nome = ?");
    $dados->execute([$usuario]);
    return $dados->fetch() !== false;
}

function Listar(object $conexao, $IdUsuario)
{
    echo '<div id="album">';

    $comandoSQL = "SELECT * FROM servico order by Nome";
    $retorno = $conexao->prepare($comandoSQL);
    $retorno->execute();
    $registros = $retorno->fetchAll(PDO::FETCH_OBJ);

    foreach ($registros as $linha) {
        echo '<div class="servico">';

        // Nome
        echo '<div class="servico-nome">' . htmlspecialchars(mb_strtoupper($linha->Nome, 'UTF-8')) . '</div>';

        // Imagem
        if (!empty($linha->Imagem)) {
            echo '<div class="servico-imagem">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($linha->Imagem) . '" alt="Imagem do Serviço"/>';
            echo '</div>';
        }

        // Descrição
        echo '<div class="servico-descricao">' . nl2br(htmlspecialchars(mb_strtoupper($linha->Descricao))) . '</div>';

       if ($IdUsuario != 0) {
        if (!Administrador($conexao, NomeUsuario($conexao, $IdUsuario))) {
            echo '<div class="servico-acao">';
            if (VerificarFavorito($conexao, $IdUsuario, $linha->idServico)) {
                echo '<a href="albuns.php?idUsuario=' . htmlspecialchars($IdUsuario) .
                    '&idServico=' . htmlspecialchars($linha->idServico) .
                    '&favorito=2"><figure><img src="../images/favorite.png" alt="Imagem não encontrada!" height="40px"></figure></a>';
            } else {
                echo '<a href="albuns.php?idUsuario=' . htmlspecialchars($IdUsuario) .
                    '&idServico=' . htmlspecialchars($linha->idServico) .
                    '&favorito=1"><figure><img src="../images/unfavorite.png" alt="Imagem não encontrada!" height="40px"></figure></a>';
            }

            echo '</div>';
        }
    }
        echo '</div>';

        if ($IdUsuario != 0) {
            if (Administrador($conexao, NomeUsuario($conexao, $IdUsuario))) {
                echo '<div class="servico-acao">';
                echo '<a href="albuns.php?idServico=' . htmlspecialchars($linha->idServico) .
                    '&idUsuario=' . htmlspecialchars($IdUsuario) .
                    '&excluir=1" onclick="return confirm(\'Tem certeza que deseja deletar este álbum?\')">
        <figure><img id="delalter" src="../images/delete.png" alt="Imagem não encontrada!"></figure>
      </a>';
                echo '<a href="alterar.php?idServico=' . htmlspecialchars($linha->idServico) .
                    '&idUsuario=' . htmlspecialchars($IdUsuario) . '">
    <figure><img id="delalter" src="../images/alter.jpg" alt="Imagem não encontrada!"></figure>
</a>';


                echo '</div>';
            }
        }
    }

    return $registros;
}

function Buscar(PDO $conexao, int $IdUsuario, string $termo)
{
    echo '<div id="album">';

    $comandoSQL = "SELECT * FROM servico WHERE (Nome LIKE :termo OR Descricao LIKE :termo) order by Nome";

    $retorno = $conexao->prepare($comandoSQL);

    $termoBusca = '%' . $termo . '%';

    $retorno->bindParam(':idUsuario', $IdUsuario, PDO::PARAM_INT);
    $retorno->bindParam(':termo', $termoBusca, PDO::PARAM_STR);

    $retorno->execute();
    $registros = $retorno->fetchAll(PDO::FETCH_OBJ);

    foreach ($registros as $linha) {
        echo '<div class="servico">';

        // Nome
        echo '<div class="servico-nome">' . htmlspecialchars(mb_strtoupper($linha->Nome, 'UTF-8')) . '</div>';

        // Imagem
        if (!empty($linha->Imagem)) {
            echo '<div class="servico-imagem">';

            echo '<img src="data:image/jpeg;base64,' . base64_encode($linha->Imagem) . '" alt="Imagem do Serviço"/>';

            echo '</div>';
        }

        // Descrição
        echo '<div class="servico-descricao">' . nl2br(htmlspecialchars(mb_strtoupper($linha->Descricao))) . '</div>';

        if (!Administrador($conexao, NomeUsuario($conexao, $IdUsuario))) {
            echo '<div class="servico-acao">';

            if (VerificarFavorito($conexao, $IdUsuario, $linha->idServico)) {
                echo '<a href="buscar.php?idUsuario=' . htmlspecialchars($IdUsuario) .
                    '&idServico=' . htmlspecialchars($linha->idServico) .
                    '&favorito=2' .
                    '&termo=' . urlencode($termo) . '">' .
                    '<figure><img src="../images/favorite.png" alt="Imagem não encontrada!" height="40px"></figure></a>';
            } else {
                echo '<a href="buscar.php?idUsuario=' . htmlspecialchars($IdUsuario) .
                    '&idServico=' . htmlspecialchars($linha->idServico) .
                    '&favorito=1' .
                    '&termo=' . urlencode($termo) . '">' .
                    '<figure><img src="../images/unfavorite.png" alt="Imagem não encontrada!" height="40px"></figure></a>';
            }

            echo '</div>';
        }


        echo '</div>';

        if ($IdUsuario != 0 && Administrador($conexao, NomeUsuario($conexao, $IdUsuario))) {
            echo '<div class="servico-acao">';
            echo '<a href="albuns.php?idServico=' . htmlspecialchars($linha->idServico) . '&idUsuario=' . htmlspecialchars($IdUsuario) . '" onclick="return confirm(\'Tem certeza que deseja deletar este álbum?\')">';
            echo '<figure><img id="delalter" src="../images/delete.png" alt="Imagem não encontrada!"></figure></a>';
            echo '<a href="alterar.php?idServico=' . htmlspecialchars($linha->idServico) . '&idUsuario=' . htmlspecialchars($IdUsuario) . '">';
            echo '<figure><img id="delalter" src="../images/alter.jpg" alt="Imagem não encontrada!"></figure></a>';
            echo '</div>';
        }
    }

    echo '</div>';

    return $registros;
}


function IdUsuario(PDO $conexao, string $usuario): ?int
{
    $comandoSQL = "SELECT idUsuario FROM Usuario WHERE nome = ?";
    $retorno = $conexao->prepare($comandoSQL);
    $retorno->execute([$usuario]);
    $registro = $retorno->fetch(PDO::FETCH_OBJ);

    return $registro ? (int)$registro->idUsuario : null;
}

function NomeUsuario(PDO $conexao, int $IdUsuario): ?string
{
    $comandoSQL = "SELECT nome FROM Usuario WHERE IdUsuario = ?";
    $retorno = $conexao->prepare($comandoSQL);
    $retorno->execute([$IdUsuario]);
    $registro = $retorno->fetch(PDO::FETCH_OBJ);

    return $registro ? $registro->nome : null;
}


function Administrador(PDO $conexao, string $usuario): ?bool
{
    if (!$usuario) {
        return false;
    }
    $comandoSQL = "SELECT tipoUsuario FROM Usuario WHERE nome = ?";
    $retorno = $conexao->prepare($comandoSQL);
    $retorno->execute([$usuario]);
    $registro = $retorno->fetch(PDO::FETCH_OBJ);

    if ($registro && isset($registro->tipoUsuario)) {
        return (int)$registro->tipoUsuario === 1;
    }

    return false;
}

function DeletarAlbum(PDO $conexao, int $idServico): void
{
    $comandoSQL = "DELETE FROM servico WHERE idServico = ?";
    $retorno = $conexao->prepare($comandoSQL);
    $retorno->bindParam(1, $idServico, PDO::PARAM_INT);
    $retorno->execute();
}

function AlterarAlbum(PDO $conexao, int $idServico, string $nome, string $descricao, string $data): bool
{
    $comandoSQL = "UPDATE servico SET Nome = ?, Descricao = ?, Data = ? WHERE idServico = ?";
    $dados = $conexao->prepare($comandoSQL);
    $dados->bindParam(1, $nome);
    $dados->bindParam(2, $descricao);
    $dados->bindParam(3, $data);
    $dados->bindParam(4, $idServico, PDO::PARAM_INT);

    if ($dados->execute()) {
        return true;
    } else {
        return false;
    }
}

function DadosServico(PDO $conexao, int $idServico): array
{
    $comandoSQL = "SELECT * FROM servico WHERE idServico = ?";
    $retorno = $conexao->prepare($comandoSQL);
    $retorno->bindParam(1, $idServico, PDO::PARAM_INT);
    $retorno->execute();
    $registro = $retorno->fetch(PDO::FETCH_ASSOC);

    return $registro;
}

function FaleConosco(PDO $conexao, string $nome, string $email, string $mensagem): bool
{
    $comandoSQL = "INSERT INTO faleconosco (Nome, Email, Duvida) VALUES (?, ?, ?)";
    $dados = $conexao->prepare($comandoSQL);
    $dados->bindParam(1, $nome);
    $dados->bindParam(2, $email);
    $dados->bindParam(3, $mensagem);

    if ($dados->execute()) {
        return true;
    } else {
        return false;
    }
}

function ListarDuvidasUsuario(object $conexao, $nomeUsuario)
{
    echo '<div id="album">';

    $comando = $conexao->prepare("SELECT * FROM faleconosco WHERE Nome = :nome");

    $comando->bindParam(':nome', $nomeUsuario, PDO::PARAM_STR);

    $comando->execute();
    $registros = $comando->fetchAll(PDO::FETCH_OBJ);

    foreach ($registros as $linha) {
        echo '<div class="servico">';

        echo '<div class="servico-nome">' . htmlspecialchars(mb_strtoupper($linha->Nome, 'UTF-8')) . '</div>';
        echo '<div class="servico-nome">' . htmlspecialchars(mb_strtoupper($linha->Email, 'UTF-8')) . '</div>';
        echo '<div class="servico-descricao">' . nl2br(htmlspecialchars(mb_strtoupper($linha->Duvida))) . '</div>';

        echo '</div>';
    }

    echo '</div>';

    return $registros;
}

function ListarTodasDuvidas(object $conexao)
{
    echo '<div id="album">';

    $comandoSQL = "SELECT * FROM faleconosco order by Nome";
    $retorno = $conexao->prepare($comandoSQL);
    $retorno->execute();
    $registros = $retorno->fetchAll(PDO::FETCH_OBJ);

    foreach ($registros as $linha) {
        echo '<div class="servico">';

        echo '<div class="servico-nome">' . htmlspecialchars(mb_strtoupper($linha->Nome, 'UTF-8')) . '</div>';
        echo '<div class="servico-nome">' . htmlspecialchars(mb_strtoupper($linha->Email, 'UTF-8')) . '</div>';
        echo '<div class="servico-descricao">' . nl2br(htmlspecialchars(mb_strtoupper($linha->Duvida))) . '</div>';

        echo '</div>';
    }

    echo '</div>';

    return $registros;
}

function AdicionarFavorito(PDO $conexao, int $idUsuario, int $idServico): void
{
    $comandoSQL = "INSERT INTO interesse (idUsuario, idServico) VALUES (?, ?)";
    $dados = $conexao->prepare($comandoSQL);
    $dados->bindParam(1, $idUsuario, PDO::PARAM_INT);
    $dados->bindParam(2, $idServico, PDO::PARAM_INT);

    $dados->execute();
}

function DeletarFavorito(PDO $conexao, int $idUsuario, int $idServico): void
{
    $comandoSQL = "DELETE FROM interesse WHERE idUsuario = ? AND idServico = ?";
    $dados = $conexao->prepare($comandoSQL);
    $dados->bindParam(1, $idUsuario, PDO::PARAM_INT);
    $dados->bindParam(2, $idServico, PDO::PARAM_INT);

    $dados->execute();
}

function VerificarFavorito(PDO $conexao, int $idUsuario, int $idServico): bool
{
    $comandoSQL = "SELECT 1 FROM interesse WHERE idUsuario = ? AND idServico = ?";
    $dados = $conexao->prepare($comandoSQL);
    $dados->bindParam(1, $idUsuario, PDO::PARAM_INT);
    $dados->bindParam(2, $idServico, PDO::PARAM_INT);
    $dados->execute();

    return $dados->fetch() !== false;
}

function listarFavoritos(PDO $conexao, int $idUsuario)
{
    echo '<div id="album">';

    $comandoSQL = "
        SELECT s.*
        FROM interesse i
        INNER JOIN servico s ON s.idServico = i.idServico
        WHERE i.idUsuario = ?
        ORDER BY s.Nome
    ";

    $retorno = $conexao->prepare($comandoSQL);
    $retorno->bindParam(1, $idUsuario, PDO::PARAM_INT);
    $retorno->execute();

    $registros = $retorno->fetchAll(PDO::FETCH_OBJ);

    foreach ($registros as $linha) {
        echo '<div class="servico">';

        echo '<div class="servico-nome">' . htmlspecialchars(mb_strtoupper($linha->Nome, 'UTF-8')) . '</div>';

        if (!empty($linha->Imagem)) {
            echo '<div class="servico-imagem">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($linha->Imagem) . '" alt="Imagem do Serviço"/>';
            echo '</div>';
        }

        echo '<div class="servico-descricao">' . nl2br(htmlspecialchars(mb_strtoupper($linha->Descricao))) . '</div>';

        echo '<div class="servico-acao">';
        echo '<a href="favoritos.php?idUsuario=' . htmlspecialchars($idUsuario) .
            '&idServico=' . htmlspecialchars($linha->idServico) . '">
        <figure>
            <img src="../images/favorite.png" alt="Imagem não encontrada!" height="40px">
        </figure>
    </a>';

        echo '</div></div>';
    }

    echo '</div>';

    return $registros;
}
