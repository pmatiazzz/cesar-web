<?php 
include_once './database.php';
include_once './usuario.php';
session_start(); 

if (isset($_GET['avaliacao'])){
    $consulta_avaliacao = mysqli_query($conexao, "UPDATE avaliacao SET nota = " . $_POST['nota'] . " WHERE idAvaliacao = " . $_GET['avaliacao'] . ";");
    $consultaComentario = mysqli_query($conexao, "insert into comentario(comentario, idAvaliacao, data) values ('" . $_POST['comentario'] . "','" . $_GET['avaliacao'] . "', CURDATE())");
    header("Location: perfil.php");
    exit;
} else {
    header("Location: http://localhost/uniBooks/perfil.php");
    exit;
}
?>