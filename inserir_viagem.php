<?php
require_once 'login.php';
include_once 'crud_rotas.php';
include_once 'crud_veiculos.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('location:index.php');
    exit();
}

$id_usuario = mysqli_real_escape_string($connect, $_SESSION['id_usuario']);
$sql_perfil = "SELECT perfil_id FROM usuario WHERE id = '$id_usuario'";
$res_perfil = mysqli_query($connect, $sql_perfil);
$user_dados = mysqli_fetch_array($res_perfil);

if (!$user_dados || $user_dados['perfil_id'] != 3) {
    header('location:analista.php?erro=acesso_negado');
    exit();
}

$rotas_disponiveis = listarRotas();
$veiculos_disponiveis = listarVeiculos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Programar Nova Viagem - Administração</title>
</head>

<body>

    <h3>📋 Programar Nova Viagem (Cálculo Automático de Tarifa)</h3>

    <form name="dadosViagem" action="crud_viagens.php" method="POST">
        <table border="1" cellpadding="5" cellspacing="0">
            <tbody>
                <!-- SELEÇÃO DE ROTA -->
                <tr>
                    <td>Selecione a Rota / Linha</td>
                    <td>
                        <select name="rota_id" id="rota_id" onchange="calcularTarifaAutomatica()" required>
                            <option value="">-- Escolha uma Rota --</option>
                            <?php foreach ($rotas_disponiveis as $rota): ?>
                                <option value="<?= $rota['id'] ?>"><?= htmlspecialchars($rota['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <!-- SELEÇÃO DE VEÍCULO -->
                <tr>
                    <td>Selecione o Ônibus (Veículo)</td>
                    <td>
                        <select name="veiculo_id" id="veiculo_id" onchange="calcularTarifaAutomatica()" required>
                            <option value="">-- Escolha um Veículo --</option>
                            <?php foreach ($veiculos_disponiveis as $veiculo): ?>
                                <option value="<?= $veiculo['id'] ?>">
                                    <?= htmlspecialchars($veiculo['marca'] . " " . $veiculo['modelo'] . " (" . $veiculo['tipo'] . ")") ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Data da Viagem</td>
                    <td><input type="date" name="data" min="<?= date('Y-m-d') ?>" required /></td>
                </tr>

                <!-- VALOR CALCULADO AUTOMATICAMENTE -->
                <tr>
                    <td>Valor da Passagem Calculado (R$)</td>
                    <td>
                        <!-- Readonly impede a alteração manual e mantém o padrão calculado pelo sistema -->
                        <input type="number" step="0.01" name="valor" id="valor_passagem" placeholder="Selecione rota e veículo" readonly style="background-color: #eee; font-weight: bold;" required />
                    </td>
                </tr>

                <tr align="center">
                    <td colspan="2">
                        <input type="hidden" name="acao" value="inserir" />
                        <input type="submit" value="Programar Viagem" name="enviar" />
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

            // Se um dos dois campos ainda não foi selecionado, limpa o valor final
            if (rotaId === "" || veiculoId === "") {
                inputValor.value = "";
                return;
            }

            // Consulta silenciosa enviando ambos os parâmetros para o PHP calcular
            fetch('calcular_preco.php?rota_id=' + rotaId + '&veiculo_id=' + veiculoId)
                .then(function(resposta) {
                    return resposta.text();
                })
                .then(function(precoCalculado) {
                    inputValor.value = precoCalculado.trim();
                })
                .catch(function(erro) {
                    console.error('Erro ao calcular tarifa:', erro);
                    inputValor.value = "0.00";
                });
        }
    </script>

</body>

</html>