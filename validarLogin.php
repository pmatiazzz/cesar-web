<?php
include_once './database.php';
include_once './usuario.php';
session_start();

if (isset($_POST['email'])) {
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $consulta = mysqli_query($conexao, "select idUsuario, nome, email, senha from usuario where email = '" . $email . "'");
        $dados = mysqli_fetch_assoc($consulta);
        $user = null;
        if ($dados != null) {
            $user = new Usuario($dados["idUsuario"], $dados["nome"], $dados["email"], $dados["senha"]);
        }

        if ($user != null && $user->validaUsuarioSenha($email, $senha)) {
            $_SESSION['user'] = $user;
        } else {
            $_SESSION['msg'] = "email ou senha incorretos";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['msg'] = "insira o email";
        header("Location: login.php");
        exit;
    }
}
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
        <?php echo 'ola ' . $dados['nome'] . '! <br>'; ?>
        <a href="logout.php"> Sair </a>
    </body>
</html>