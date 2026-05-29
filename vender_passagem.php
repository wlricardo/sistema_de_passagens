<?php
require_once 'login.php';
include_once 'crud_clientes.php';
include_once 'crud_viagens.php';

// Verifica se veio uma viagem selecionada
if (!isset($_POST['viagem_id'])) {
    header("Location: vendas.php");
    exit();
}

$viagem_id = $_POST['viagem_id'];

// Busca os dados da viagem selecionada 
$viagens = listarViagens();
$viagem_selecionada = null;
foreach ($viagens as $v) {
    if ($v['id'] == $viagem_id) {
        $viagem_selecionada = $v;
        break;
    }
}

// Busca a lista de clientes para o menu suspenso
$clientes = listarClientes();
?>
<meta charset="UTF-8">
<h3>📋 Concluir Venda de Passagem (Nova Reserva)</h3>

<form name="dadosReserva" action="crud_reservas.php" method="POST">
    <table border="1" cellpadding="5" cellspacing="0">
        <tbody>
            <!-- DADOS FIXOS DA VIAGEM SELECIONADA -->
            <tr>
                <td><strong>Linha / Rota:</strong></td>
                <td><?= htmlspecialchars($viagem_selecionada['nome_rota']) ?></td>
            </tr>
            <tr>
                <td><strong>Veículo:</strong></td>
                <td><?= htmlspecialchars($viagem_selecionada['modelo_veiculo'] . " (" . $viagem_selecionada['tipo_veiculo'] . ")") ?></td>
            </tr>
            <tr>
                <td><strong>Data da Viagem:</strong></td>
                <td><?= date('d/m/Y', strtotime($viagem_selecionada['data'])) ?></td>
            </tr>
            <tr>
                <td><strong>Valor Base da Viagem:</strong></td>
                <td>
                    R$ <?= number_format($viagem_selecionada['valor'], 2, ',', '.') ?>
                    <!-- Input oculto com o valor original para o JavaScript usar no cálculo -->
                    <input type="hidden" id="valor_base" value="<?= $viagem_selecionada['valor'] ?>" />
                </td>
            </tr>

            <!-- SELEÇÃO DO CLIENTE COMPRADOR -->
            <tr>
                <td><label for="cliente_id">Selecionar Cliente</label></td>
                <td>
                    <select name="cliente_id" id="cliente_id" required>
                        <option value="">-- Escolha o Cliente --</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>">
                                <?= htmlspecialchars($cliente['nome']) ?> (CPF: <?= htmlspecialchars($cliente['cpf']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <!-- FORMA DE PAGAMENTO -->
            <tr>
                <td><label for="forma_pagamento">Forma de Pagamento</label></td>
                <td>
                    <select name="forma_pagamento" id="forma_pagamento" onchange="calcularCondicoesPagamento()" required>
                        <option value="">-- Selecione --</option>
                        <option value="pix">PIX (10% de desconto)</option>
                        <option value="dinheiro">Dinheiro (10% de desconto)</option>
                        <option value="cartao">Cartão de Crédito</option>
                    </select>
                </td>
            </tr>

            <!-- OPÇÕES DINÂMICAS DE PARCELAMENTO (Aparece apenas se for Cartão) -->
            <tr id="linha_parcelas" style="display: none;">
                <td><label for="parcelas">Parcelamento</label></td>
                <td>
                    <select name="parcelas" id="parcelas" onchange="calcularCondicoesPagamento()">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?>x</option>
                        <?php endfor; ?> <!-- CORREÇÃO AQUI -->
                    </select>
                </td>
            </tr>

            <!-- EXIBIÇÃO DO VALOR FINAL -->
            <tr>
                <td><strong>Total a Pagar:</strong></td>
                <td>
                    <span id="texto_valor_final" style="font-size: 18px; color: green; font-weight: bold;">
                        R$ <?= number_format($viagem_selecionada['valor'], 2, ',', '.') ?>
                    </span>
                    <div id="texto_detalhe_parcela" style="font-size: 12px; color: #555; margin-top: 5px;"></div>
                </td>
            </tr>

            <!-- BOTÕES DE ENVIO -->
            <tr align="center">
                <td colspan="2">
                    <!-- Inputs ocultos mapeando estritamente os atributos da classe UML Reserva -->
                    <input type="hidden" name="viagem_id" value="<?= $viagem_id ?>" />
                    <input type="hidden" name="usuario_id" value="<?= $_SESSION['id_usuario'] ?>" />
                    <input type="hidden" name="valor_final" id="valor_final_input" value="<?= $viagem_selecionada['valor'] ?>" />
                    <input type="hidden" name="acao" value="inserir" />

                    <input type="submit" value="Finalizar e Emitir Bilhete" />
                    <a href="vendas.php" style="margin-left: 10px; text-decoration: none; padding: 2px 5px; background: #ddd; color: black; border: 1px solid #aaa; font-size: 14px;">Cancelar</a>
                </td>
            </tr>
        </tbody>
    </table>
</form>

<script>
    function calcularCondicoesPagamento() {
        const valorBase = parseFloat(document.getElementById('valor_base').value);
        const formaPgto = document.getElementById('forma_pagamento').value;
        const linhaParcelas = document.getElementById('linha_parcelas');
        const numParcelas = parseInt(document.getElementById('parcelas').value) || 1;

        const textoValor = document.getElementById('texto_valor_final');
        const textoDetalhe = document.getElementById('texto_detalhe_parcela');
        const inputValorFinal = document.getElementById('valor_final_input');

        let valorCalculado = valorBase;
        textoDetalhe.innerHTML = "";

        if (formaPgto === "pix" || formaPgto === "dinheiro") {
            linhaParcelas.style.display = "none";
            // Aplica o desconto de 10%
            valorCalculado = valorBase * 0.9;
            textoDetalhe.innerHTML = "⚡ Desconto de 10% aplicado para pagamento à vista!";
        } else if (formaPgto === "cartao") {
            linhaParcelas.style.display = "table-row";
            valorCalculado = valorBase;

            // Calcula o valor de cada parcela
            const valorParcela = valorCalculado / numParcelas;
            textoDetalhe.innerHTML = "💳 " + numParcelas + " parcelas de R$ " + valorParcela.toFixed(2).replace('.', ',');
        } else {
            linhaParcelas.style.display = "none";
        }

        // Atualiza a interface visual e o input que será enviado ao PHP
        textoValor.innerHTML = "R$ " + valorCalculado.toFixed(2).replace('.', ',');
        inputValorFinal.value = valorCalculado.toFixed(2);
    }
</script>