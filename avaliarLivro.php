<?php
include_once './database.php';
include_once './usuario.php';
session_start();
?>

<html>

<head>
    <link rel="stylesheet" href="./../styles/principal.css" />
    <link rel="icon" href="./../assets/image1.png" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Pesquisa Avaliação</title>
</head>

<body>
    <div class="lateral">
        <img src="./../assets/logo.png" alt="logo" />
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
    echo "<div id='detalhesLivro'>";
    if (isset($_GET['livro'])) {
        $idLivro = $_GET['livro'];

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

        // Exibindo os detalhes do livro
        if (!empty($livro['volumeInfo'])) {
            $titulo = $livro['volumeInfo']['title'];
            $autores = implode(', ', $livro['volumeInfo']['authors']);
            $descricao = $livro['volumeInfo']['description'] ?? 'Descrição não disponivel';
            $editora = $livro['volumeInfo']['publisher'];
            $dataPublicacao = $livro['volumeInfo']['publishedDate'];
            $capa = $livro['volumeInfo']['imageLinks']['thumbnail'] ?? './../assets/placeholder.png';


        } else {
            echo "Detalhes não encontrados para este livro.";
        }
    } else {
        echo "Erro. Livro não encontrado.";
    }
    ?>
    <div id="detalhesLivro">
        <div class="livro-detalhes">
            <?php if ($capa): ?>
                <img src="<?php echo htmlspecialchars($capa, ENT_QUOTES); ?>" alt="Capa do livro" />
            <?php endif; ?>
            <div class="texto-detalhes">
                <h1><?php echo htmlspecialchars($titulo, ENT_QUOTES); ?></h1>
                <p><strong>Autor(es):</strong> <?php echo htmlspecialchars($autores, ENT_QUOTES); ?></p>
                <p><strong>Editora:</strong> <?php echo htmlspecialchars($editora, ENT_QUOTES); ?></p>
                <p><strong>Data de publicação:</strong> <?php echo htmlspecialchars($dataPublicacao, ENT_QUOTES); ?></p>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($descricao, ENT_QUOTES); ?></p>
            </div>
        </div>

        <form action="/uniBooks/salvarAvaliacao.php/?idLivro=<?php echo $_GET['livro']; ?>" method="POST">
            <div class="avaliacao">
                <label>Nota:</label>
                <select name="nota">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <textarea name="comentario" rows="4" cols="50" placeholder="Escreva seu comentário aqui..."></textarea>
                <button type="submit">Avaliar</button>
            </div>
        </form>
    </div>
    </div>
</body>

</html>