<?php 
include_once './database.php';
include_once './usuario.php';
session_start();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="./../styles/principal.css" />
        <link rel="icon" href="./../assets/image1.png" />
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <meta charset="UTF-8">
        <title>Editando...</title>
    </head>
    <body>
        <div class="lateral">
        <img src="./../assets/logo.png" alt="logo" />
        <table>
            <ul>
                <li>
                    <div class="item" id="principal">
                        <a href="./../feed_teste.php">
                            <ion-icon name="home-outline"></ion-icon>Principal</a>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <a href="./../pesquisaAdicionar.php">
                            <ion-icon name="bookmark-outline"></ion-icon>Leituras</a>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <a href="./../pesquisaAvaliar.php">
                            <ion-icon name="star-outline"></ion-icon>Avaliações</a>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <a href="./../perfil.php">
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
        echo "<div id='edicao'>";
        if (isset($_GET['avaliacao'])) {
            $idAvaliacao = $_GET['avaliacao'];
            $consulta_avaliacao = mysqli_query($conexao, "SELECT idObra FROM avaliacao WHERE idAvaliacao = " . $idAvaliacao . ";");
            $avaliacao = mysqli_fetch_assoc($consulta_avaliacao);
            $consulta_obra = mysqli_query($conexao, "SELECT idApi FROM obra WHERE idObra = " . $avaliacao['idObra'] . ";");
            $obra = mysqli_fetch_assoc($consulta_obra);
            
            $idLivro = $obra['idApi'];

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
        
        <form action="/uniBooks/editandoAvaliacao.php/?avaliacao=<?php echo $_GET['avaliacao']?> " method="POST">
            <div class="avaliacao">
            <label>Nota:</label>
            <select name="nota">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <textarea name="comentario" id="" rows="4" cols="50" placeholder="Escreva seu comentário aqui..."></textarea>
            <button type="submit">Avaliar</button><br>
            </div>
        </form>
        <?php
            $consulta_comentario = mysqli_query($conexao, "SELECT * FROM comentario WHERE idAvaliacao = " . $_GET['avaliacao'] . " ORDER BY idComentario DESC;");
                while ($comentario = mysqli_fetch_assoc($consulta_comentario)){
                    echo "<p>" . '<strong>Comentário:</strong> ' . $comentario['comentario'] ."</p>";
                    echo '<a href="http://localhost/uniBooks/excluir_comentario.php/?comentario=' . $comentario['idComentario'] . '"><button id="excluir"><strong><ion-icon name="trash-outline"></ion-icon></strong></button></a>';
                }
        ?>
        </div>
    </body>
</html>