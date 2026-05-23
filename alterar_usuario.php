<?php
include ("crud_usuarios.php");
$usuario = SelecionarUsuarioId($_POST["id"]);


?>
<meta charset="UTF-8">
<form name="dadosUsuario" action="conectado.php" method="POST">
    <table border="1">
      
        <tbody>
            <tr>
                <td>Nome </td>
                <td><input type="text" name="nome" value="<?=$usuario["nome"]?>" size="35" /> </td>
            </tr>            
            <tr>
                <td>CPF</td>
                <td><input type="text" name="cpf" value="<?=$usuario["cpf"]?>" size="14" /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" value="<?=$usuario["email"]?>" /></td>
            </tr>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login" value="<?=$usuario["login"]?>" size="20" /></td>
            </tr>
            <tr>
                <td>Senha</td>
                <td><input type="password" name="senha" value="<?=$usuario["senha"]?>" size="20" /></td>
            </tr>            
            <tr>
                <td><input type="hidden" name="acao" value="alterar" /></td>
                <td><input type="hidden" name="id" value="<?=$usuario["id"]?>" /></td>               
            </tr>
             <td><input type="submit" value="Enviar" name="enviar" /></td>
        </tbody>
    </table>
</form>