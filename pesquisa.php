<?php
include_once './database.php';
include_once './usuario.php';
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
        <form action="resultado.php" method="POST">
            <input type="text" name="livro"/>
            <button type="submit">pesquisar</button>
        </form>
    </body>
</html>