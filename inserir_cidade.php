<?php
require_once 'conexao.php';

function listarEstadosFormulario()
{
    $banco = abrirBanco();
    $sql = "SELECT id, nome FROM estado ORDER BY nome ASC";
    $resultado = $banco->query($sql);
    $estados = [];
    while ($row = mysqli_fetch_assoc($resultado)) {
        $estados[] = $row;
    }
    $banco->close();
    return $estados;
}

$estados_cadastrados = listarEstadosFormulario();
?>
<meta charset="UTF-8">
<h3>📋 Cadastrar Cidade</h3>

<form name="dadosCidade" action="crud_cidades.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Nome da Cidade</td>
                <td><input type="text" name="nome" placeholder="Ex: Campinas" required /></td>
            </tr>
            <tr>
                <td>Estado</td>
                <td>
                    <select name="estado_id" required>
                        <option value="">-- Selecione o Estado --</option>
                        <?php foreach ($estados_cadastrados as $estado): ?>
                            <option value="<?= $estado['id'] ?>"><?= htmlspecialchars($estado['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="inserir" />
                    <input type="submit" value="Adicionar Cidade" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>
        </tbody>
    </table>
</form>