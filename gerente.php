<?php
require_once 'login.php';

// Importa todos os controladores do sistema 
include_once 'crud_usuarios.php'; 
include_once 'crud_veiculos.php';
include_once 'crud_rotas.php';
include_once 'crud_cidades.php';
include_once 'crud_viagens.php';
include_once 'crud_clientes.php';
include_once 'crud_reservas.php';

// 1. Impede acesso direto à página sem estar logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('location:index.php');
    exit();
}

$id = mysqli_real_escape_string($connect, $_SESSION['id_usuario']);

// 2. Obter os dados do usuário e confirmar o perfil
$sql = "SELECT usuario.nome AS nome_usuario, usuario.perfil_id, perfil.nome AS nome_perfil 
        FROM usuario 
        INNER JOIN perfil ON usuario.perfil_id = perfil.id 
        WHERE usuario.id = '$id'";
$resultado = mysqli_query($connect, $sql);
$dados = mysqli_fetch_array($resultado);

// Fallback de segurança
if (!$dados) {
    session_unset();
    session_destroy();
    header('location:index.php');
    exit();
}

// Permite entrada APENAS do Gerente (Perfil ID 1)
if ($dados['perfil_id'] != 1) {
    header('location:index.php?erro=acesso_negado');
    exit();
}

// Carrega os dados do sistema
$usuarios_geral = listarUsuarios(); 
$veiculos       = listarVeiculos();
$rotas          = listarRotas();
$cidades        = listarCidades();
$viagens        = listarViagens();
$clientes       = listarClientes();
$todas_vendas   = listarHistoricoReservas(); // Sem parâmetro: traz o histórico de TODOS

// Lista de Consultores de Vendas 
$consultores = array_filter($usuarios_geral, function ($u) {
    return $u['perfil_id'] == 2;
});

// Indicadores Financeiros Rápidos
$faturamento_total = 0;
$total_passagens_vendidas = count($todas_vendas);
foreach ($todas_vendas as $venda) {
    $faturamento_total += $venda['valor_pago'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Gerente - Relatórios e Auditoria</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f6f9;
            color: #333;
        }

        header {
            background-color: #1a237e;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header a {
            color: #ff8a80;
            text-decoration: none;
            font-weight: bold;
        }

        .grid-cards {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1;
            border-left: 5px solid #1a237e;
        }

        .card.money {
            border-left-color: #2e7d32;
        }

        .card h4 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 14px;
        }

        .card span {
            font-size: 24px;
            font-weight: bold;
        }

        .section-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .btn-relatorio {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .btn-relatorio:hover {
            background-color: #1b5e20;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <!-- ===== HEADER ===== -->
    <header>
        <div>
            <h1>Painel Estratégico do Gerente</h1>
            <p>Bem-vindo, <strong><?= htmlspecialchars($dados['nome_usuario']); ?></strong>!</p>
        </div>
        <a href="logout.php">Encerrar Sessão</a>
    </header>

    <!-- ===== PAINEIS DE DESEMPENHO ===== -->
    <div class="grid-cards">
        <div class="card money">
            <h4>💰 FATURAMENTO BRUTO TOTAL</h4>
            <span style="color: #2e7d32;">R$ <?= number_format($faturamento_total, 2, ',', '.') ?></span>
        </div>
        <div class="card">
            <h4>🎫 TOTAL DE PASSAGENS VENDIDAS</h4>
            <span><?= $total_passagens_vendidas ?> bilhetes</span>
        </div>
        <div class="card">
            <h4>👥 TOTAL DE CLIENTES ATIVOS</h4>
            <span><?= count($clientes) ?> cadastrados</span>
        </div>
    </div>

    <!-- ===== CENTRAL DE RELATÓRIOS (BOTÕES INSPIRACIONAIS) ===== -->
    <div class="section-box" style="background-color: #efebe9; border: 1px solid #d7ccc8;">
        <h3>📊 Central de Inteligência e Relatórios Gerenciais</h3>
        <p>Clique abaixo para emitir os relatórios analíticos de desempenho da frota e vendas:</p>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="relatorio_faturamento.php" class="btn-relatorio">📈 Relatório Financeiro por Período</a>
            <a href="ranking_vendedores.php" class="btn-relatorio" style="background-color: #0277bd;">🏆 Ranking de Vendas por Consultor</a>
            <a href="ranking_rotas.php" class="btn-relatorio" style="background-color: #ef6c00;">🚌 Linhas e Rotas Mais Procuradas</a>
            <a href="ranking_tipo_de_veiculo.php" class="btn-relatorio" style="background-color: #6a1b9a;">🚌 Taxa de Ocupação por Tipo de Ônibus</a>
        </div>
    </div>

    <!-- ===== VISTA DE AUDITORIA DE TABELAS (APENAS LEITURA) ===== -->

    <!-- 1. CONSULTORES DE VENDAS -->
    <div class="section-box">
        <h3>👥 Equipe de Consultores de Vendas Ativos</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Consultor</th>
                    <th>Login no Sistema</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consultores as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['nome']) ?></td>
                        <td><?= htmlspecialchars($c['login']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 2. HISTÓRICO DE BILHETES EMITIDOS -->
    <div class="section-box">
        <h3>🎫 Últimos Bilhetes Emitidos (Todas as Vendas)</h3>
        <table>
            <thead>
                <tr>
                    <th>Nº Bilhete</th>
                    <th>Data Venda</th>
                    <th>Consultor</th>
                    <th>Passageiro</th>
                    <th>Rota / Linha</th>
                    <th>Forma Pgto</th>
                    <th>Valor Arrecadado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todas_vendas as $venda): ?>
                    <tr>
                        <td>#<?= $venda["reserva_id"] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($venda["data_venda"])) ?></td>
                        <td><strong><?= htmlspecialchars($venda["nome_vendedor"]) ?></strong></td>
                        <td><?= htmlspecialchars($venda["nome_cliente"]) ?></td>
                        <td><?= htmlspecialchars($venda["nome_rota"]) ?></td>
                        <td><?= strtoupper($venda["forma_pagamento"]) ?> <?= $venda["forma_pagamento"] == 'cartao' ? "({$venda['parcelas']}x)" : "" ?></td>
                        <td style="color: green; font-weight: bold;">R$ <?= number_format($venda["valor_pago"], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 3. DETALHAMENTO DA FROTA (VEÍCULOS) -->
    <div class="section-box">
        <h3>🚍 Frota de Veículos Cadastrada</h3>
        <table>
            <thead>
                <tr>
                    <th>Marca/Modelo</th>
                    <th>Configuração Poltronas</th>
                    <th>Categoria / Tipo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($veiculos as $vei): ?>
                    <tr>
                        <td><?= htmlspecialchars($vei['marca'] . " " . $vei['modelo']) ?></td>
                        <td><?= htmlspecialchars($vei['poltrona']) ?></td>
                        <td><?= htmlspecialchars($vei['tipo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 4. ROTAS E ITINERÁRIOS -->
    <div class="section-box">
        <h3>🗺️ Grade de Rotas Autorizadas</h3>
        <table>
            <thead>
                <tr>
                    <th>Identificação da Linha</th>
                    <th>Cidade Origem</th>
                    <th>Cidade Destino</th>
                    <th>Tempo Viagem</th>
                    <th>Preço Base</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rotas as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nome']) ?></td>
                        <td><?= htmlspecialchars($r['origem']) ?></td>
                        <td><?= htmlspecialchars($r['destino']) ?></td>
                        <td><?= htmlspecialchars($r['tempo_viagem']) ?></td>
                        <td>R$ <?= number_format($r['valor_base'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>