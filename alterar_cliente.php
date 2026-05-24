<?php
include("crud_clientes.php");
$cliente = selecionarClienteId($_POST["id"]);


?>
<meta charset="UTF-8">
<form name="dadosCliente" action="crud_clientes.php" method="POST">
    <table border="1">

        <tbody>
            <tr>
                <td>Nome </td>
                <td><input type="text" name="nome" value="<?= $cliente["nome"] ?>" size="35" /> </td>
            </tr>
            <tr>
                <td>CPF</td>
                <td><input type="text" name="cpf" value="<?= $cliente["cpf"] ?>" size="14" /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" value="<?= $cliente["email"] ?>" /></td>
            </tr>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login" value="<?= $cliente["login"] ?>" size="20" /></td>
            </tr>
            <tr>
                <td>Senha</td>
                <td><input type="password" name="senha" value="" size="20" placeholder="Caso deseje alterar a senha" /></td>
            </tr>
            <tr>
                <td><input type="hidden" name="acao" value="alterar" /></td>
                <td><input type="hidden" name="id" value="<?= $cliente["id"] ?>" /></td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="submit" value="Salvar alterações" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: bold; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>
        </tbody>
    </table>
</form>