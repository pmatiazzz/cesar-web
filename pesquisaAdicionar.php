<?php
include_once './database.php';
include_once './usuario.php';
session_start();
?>

<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles/principal.css" />
    <link rel="icon" href="./assets/image1.png" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Pesquisa de Livros</title>
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
    echo "<div id=leitura>";
    echo "<form action='pesquisaAdicionar.php' method='POST'>";
    echo "<input type='text' name='livro'/>";
    echo "<button type='submit'><ion-icon name='search-outline'></ion-icon></button>";
    echo "</form>";
    if (isset($_POST['livro'])) {
        // Endpoint da API do Google Books
        $query = $_POST['livro']; // Termo de pesquisa
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query);

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
        $data = json_decode($response, true);

        // Exibindo os resultados
        if (!empty($data['items'])) {
            foreach ($data['items'] as $livro) {
                $titulo = $livro['volumeInfo']['title'];
                $descricao = $livro['volumeInfo']['description'] ?? 'Descrição não disponivel';
                $autores = isset($livro['volumeInfo']['authors']) ? implode(', ', $livro['volumeInfo']['authors']) : 'Autor(es) não disponível';
                $capa = $livro['volumeInfo']['imageLinks']['thumbnail'] ?? './assets/placeholder.png';

                echo "<div class='book-item'>";

                // Exibindo a capa do livro
                if ($capa) {
                    echo "<img src='" . $capa . "' alt='Capa do livro: $titulo' style='width: 100px; height: auto; margin-bottom: 10px;'>";
                }

                // Exibindo os outros detalhes
                echo "<h2>" . $titulo . "</h2>";
                echo "<p>" . $descricao . "</p><br>";
                echo "<small><strong>Autor(es):</strong> " . $autores . "</small>";

                // Criando o link para mais detalhes (fazendo nova requisição com o ID)
                echo "<br><a href='adicionarLivro.php/?livro=" . $livro['id'] . "'><ion-icon name='add-circle-outline'></ion-icon>  Adicionar leitura</a>";

                echo "</div><hr>";
            }
        } else {
            echo "Nenhum livro encontrado.";
        }
    }
    echo "</div>";
    ?>
</body>

</html>