<?php
include("crud_rotas.php");
$rota = selecionarRotaId($_POST["id"]);
?>
<meta charset="UTF-8">
<h3>📝 Alterar Rota</h3>
<form name="dadosRota" action="crud_rotas.php" method="POST">
    <table border="1">
        <tbody>
            <tr>
                <td>Nome da Rota</td>
                <td><input type="text" name="nome" value="<?= $rota["nome"] ?>" required /></td>
            </tr>
            <tr>
                <td>Cidade de Origem</td>
                <td>
                    <select name="cidade_origem_id" required>
                        <?php
                        $cidades = [1=>"São Paulo (SP)", 2=>"Rio de Janeiro (RJ)", 3=>"Curitiba (PR)", 4=>"Fortaleza (CE)", 5=>"Recife (PE)"];
                        foreach($cidades as $id => $nome) {
                            $sel = ($rota["cidade_origem_id"] == $id) ? "selected" : "";
                            echo "<option value='$id' $sel>$nome</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Cidade de Destino</td>
                <td>
                    <select name="cidade_destino_id" required>
                        <?php
                        foreach($cidades as $id => $nome) {
                            $sel = ($rota["cidade_destino_id"] == $id) ? "selected" : "";
                            echo "<option value='$id' $sel>$nome</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tempo de Viagem</td>
                <td><input type="text" name="tempo_viagem" value="<?= $rota["tempo_viagem"] ?>" required /></td>
            </tr>
            <tr>
                <td>Valor Base (R$)</td>
                <td><input type="number" step="0.01" name="valor_base" value="<?= $rota["valor_base"] ?>" required /></td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="alterar" />
                    <input type="hidden" name="id" value="<?= $rota["id"] ?>" />
                    <input type="submit" value="Salvar Alterações" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>            
        </tbody>
    </table>
</form>
