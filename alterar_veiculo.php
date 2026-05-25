<?php
include("crud_veiculos.php");
$veiculo = selecionarVeiculoId($_POST["id"]);
?>
<meta charset="UTF-8">
<h3>📝 Alterar dados do veículo</h3>
<form name="dadosVeiculo" action="crud_veiculos.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Marca</td>
                <td><input type="text" name="marca" value="<?= $veiculo["marca"] ?>" required /></td>
            </tr>
            <tr>
                <td>Modelo</td>
                <td><input type="text" name="modelo" value="<?= $veiculo["modelo"] ?>" required /></td>
            </tr>
            <tr>
                <td>Poltrona</td>
                <td><input type="text" name="poltrona" value="<?= $veiculo["poltrona"] ?>" required /></td>
            </tr>
            <tr>
                <td>Tipo</td>
                <td>
                    <select name="tipo" required>
                        <option value="Convencional" <?= $veiculo["tipo"] == 'Convencional' ? 'selected' : '' ?>>Convencional</option>
                        <option value="Executivo" <?= $veiculo["tipo"] == 'Executivo' ? 'selected' : '' ?>>Executivo</option>
                        <option value="Leito" <?= $veiculo["tipo"] == 'Leito' ? 'selected' : '' ?>>Leito</option>
                    </select>
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="alterar" />
                    <input type="hidden" name="id" value="<?= $veiculo["id"] ?>" />
                    <input type="submit" value="Salvar Alterações" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>            
        </tbody>
    </table>
</form>
