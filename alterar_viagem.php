<?php
require_once 'login.php';
include_once 'crud_viagens.php';
include_once 'crud_rotas.php';
include_once 'crud_veiculos.php';

// Busca os dados da viagem atual selecionada por ID
$viagem = selecionarViagemId($_POST["id"]);

$rotas = listarRotas();
$veiculos = listarVeiculos();
?>
<meta charset="UTF-8">
<h3>📝 Alterar Viagem Programada</h3>

<form name="dadosViagem" action="crud_viagens.php" method="POST">
    <table border="1" cellpadding="5" cellspacing="0">
        <tbody>
            <tr>
                <td>Selecione a Rota</td>
                <td>
                    <select name="rota_id" id="rota_id" onchange="calcularTarifaAutomatica()" required>
                        <?php foreach ($rotas as $rota): ?>
                            <option value="<?= $rota['id'] ?>" <?= $viagem['rota_id'] == $rota['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($rota['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Selecione o Veículo</td>
                <td>
                    <select name="veiculo_id" id="veiculo_id" onchange="calcularTarifaAutomatica()" required>
                        <?php foreach ($veiculos as $veiculo): ?>
                            <option value="<?= $veiculo['id'] ?>" <?= $viagem['veiculo_id'] == $veiculo['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($veiculo['marca'] . " " . $veiculo['modelo'] . " (" . $veiculo['tipo'] . ")") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Data da Viagem</td>
                <td><input type="date" name="data" value="<?= $viagem['data'] ?>" min="<?= date('Y-m-d') ?>" required /></td>
            </tr>
            <tr>
                <td>Valor da Passagem (R$)</td>
                <td>
                    <input type="number" step="0.01" name="valor" id="valor_passagem" value="<?= $viagem['valor'] ?>" readonly style="background-color: #eee; font-weight: bold;" required />
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="acao" value="alterar" />
                    <input type="hidden" name="id" value="<?= $viagem["id"] ?>" />
                    <input type="submit" value="Salvar Alterações" name="enviar" />
                    <a href="analista.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<script>
    function calcularTarifaAutomatica() {
        const rotaId = document.getElementById('rota_id').value;
        const veiculoId = document.getElementById('veiculo_id').value;
        const inputValor = document.getElementById('valor_passagem');

        if (rotaId === "" || veiculoId === "") {
            inputValor.value = "";
            return;
        }

        fetch('calcular_preco.php?rota_id=' + rotaId + '&veiculo_id=' + veiculoId)
            .then(response => response.text())
            .then(precoCalculado => {
                inputValor.value = precoCalculado.trim();
            })
            .catch(erro => {
                console.error('Erro:', erro);
            });
    }
</script>