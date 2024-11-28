<?php 
include_once './database.php';
include_once './usuario.php';
session_start(); 

if (isset($_GET['comentario'])){
    $consulta_avaliacao = mysqli_query($conexao, "SELECT idAvaliacao FROM comentario WHERE idComentario = " . $_GET['comentario'] . ";");
    $avaliacao = mysqli_fetch_assoc($consulta_avaliacao);
    $consulta_comentario = mysqli_query($conexao, "DELETE FROM comentario WHERE idComentario = " . $_GET['comentario'] . ";");
    header("Location: http://localhost/uniBooks/editar_avaliacao.php/?avaliacao=" . $avaliacao['idAvaliacao']);
    exit;
} else {
    header("Location: http://localhost/uniBooks/feed_teste");
    exit;
}
?>