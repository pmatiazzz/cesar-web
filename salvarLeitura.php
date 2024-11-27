<?php
include_once './database.php';
include_once './usuario.php';
session_start();

$idLivro = $_GET['idLivro'];
$situacao = $_POST['situacao'];

$idUsuario = $_SESSION['user']->getCod();

if (isset($_GET['idLivro'])) {
    $idLivro = $_GET['idLivro'];

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
        
        $consultaLivroExistente = mysqli_query($conexao, "SELECT idObra FROM obra WHERE idApi = '" . $idLivro . "';");
        $livroExistente = mysqli_fetch_assoc($consultaLivroExistente);
        if (empty($livroExistente['idObra'])){
            //salva obra no database
            $consultaObra = mysqli_query($conexao, "insert into obra(titulo, idApi) values ('" . $titulo . "','" . $idLivro . "')");
            //pega o id da ultima incersão
            $idObra = $conexao->insert_id;
            
            //salva leitura no database
            $consultaLeitura = mysqli_query($conexao, "insert into leitura(idObra, idUsuario, situacao) values ('" . $idObra . "','" . $idUsuario . "','" . $situacao . "')");
            $idLeitura = $conexao->insert_id;

            $consultaFeed = mysqli_query($conexao, "insert into feed(tipo, idLeitura) values ('leitura','" . $idLeitura . "')");
            
        } else {
            $idObra = $livroExistente['idObra'];
            //salva leitura no database
            $consultaLeitura = mysqli_query($conexao, "insert into leitura(idObra, idUsuario, situacao) values ('" . $idObra . "','" . $idUsuario . "','" . $situacao . "')");
            $idLeitura = $conexao->insert_id;

            $consultaFeed = mysqli_query($conexao, "insert into feed(tipo, idLeitura) values ('leitura','" . $idLeitura . "')");
            
        }
        
        //envia para tala de pesquisa
        header("Location: http://localhost/uniBooks/feed_teste.php");
        exit;
    } 
    
} else {
    echo "Erro. Livro não encontrado.";
}
?>
