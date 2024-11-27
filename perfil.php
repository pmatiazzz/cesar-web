<?php
include './database.php';
include './usuario.php';
session_start();
?>

<!DOCTYPE html>
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
    Usuario : <?php echo $_SESSION['user']->getNome();?>
    
    <?php
        //leituras
        echo '<br>minhas leituras';
    
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
                
                echo "<h1>$titulo</h1>";
                if ($capa) {
                    echo "<img src='" . $capa, ENT_QUOTES . "' alt='Capa do livro' style='width: 150px; height: auto;'>";
                }
                
                echo "<a href=avaliarLivro.php/?livro=" . $idLivro . "><button>avaliar</button></a>";
                echo "<a href=editar_leitura.php/?leitura=" . $leituras['idLeitura'] . "><button>editar</button></a>";
            }
        }
        
        //avaliações
        echo '<br>minhas avaliações';
    
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
                
                echo "<h1>$titulo</h1>";
                if ($capa) {
                    echo "<img src='" . $capa, ENT_QUOTES . "' alt='Capa do livro' style='width: 150px; height: auto;'>";
                }
                
                echo "<a href=avaliarLivro.php/?livro=" . $idLivro . "><button>avaliar</button></a>";
            }
        }
    ?>
</body>

</html>