<?php



?>
<meta charset="UTF-8">
<form name="dadosCliente" action="crud_clientes.php" method="POST">
    <table border="1">

        <tbody>
            <thead>
                <tr>
                    <th colspan="2">Adicionar Cliente</th>
                </tr>
            </thead>
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
                <td>
                    <input type="hidden" name="acao" value="inserir" />
                    <input type="submit" value="Enviar" name="enviar" />
                    <input type="submit" value="Cancelar" name="cancelar" />
                </td>
            </tr>
        </tbody>
    </table>
</form>