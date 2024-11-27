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
        <?php
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
                $descricao = $livro['volumeInfo']['description'];
                $editora = $livro['volumeInfo']['publisher'];
                $dataPublicacao = $livro['volumeInfo']['publishedDate'];
                $capa = $livro['volumeInfo']['imageLinks']['thumbnail'];

                // Exibindo os detalhes do livro
                echo "<h1>$titulo</h1>";
                echo "<p><strong>Autor(es):</strong> $autores</p>";
                echo "<p><strong>Descrição:</strong> $descricao</p>";
                echo "<p><strong>Editora:</strong> $editora</p>";
                echo "<p><strong>Data de publicação:</strong> $dataPublicacao</p>";

                // Exibindo a capa do livro
                if ($capa) {
                    echo "<img src='" . $capa, ENT_QUOTES . "' alt='Capa do livro' style='width: 150px; height: auto;'>";
                }
            } else {
                echo "Detalhes não encontrados para este livro.";
            }
        } else {
            echo "Erro. Livro não encontrado.";
        }
        ?>
        
        <form action="/uniBooks/salvarLeitura.php/?idLivro=<?php echo $_GET['livro']?> " method="POST">
            <label>situação:</label>
            <select name="situacao">
                <option value="esta lendo">Lendo</option>
                <option value="leu">Lido</option>
            </select>
            <button type="submit">salvar</button>
        </form>
    </body>
</html>