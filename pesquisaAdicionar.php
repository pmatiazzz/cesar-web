<?php
include_once './database.php';
include_once './usuario.php';
session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="pesquisaAdicionar.php" method="POST">
            <input type="text" name="livro"/>
            <button type="submit">pesquisar</button>
        </form>
        
        <?php
            if (isset($_POST['livro'])){
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
                        $descricao = isset($livro['volumeInfo']['description']) ? $livro['volumeInfo']['description'] : 'Descrição não disponivel';
                        $autores = isset($livro['volumeInfo']['authors']) ? implode(', ', $livro['volumeInfo']['authors']) : 'Autor(es) não disponível';
                        $capa = $livro['volumeInfo']['imageLinks']['thumbnail']; // URL da imagem de capa

                        echo "<div class='book-item'>";

                        // Exibindo a capa do livro
                        if ($capa) {
                            echo "<img src='" . $capa . "' alt='Capa do livro: $titulo' style='width: 100px; height: auto; margin-bottom: 10px;'>";
                        }

                        // Exibindo os outros detalhes
                        echo "<h2>" . $titulo . "</h2>";
                        echo "<p>" . $descricao . "</p>";
                        echo "<small>Autor(es): " . $autores . "</small>";

                        // Criando o link para mais detalhes (fazendo nova requisição com o ID)
                        echo "<br><a href='adicionarLivro.php/?livro=" . $livro['id'] . "'>Ver mais detalhes</a>";

                        echo "</div><hr>";
                    }
                } else {
                    echo "Nenhum livro encontrado.";
                }
            }
        ?>
    </body>
</html>