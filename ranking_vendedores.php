<?php
require_once 'login.php';
include_once 'crud_reservas.php';

// Verifica se é gerente
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['perfil_id'] != 1) {
    header('location:index.php');
    exit();
}

// Busca todas as vendas com informações dos vendedores
$todas_vendas = listarHistoricoReservas();

// Agrupa vendas por vendedor (usando nome_vendedor como chave)
$ranking_vendedores = [];
foreach ($todas_vendas as $venda) {
    $vendedor_nome = $venda['nome_vendedor'];

    if (!isset($ranking_vendedores[$vendedor_nome])) {
        $ranking_vendedores[$vendedor_nome] = [
            'nome' => $vendedor_nome,
            'total_vendas' => 0,
            'valor_total' => 0,
            'ticket_medio' => 0
        ];
    }

    $ranking_vendedores[$vendedor_nome]['total_vendas']++;
    $ranking_vendedores[$vendedor_nome]['valor_total'] += $venda['valor_pago'];
}

// Calcula ticket médio
foreach ($ranking_vendedores as $nome => &$dados) {
    if ($dados['total_vendas'] > 0) {
        $dados['ticket_medio'] = $dados['valor_total'] / $dados['total_vendas'];
    }
}

// Ordena pelo valor total (decrescente)
usort($ranking_vendedores, function ($a, $b) {
    return $b['valor_total'] <=> $a['valor_total'];
});

// Adiciona posição ao ranking
$posicao = 1;
foreach ($ranking_vendedores as $nome => &$dados) {
    $dados['posicao'] = $posicao++;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking de Vendas por Consultor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #1a237e;
            color: white;
            padding: 20px 30px;
            border-radius: 5px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .btn-voltar {
            background-color: #1a237e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .btn-voltar:hover {
            background-color: #0d1642;
        }

        .btn-imprimir {
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .btn-imprimir:hover {
            background-color: #1b5e20;
        }

        .ranking-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .ranking-table thead {
            background-color: #1a237e;
            color: white;
        }

        .ranking-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        .ranking-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        .ranking-table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .posicao-1 {
            background-color: #ffd700 !important;
            font-weight: bold;
        }

        .posicao-2 {
            background-color: #c0c0c0 !important;
            font-weight: bold;
        }

        .posicao-3 {
            background-color: #cd7f32 !important;
            color: white;
            font-weight: bold;
        }

        .valor {
            color: #2e7d32;
            font-weight: bold;
        }

        .medalha {
            font-size: 24px;
        }

        .resumo-box {
            margin-top: 30px;
            padding: 20px;
            background-color: #e3f2fd;
            border-left: 4px solid #1a237e;
            border-radius: 6px;
        }

        .resumo-box h4 {
            color: #1a237e;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .resumo-box p {
            margin: 8px 0;
            font-size: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        @media print {

            .btn-voltar,
            .btn-imprimir {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            .header {
                background-color: #1a237e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="gerente.php" class="btn-voltar">← Voltar</a>
            <button onclick="window.print()" class="btn-imprimir">🖨️ Imprimir</button>
        </div>

        <div class="header">
            <h1>🏆 RANKING DE VENDAS POR CONSULTOR</h1>
            <p>Sistema Vá com Deus Rodoviário</p>
            <p>Gerado em: <?php echo date('d/m/Y H:i'); ?></p>
        </div>

        <?php if (empty($ranking_vendedores)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📊</div>
                <p>Nenhuma venda registrada no sistema.</p>
            </div>
        <?php else: ?>
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Posição</th>
                        <th>Consultor</th>
                        <th style="text-align: center; width: 150px;">Total de Vendas</th>
                        <th style="text-align: right; width: 180px;">Valor Total Vendido</th>
                        <th style="text-align: right; width: 150px;">Ticket Médio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ranking_vendedores as $vendedor): ?>
                        <?php
                        $classe_linha = '';
                        $medalha = '';

                        if ($vendedor['posicao'] == 1) {
                            $classe_linha = 'posicao-1';
                            $medalha = '🥇';
                        } elseif ($vendedor['posicao'] == 2) {
                            $classe_linha = 'posicao-2';
                            $medalha = '🥈';
                        } elseif ($vendedor['posicao'] == 3) {
                            $classe_linha = 'posicao-3';
                            $medalha = '';
                        }
                        ?>
                        <tr class="<?= $classe_linha ?>">
                            <td style="text-align: center;">
                                <span class="medalha"><?= $medalha ?></span>
                                <br>
                                <small><?= $vendedor['posicao'] ?>º lugar</small>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($vendedor['nome']) ?></strong>
                            </td>
                            <td style="text-align: center;">
                                <?= $vendedor['total_vendas'] ?> venda(s)
                            </td>
                            <td style="text-align: right;" class="valor">
                                R$ <?= number_format($vendedor['valor_total'], 2, ',', '.') ?>
                            </td>
                            <td style="text-align: right;">
                                R$ <?= number_format($vendedor['ticket_medio'], 2, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="resumo-box">
                <h4>📊 Resumo do Ranking</h4>
                <p>
                    <strong>Total de Consultores:</strong> <?= count($ranking_vendedores) ?>
                </p>
                <p>
                    <strong>Maior Vendedor:</strong> <?= htmlspecialchars($ranking_vendedores[0]['nome']) ?>
                    (R$ <?= number_format($ranking_vendedores[0]['valor_total'], 2, ',', '.') ?>)
                </p>
                <p>
                    <strong>Total de Vendas no Período:</strong>
                    <?php
                    $total_geral = array_sum(array_column($ranking_vendedores, 'total_vendas'));
                    echo $total_geral . ' venda(s)';
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>