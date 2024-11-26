<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/login.css" />
    <link rel="icon" href="./assets/image 1.png" />
    <title>Login</title>
</head>

<body>
    <div id="login">
        <form action="validarLogin.php" method="POST">
            <img src="./assets/logo.png" alt="logo" />
            <?php if (isset($_SESSION['msg'])) { ?>
                <tr><td colspan="2" style="color: red;">
                <?php echo $_SESSION['msg']; ?></td></tr>
                <?php
                    session_destroy();
            } ?>
            <label for="">E-mail:</label>
            <input type="text" name="email"/>
            <label for="">Senha:</label>
            <input type="password" name="senha"/>
            <button type="submit">Entrar</button>
            <div id="extras">
                <p>Não possuí conta ainda?</p>
                <a href="cadastro.php">Clique aqui.</a>
            </div>
        </form>
    </div>
</body>

</html>