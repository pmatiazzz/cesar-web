<?php
include './database.php';
include './usuario.php';
session_start();
?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/principal.css" />
    <link rel="icon" href="./assets/image 1.png" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Menu Principal</title>
</head>

<body>
    <div class="lateral">
        <img src="./assets/logo.png" alt="logo" />
        <table>
            <ul>
                <li>
                    <div class="item" id="principal">
                        <a href="./feed_teste.php">
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
    echo "<div id='perfil'>";
    ?>
    <h1>Usuário logado: <strong><?php echo $_SESSION['user']->getNome();?></strong></h1>

    <?php
        echo '<br><h4>Minhas Leituras:</h4>';
    ?>
    <div id='leituras-perfil'>
    <?php
    $consulta_leitura = mysqli_query($conexao, "SELECT * FROM leitura WHERE idUsuario = " . $_SESSION['user']->getCod() . " ORDER BY idLeitura DESC;");
    
    while ($leituras = mysqli_fetch_assoc($consulta_leitura)) {
        
        $consulta_livro = mysqli_query($conexao, "SELECT idApi FROM obra WHERE idObra = " . $leituras['idObra'] . ";");
        $livros = mysqli_fetch_assoc($consulta_livro);
        $idLivro = $livros['idApi'];
        
        // URL da API com o ID do livro
        $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . urlencode($idLivro);

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
        
        if (!empty($livro['volumeInfo'])) {
            $titulo = $livro['volumeInfo']['title'];
            $capa = $livro['volumeInfo']['imageLinks']['thumbnail'];
            
            // Cada leitura agora está envolta em um div com classe="leitura-item"
            echo "<div class='leitura-item'>";
            echo "<h1>$titulo</h1>"; // Título no topo
            if ($capa) {
                echo "<img src='" . $capa, ENT_QUOTES . "' alt='Capa do livro'>";
            }
            echo "<div class='buttons'>";
            echo "<a href=avaliarLivro.php/?livro=" . $idLivro . "><button id='botao'>Avaliar</button></a>";
            echo "<a href=editar_leitura.php/?leitura=" . $leituras['idLeitura'] . "><button id='botao'>Editar</button></a>";
            echo "</div>"; // Botões à direita
            echo "</div>"; // Fecha o div para cada leitura
        }
    }
    ?>
</div>

<div id='leituras-perfil'>
    <?php
    //avaliações
    echo '<br><h4>Minhas Avaliações:</h4>';
    
    $consulta_avaliacao = mysqli_query($conexao, "SELECT * FROM avaliacao WHERE idUsuario = " . $_SESSION['user']->getCod() . " ORDER BY idAvaliacao DESC;");
    
    while ($avaliacoes = mysqli_fetch_assoc($consulta_avaliacao)) {
        
        $consulta_livro = mysqli_query($conexao, "SELECT idApi FROM obra WHERE idObra = " . $avaliacoes['idObra'] . ";");
        $livros = mysqli_fetch_assoc($consulta_livro);
        $idLivro = $livros['idApi'];
        
        // URL da API com o ID do livro
        $apiUrl = "https://www.googleapis.com/books/v1/volumes/" . urlencode($idLivro);

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
        
        if (!empty($livro['volumeInfo'])) {
            $titulo = $livro['volumeInfo']['title'];
            $capa = $livro['volumeInfo']['imageLinks']['thumbnail'];
            
            // Cada avaliação agora está envolta em um div com classe="avaliacao-item"
            echo "<div class='avaliacao-item'>";
            echo "<h1>$titulo</h1>"; // Título no topo
            if ($capa) {
                echo "<img src='" . $capa, ENT_QUOTES . "' alt='Capa do livro'>";
            }
            echo "<div class='buttons'>";
            echo "<a href=editar_avaliacao.php/?avaliacao=" . $avaliacoes['idAvaliacao'] . "><button id='botao'>Editar</button></a>";
            echo "</div>"; // Botões à direita
            echo "</div>"; // Fecha o div para cada avaliação
        }
    }
    ?>
</div>
</body>

</html>
