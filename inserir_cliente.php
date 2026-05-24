<?php



?>
<meta charset="UTF-8">
<form name="dadosCliente" action="crud_clientes.php" method="POST">
    <table border="1">

        <tbody>
            <tr>
                <td>Nome</td>
                <td><input type="text" name="nome" value="" /> </td>
            </tr>            
            <tr>
                <td>CPF</td>
                <td><input type="text" name="cpf" value="" /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" value=""></td>
            </tr>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login" value="" /></td>
            </tr>
            <tr>
                <td>Senha</td>
                <td><input type="password" name="senha" value="" /></td>
            </tr>
            <tr>
                <td><input type="hidden" name="acao" value="inserir" /></td>
                <td><input type="submit" value="enviar" name="enviar" /></td>
                <a href="analista.php" class="btn btn-secondary btn-sm">Voltar</a>
            </tr>
        </tbody>
    </table>
</form>