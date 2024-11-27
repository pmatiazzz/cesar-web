<?php 
include_once './database.php';
include_once './usuario.php';
session_start();

if (isset($_GET['leitura'])){
    $consulta_feed = mysqli_query($conexao, "DELETE FROM feed WHERE idLeitura = " . $_GET['leitura'] . ";");
    $consulta_leitura = mysqli_query($conexao, "DELETE FROM leitura WHERE idLeitura = " . $_GET['leitura'] . ";");
    header("Location: perfil.php");
    exit;
} else {
    header("Location: http://localhost/uniBooks/perfil.php");
    exit;
}
?>