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
            header("Location: feed_teste.php");
            exit;
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