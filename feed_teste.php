<?php
include './database.php';
include './usuario.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/principal.css" />
    <link rel="icon" href="./assets/image1.png" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Principal</title>
</head>

<body>
    <div class="lateral">
        <img src="./assets/logo.png" alt="logo" />
        <table>
            <ul>
                <li>
                    <div class="item" id="principal">
                        <a href="./index.html">
                            <ion-icon name="home-outline"></ion-icon>Principal</a>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <a href="pesquisaAdicionar.php">
                            <ion-icon name="bookmark-outline"></ion-icon>Leituras</a>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <a href="pesquisaAvaliar.php">
                            <ion-icon name="star-outline"></ion-icon>Avaliações</a>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <a href="./perfil.php">
                            <ion-icon name="analytics-outline"></ion-icon>Atividade</a>
                    </div>
                </li>
            </ul>
        </table>
        <div id="sair">
            <a href="logout.php">
                <ion-icon name="exit-outline"></ion-icon>Sair</a>
        </div>
    </div>

    
        <?php
        echo "<div id=feed>";
            $consulta_feed = mysqli_query($conexao, "SELECT * FROM feed ORDER BY idFeed DESC;");
            $consulta_avaliacao = mysqli_query($conexao, "SELECT * FROM avaliacao;");
            while ($feed = mysqli_fetch_assoc($consulta_feed)) {
                
                if ($feed['tipo'] == 'leitura') {
                    
                    $consulta_leitura = mysqli_query($conexao, "SELECT * FROM leitura WHERE idLeitura = " . $feed['idLeitura'] . ";");
                    $leitura = mysqli_fetch_assoc($consulta_leitura);
                    $consulta_usuario = mysqli_query($conexao, "SELECT * FROM usuario WHERE idUsuario = " . $leitura['idUsuario'] . ";");
                    $usuario = mysqli_fetch_assoc($consulta_usuario);
                    $consulta_obra = mysqli_query($conexao, "SELECT * FROM obra WHERE idObra = " . $leitura['idObra'] . ";");
                    $obra = mysqli_fetch_assoc($consulta_obra);
                    $idApi = $obra['idApi'];
                    // URL da API com o ID do livro
                    $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . urlencode($idApi);
                    // Inicializando o cURL
                    $curl = curl_init();
                    // Configurando o cURL
                    curl_setopt($curl, CURLOPT_URL, $apiUrl);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    // Executando a requisição
                    $response = curl_exec($curl);
                    // Verificando erros
                    if ($response === false) {
                        die('Erro na requisição: ' . curl_error($curl));
                    }
                    // Fechando o cURL
                    curl_close($curl);
                    // Decodificando a resposta JSON
                    $livro = json_decode($response, true);
                    
                    $capa = $livro['volumeInfo']['imageLinks']['thumbnail'];
                    
                    echo "<div class='feed-item'>";
                    echo "<p><strong>" . $usuario['nome'] . '</strong> ' . $leitura['situacao'] . ' o livro <strong>' . $obra['titulo'] . ".</strong></p>";
                    if ($capa) {
                        echo "<img src='" . $capa, ENT_QUOTES . "' alt='Capa do livro' style='width: 150px; height: auto;'>";
                    }
                    echo "</div>";
                }
                
                if ($feed['tipo'] == 'avaliacao') {
                    $consulta_avaliacao = mysqli_query($conexao, "SELECT * FROM avaliacao WHERE idAvaliacao = " . $feed['idAvaliacao'] . ";");
                    $avaliacao = mysqli_fetch_assoc($consulta_avaliacao);
                    $consulta_usuario = mysqli_query($conexao, "SELECT * FROM usuario WHERE idUsuario = " . $avaliacao['idUsuario'] . ";");
                    $usuario = mysqli_fetch_assoc($consulta_usuario);
                    $consulta_obra = mysqli_query($conexao, "SELECT * FROM obra WHERE idObra = " . $avaliacao['idObra'] . ";");
                    $obra = mysqli_fetch_assoc($consulta_obra);
                    $idApi = $obra['idApi'];
                    // URL da API com o ID do livro
                    $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . urlencode($idApi);
                    // Inicializando o cURL
                    $curl = curl_init();
                    // Configurando o cURL
                    curl_setopt($curl, CURLOPT_URL, $apiUrl);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    // Executando a requisição
                    $response = curl_exec($curl);
                    // Verificando erros
                    if ($response === false) {
                        die('Erro na requisição: ' . curl_error($curl));
                    }
                    // Fechando o cURL
                    curl_close($curl);
                    // Decodificando a resposta JSON
                    $livro = json_decode($response, true);
                    
                    $capa = $livro['volumeInfo']['imageLinks']['thumbnail'];
                    $nota = intval($avaliacao['nota']); // Convertendo nota para inteiro
                    $emojis = str_repeat('⭐', $nota); // Repete a estrela conforme a nota
                    
                    echo "<div class='feed-item'>";
                    echo "<p><strong>" . $usuario['nome'] . '</strong> avaliou o livro <strong>' . $obra['titulo'] . '</strong> com <strong>' . $avaliacao['nota'] . ' estrelas' . $emojis . ".</strong></p>";
                    if ($capa) {
                        echo "<img src='" . $capa, ENT_QUOTES . "' alt='Capa do livro' style='width: 150px; height: auto;'>";
                    }
                    
                    $consulta_comentario = mysqli_query($conexao, "SELECT * FROM comentario WHERE idAvaliacao = " . $feed['idAvaliacao'] . " ORDER BY idComentario DESC;");
                    while ($comentario = mysqli_fetch_assoc($consulta_comentario)){
                        echo "<p>" . 'comentario: ' . $comentario['comentario'] ."</p>";
                    }
                    echo "</div>";
                }
            }
            echo "</div>";
        ?>
        <a href="pesquisaAdicionar.php">adicionar</a>
        <a href="pesquisaAvaliar.php">avaliar</a>

</body>
</html>