<?php
include ("crud_clientes.php");
$cliente = SelecionarClienteId($_POST["id"]);


?>
<meta charset="UTF-8">
<form name="dadosCliente" action="conectado.php" method="POST">
    <table border="1">
      
        <tbody>
            <tr>
                <td>Nome </td>
                <td><input type="text" name="nome" value="<?=$cliente["nome"]?>" size="35" /> </td>
            </tr>            
            <tr>
                <td>CPF</td>
                <td><input type="text" name="cpf" value="<?=$cliente["cpf"]?>" size="14" /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" value="<?=$cliente["email"]?>" /></td>
            </tr>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login" value="<?=$cliente["login"]?>" size="20" /></td>
            </tr>
            <tr>
                <td>Senha</td>
                <td><input type="password" name="senha" value="<?=$cliente["senha"]?>" size="20" /></td>
            </tr>            
            <tr>
                <td><input type="hidden" name="acao" value="alterar" /></td>
                <td><input type="hidden" name="id" value="<?=$cliente["id"]?>" /></td>               
            </tr>
             <td><input type="submit" value="Enviar" name="enviar" /></td>
        </tbody>
    </table>
</form>