<?php
require_once 'login.php';
require_once 'conexao.php';

if (!isset($_SESSION['logado']) || $_SESSION['perfil_id'] != 1) {
    header('Location: index.php');
    exit();
}

$banco = abrirBanco();
$sql = "SELECT ro.nome AS linha, COUNT(res.id) AS passagens_vendidas, SUM(res.valor_pago) AS faturamento_linha
        FROM reserva res
        INNER JOIN viagem v ON res.viagem_id = v.id
        INNER JOIN rota ro ON v.rota_id = ro.id
        GROUP BY v.rota_id 
        ORDER BY passagens_vendidas DESC";
$resultado = $banco->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Demanda de Linhas</title>
    <style>
        body {
            font-family: monospace;
            margin: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

    <h2>VÁ COM DEUS RODOVIÁRIO - DEMANDA DE ITINERÁRIOS</h2>
    <p>Classificação de linhas baseada no volume de passageiros</p>
    <hr>

    <table>
        <thead>
            <tr>
                <th>Linha / Trecho</th>
                <th>Passageiros Transportados</th>
                <th>Arrecadação Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows == 0) { ?>
                <tr>
                    <td colspan="3" align="center">Nenhum registro localizado.</td>
                </tr>
            <?php } else { ?>
                <?php while ($row = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['linha']) ?></td>
                        <td><?= $row['passagens_vendidas'] ?> passageiros</td>
                        <td>R$ <?= number_format($row['faturamento_linha'], 2, ',', '.') ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>