<?php
include("crud_clientes.php");

// 1. Verifica se o ID foi enviado via POST e não está vazio
if (!isset($_POST["cliente_id"]) || empty($_POST["cliente_id"])) {
    $origem = isset($_POST["origem"]) ? $_POST["origem"] : "vendas.php";
    header("Location: " . $origem . "?erro=id_nao_enviado");
    exit();
}

// 2. Busca o cliente no banco de dados
$cliente = selecionarClienteId($_POST["cliente_id"]);

// 3. Verifica se o cliente foi realmente encontrado
if (!$cliente) {
    $origem = isset($_POST["origem"]) ? $_POST["origem"] : "vendas.php";
    header("Location: " . $origem . "?erro=cliente_nao_encontrado");
    exit();
}

// Define a página de origem para o botão de cancelar
$origem = isset($_POST["origem"]) ? $_POST["origem"] : "vendas.php";
?>
<meta charset="UTF-8">
<h3>📋 Atualizar dados do cliente </h3>
<form name="dadosCliente" action="crud_clientes.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Nome </td>
                <td><input type="text" name="nome" value="<?= htmlspecialchars($cliente["nome"]) ?>" size="35" /> </td>
            </tr>
            <tr>
                <td>CPF</td>
                <td><input type="text" name="cpf" value="<?= htmlspecialchars($cliente["cpf"]) ?>" size="14" /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" value="<?= htmlspecialchars($cliente["email"]) ?>" /></td>
            </tr>
            <tr>
                <td>Login</td>
                <td><input type="text" name="login" value="<?= htmlspecialchars($cliente["login"]) ?>" size="20" /></td>
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
                    <!-- Botão de cancelar agora é dinâmico, voltando para a página correta -->
                    <a href="<?= $origem ?>" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>
        </tbody>
    </table>
</form>