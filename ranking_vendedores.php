<?php
require_once 'login.php';
require_once 'conexao.php';

// Proteção de acesso para o Gerente
if (!isset($_SESSION['logado']) || $_SESSION['perfil_id'] != 1) {
    header('Location: index.php');
    exit();
}

$banco = abrirBanco();

// Consulta que agrupa o faturamento por Ano e Mês
$sql_mensal = "SELECT DATE_FORMAT(data, '%Y-%m') AS mes, COUNT(*) AS total_bilhetes, SUM(valor_pago) AS faturamento 
               FROM reserva GROUP BY mes ORDER BY mes DESC";
$res_mensal = $banco->query($sql_mensal);

// Consulta que traz o resumo por forma de pagamento
$sql_meios = "SELECT forma_pagamento, COUNT(*) AS qtd, SUM(valor_pago) AS total 
              FROM reserva GROUP BY forma_pagamento";
$res_meios = $banco->query($sql_meios);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro de Faturamento</title>
    <style>
        body {
            font-family: monospace;
            margin: 30px;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table th {
            background: #f2f2f2;
        }

        .btn {
            padding: 5px 10px;
            background: #ddd;
            text-decoration: none;
            color: #000;
            border: 1px solid #000;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <a href="gerente.php" class="btn">⬅ Voltar</a>
        <button onclick="window.print()" class="btn">🖨 Imprimir</button>
    </div>

    <h2>VÁ COM DEUS RODOVIÁRIO - RELATÓRIO FINANCEIRO</h2>
    <p>Gerado em: <?= date('d/m/Y H:i') ?></p>
    <hr>

    <h3>📈 Faturamento Mensal Histórico</h3>
    <table>
        <thead>
            <tr>
                <th>Ano / Mês</th>
                <th>Bilhetes Emitidos</th>
                <th>Faturamento Bruto</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $res_mensal->fetch_assoc()): ?>
                <tr>
                    <td><?= date('m/Y', strtotime($row['mes'] . "-01")) ?></td>
                    <td><?= $row['total_bilhetes'] ?></td>
                    <td>R$ <?= number_format($row['faturamento'], 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>💳 Resumo por Meio de Pagamento</h3>
    <table>
        <thead>
            <tr>
                <th>Forma de Pagamento</th>
                <th>Quantidade de Vendas</th>
                <th>Total Arrecadado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $res_meios->fetch_assoc()): ?>
                <tr>
                    <td><?= strtoupper($row['forma_pagamento']) ?></td>
                    <td><?= $row['qtd'] ?></td>
                    <td>R$ <?= number_format($row['total'], 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>