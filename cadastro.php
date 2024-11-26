<?php
include './database.php';
include './usuario.php';
session_start();

if (isset($_POST['nome'])) {
    if (!empty($_POST['nome']) && !empty($_POST['email']) && !empty($_POST['senha']) && !empty($_POST['senha_confirm'])) {
        if ($_POST['senha'] == $_POST['senha_confirm']){
            $consulta_email = mysqli_query($conexao, "select email from usuario where email = '" . $_POST['email'] . "'");
            $validar_email = mysqli_fetch_assoc($consulta_email);
            if (isset($validar_email['email'])){
                $_SESSION['msg'] = 'email existente';
            } else {
                $consulta = mysqli_query($conexao,
                        "insert into usuario(nome, email, senha) values ('".$_POST['nome']."','".$_POST['email']."','".$_POST['senha']."')");
                session_destroy();
                header("Location: index.php");
                exit;
            }
        } else {
            $_SESSION['msg'] = 'senhas diferentes';
        }
    } else {
        $_SESSION['msg'] = 'preencha todos os campos';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./login.css" />
    <link rel="icon" href="./assets/image 1.png" />
    <title>Cadastro</title>
</head>

<body>
    <div id="login">
        <form action="cadastro.php" method="POST">
            <img src="./assets/logo.png" alt="logo" />
            <?php if (isset($_SESSION['msg'])) { ?>
                <tr><td colspan="2" style="color: red;">
                <?php echo $_SESSION['msg']; ?></td></tr>
                <?php
                    session_destroy();
            } ?>
            <label for="">Nome:</label>
            <input type="text" name="nome"/>
            <label for="">Email:</label>
            <input type="email" name="email"/>
            <label for="">Senha:</label>
            <input type="password" name="senha"/>
            <label for="">Confirme a senha:</label>
            <input type="password" name="senha_confirm"/>
            <button type="submit">Criar</button>
            <div id="extras">
                <p>Já possuí uma conta?</p>
                <a href="login.php">Clique aqui.</a>
            </div>
        </form>
    </div>
</body>

</html>