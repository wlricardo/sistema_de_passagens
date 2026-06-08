<?php
require_once 'login.php';
require_once 'conexao.php';

if (!isset($_SESSION['logado']) || $_SESSION['perfil_id'] != 1) {
    header('Location: index.php');
    exit();
}

$banco = abrirBanco();
$sql = "SELECT vei.tipo AS categoria, COUNT(res.id) AS passagens, SUM(res.valor_pago) AS receita_categoria
        FROM reserva res
        INNER JOIN viagem v ON res.viagem_id = v.id
        INNER JOIN veiculo vei ON v.veiculo_id = vei.id
        GROUP BY vei.tipo 
        ORDER BY passagens DESC";
$resultado = $banco->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Ocupação da Frota</title>
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

    <h2>VÁ COM DEUS RODOVIÁRIO - AUDITORIA DE PREFERÊNCIA DE FROTA</h2>
    <p>Desempenho por categoria de conforto do ônibus</p>
    <hr>

    <table>
        <thead>
            <tr>
                <th>Categoria do Ônibus</th>
                <th>Assentos Reservados</th>
                <th>Receita Bruta</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['categoria']) ?></strong></td>
                    <td><?= $row['passagens'] ?> passagens vendidas</td>
                    <td>R$ <?= number_format($row['receita_categoria'], 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>