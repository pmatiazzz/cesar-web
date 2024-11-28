<page backcolor="#FEFEFE" backtop="0" backbottom="30mm" footer="date;time;page" style="font-size: 12pt">
    <bookmark title="Lettre" level="0" ></bookmark>
    <table cellspacing="0" style="width: 100%; text-align: center; font-size: 14px">
        <tr>
            <td style="width: 25%;">
            </td>
            <td style="width: 50%; color: #111199; font-size: 16pt; font-weight: bold;">
                <br>
                Livros Catalogados
            </td>
            <td style="width: 25%;"></td>
        </tr>
    </table>
    <br>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 12pt;">
        <thead>
            <tr style="font-size: 14pt; font-weight: bold; border-spacing: 100pt;" >
                <td style="width:20%;"></td>
                <td style="width:20%;">Usuario</td>
                <td style="width:20%">Livro</td> 
                <td style="width:20%">Comentario</td> 
                <td style="width:20%">Data</td> 
            </tr>
        </thead>
        <br>
        <br>
        <tbody>
        <?php
        include './database.php';
        
        $sql = "select c.idComentario, u.nome as usuario, o.titulo as livro, c.comentario, c.data 
                from comentario c
                join avaliacao a on c.idavaliacao = a.idavaliacao
                join usuario u on a.idusuario = u.idusuario
                join obra o on a.idobra = o.idobra
                ";
        
        $result = $conexao->query($sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $conexao->close();
        
        foreach ($rows as $linha) {
            ?>
            <tr>
                <td style="width:25%;">
            <barcode dimension="1D" type="S25" value="<?php echo $linha['idComentario'] ?>" 
                    label="label" style="width:25mm; height:6mm; color: #0000FF; font-size: 4mm" />
            </td>
            <td style="width:15%"><?php echo $linha['usuario'] ?></td>
            <td style="width:15%"><?php echo $linha['livro'] ?></td> 
            <td style="width:15%"><?php echo $linha['comentario'] ?></td> 
            <td style="width:15%"><?php echo $linha['data'] ?></td> 
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
    <br>
</page>
