<?php



?>
<meta charset="UTF-8">

<h3>📋 Inserir cliente </h3>

<form name="dadosCliente" action="crud_clientes.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Nome</td>
                <td><input type="text" name="nome" required /> </td>
            </tr>            
            <tr>
                <td>CPF</td>
                <td><input type="text" name="cpf" required /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" required /></td>
            </tr>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login" required /></td>
            </tr>
            <tr>
                <td>Senha</td>
                <td><input type="password" name="senha" placeholder="******" required /></td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="inserir" />
                    <input type="submit" value="Adicionar" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 13px;">Cancelar</a>
                </td>
            </tr>            
        </tbody>
    </table>
</form>