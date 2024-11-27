<?php
include './database.php';
include './usuario.php';
session_start();
?>

<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <a href="perfil.php"><?php echo $_SESSION['user']->getNome();?></a>
        <?php
            $consulta_feed = mysqli_query($conexao, "SELECT * FROM feed ORDER BY idFeed DESC;");
            $consulta_avaliacao = mysqli_query($conexao, "SELECT * FROM avaliacao;");
            while ($feed = mysqli_fetch_assoc($consulta_feed)) {
                
                if ($feed['tipo'] == 'leitura') {
                    
                    $consulta_leitura = mysqli_query($conexao, "SELECT * FROM leitura WHERE idLeitura = " . $feed['idLeitura'] . ";");
                    $leitura = mysqli_fetch_assoc($consulta_leitura);
                    $consulta_usuario = mysqli_query($conexao, "SELECT * FROM usuario WHERE idUsuario = " . $leitura['idUsuario'] . ";");
                    $usuario = mysqli_fetch_assoc($consulta_usuario);
                    $consulta_obra = mysqli_query($conexao, "SELECT * FROM obra WHERE idObra = " . $leitura['idObra'] . ";");
                    $obra = mysqli_fetch_assoc($consulta_obra);
                    
                    echo "<div class='feed-item'>";
                    echo "<h2>" . $feed['idFeed'] . "</h2>";
                    echo "<p>" . $usuario['nome'] . ' ' . $leitura['situacao'] . ' o livro ' . $obra['titulo'] . "</p>";
                    echo "</div>";
                }
                
                if ($feed['tipo'] == 'avaliacao') {
                    $consulta_avaliacao = mysqli_query($conexao, "SELECT * FROM avaliacao WHERE idAvaliacao = " . $feed['idAvaliacao'] . ";");
                    $avaliacao = mysqli_fetch_assoc($consulta_avaliacao);
                    $consulta_usuario = mysqli_query($conexao, "SELECT * FROM usuario WHERE idUsuario = " . $avaliacao['idUsuario'] . ";");
                    $usuario = mysqli_fetch_assoc($consulta_usuario);
                    $consulta_obra = mysqli_query($conexao, "SELECT * FROM obra WHERE idObra = " . $avaliacao['idObra'] . ";");
                    $obra = mysqli_fetch_assoc($consulta_obra);
                    
                    echo "<div class='feed-item'>";
                    echo "<h2>" . $feed['idFeed'] . "</h2>";
                    echo "<p>" . $usuario['nome'] . ' avaliou o livro ' . $obra['titulo'] . ' com ' . $avaliacao['nota'] . ' estrelas.' ."</p>";
                    
                    $consulta_comentario = mysqli_query($conexao, "SELECT * FROM comentario WHERE idAvaliacao = " . $feed['idAvaliacao'] . " ORDER BY idComentario DESC;");
                    while ($comentario = mysqli_fetch_assoc($consulta_comentario)){
                        echo "<p>" . 'comentario: ' . $comentario['comentario'] ."</p>";
                    }
                    echo "</div>";
                }
            }
        ?>
        <a href="pesquisaAdicionar.php">adicionar</a>
        <a href="pesquisaAvaliar.php">avaliar</a>
    </body>
</html>