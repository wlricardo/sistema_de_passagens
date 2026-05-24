<?php
include ("crud_usuarios.php");
$usuario = selecionarUsuarioId($_POST["id"]);


?>
<meta charset="UTF-8">

<h3>📋 Atualizar dados do usuário </h3>

<form name="dadosUsuario" action="crud_usuarios.php" method="POST">
    <table border="1">
      
        <tbody>
            <tr>
                <td>Nome </td>
                <td><input type="text" name="nome" value="<?=$usuario["nome"]?>" size="35" /> </td>
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
               <td>Perfil</td>
               <td>
                   <select name="perfil_id" required>
                       <option value="">Selecione um perfil</option>
                       <option value="1" <?= $usuario["perfil_id"] == 1 ? "selected" : "" ?>>Gerente</option>
                       <option value="2" <?= $usuario["perfil_id"] == 2 ? "selected" : "" ?>>Consultor de Vendas</option>
                       <option value="3" <?= $usuario["perfil_id"] == 3 ? "selected" : "" ?>>Analista de TI</option>
                   </select>
               </td>
           </tr>
            <tr>
                <td><input type="hidden" name="acao" value="alterar" /></td>
                <td><input type="hidden" name="id" value="<?=$usuario["id"]?>" /></td>               
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