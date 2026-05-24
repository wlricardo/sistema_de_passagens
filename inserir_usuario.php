<meta charset="UTF-8">

<h3>📋 Inserir usuário </h3>


<form name="dadosUsuario" action="crud_usuarios.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Nome</td>
                <td><input type="text" name="nome" required /> </td>
            </tr>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login" required /></td>
            </tr>
            <tr>
                <td>Senha</td>
                <td><input type="password" name="senha" placeholder="******" required /></td>
            </tr>
            <tr>
                <td>Perfil</td>
                <td>
                    <select name="perfil_id" required>
                        <option value="">Selecione um perfil</option>
                        <option value="1">Gerente</option>
                        <option value="2">Consultor de Vendas</option>
                        <option value="3">Analista de TI</option>
                    </select>
                </td>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="inserir" />
                    <input type="submit" value="Adicionar" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: bold; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>            
        </tbody>
    </table>
</form>