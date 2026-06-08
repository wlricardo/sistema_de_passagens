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

// Carrega os dados do sistema (Apenas para leitura)
$usuarios_geral = listarUsuarios();
$veiculos       = listarVeiculos();
$rotas          = listarRotas();
$clientes       = listarClientes();
$todas_vendas   = listarHistoricoReservas(); // Traz o histórico de TODOS

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
    <title>Painel de Relatórios</title>
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

        /* Cards de Indicadores */
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

        /* Sistema de Abas (Mesmo padrão de vendas.php) */
        .tabs-container {
            display: flex;
            gap: 5px;
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .tab-btn {
            background-color: #e0e0e0;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px 5px 0 0;
            transition: background 0.3s;
        }

        .tab-btn:hover {
            background-color: #d5d5d5;
        }

        .tab-btn.active {
            background-color: #ffffff;
            border-bottom: 2px solid #1a237e;
            color: #1a237e;
        }

        .tab-content {
            display: none;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .tab-content.active-content {
            display: block !important;
        }

        /* Botões de Relatórios (Central) */
        .btn-relatorio {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: background-color 0.2s ease;
        }

        .btn-relatorio:hover {
            background-color: #1b5e20;
        }

        .btn-relatorio.azul {
            background-color: #0277bd;
        }

        .btn-relatorio.azul:hover {
            background-color: #01579b;
        }

        .btn-relatorio.laranja {
            background-color: #ef6c00;
        }

        .btn-relatorio.laranja:hover {
            background-color: #e65100;
        }

        .btn-relatorio.roxo {
            background-color: #6a1b9a;
        }

        .btn-relatorio.roxo:hover {
            background-color: #4a148c;
        }

        /* Tabelas de Leitura */
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
            font-weight: bold;
        }

        .section-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        /* ===== FOOTER ===== */
        .page-footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 13px;
            border-top: 1px solid #e0e0e0;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <!-- ===== Header ===== -->
    <header>
        <div>
            <h1>Painel de Controle do Gerente</h1>
            <p>Bem-vindo, <strong><?= htmlspecialchars($dados['nome_usuario']); ?></strong>!</p>
        </div>
        <a href="logout.php">Encerrar Sessão</a>
    </header>

    <!-- ===== Indicadores de desempenho ===== -->
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

    <!-- ===== Abas de navegação do Painel ===== -->
    <div class="tabs-container">
        <button class="tab-btn active" onclick="alternarAba(event, 'aba-relatorios')">📊 Relatórios Gerenciais</button>
        <button class="tab-btn" onclick="alternarAba(event, 'aba-bilhetes')">🎫 Bilhetes Emitidos</button>
        <button class="tab-btn" onclick="alternarAba(event, 'aba-frota')">🚍 Frota de Veículos</button>
        <button class="tab-btn" onclick="alternarAba(event, 'aba-rotas')">🗺️ Grade de Rotas</button>
        <button class="tab-btn" onclick="alternarAba(event, 'aba-consultores')">👥 Consultores</button>
    </div>

    <!-- ========================================== -->
    <!-- ABA 1: RELATÓRIOS                          -->
    <!-- ========================================== -->
    <div id="aba-relatorios" class="tab-content active-content">
        <div class="section-box" style="background-color: #efebe9; border: 1px solid #d7ccc8; margin-bottom: 0;">
            <h3>📊 Central de Relatórios Gerenciais</h3>
            <p>Clique abaixo para emitir os relatórios da frota e vendas:</p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                <a href="relatorio_faturamento.php" class="btn-relatorio">📈 Relatório Financeiro por Período</a>
                <a href="ranking_vendedores.php" class="btn-relatorio azul">🏆 Ranking de Vendas por Consultor</a>
                <a href="ranking_rotas.php" class="btn-relatorio laranja">🚌 Linhas e Rotas Mais Procuradas</a>
                <a href="ranking_tipo_de_veiculo.php" class="btn-relatorio roxo"> Taxa de Ocupação por Tipo de Ônibus</a>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ABA 2: BILHETES EMITIDOS                   -->
    <!-- ========================================== -->
    <div id="aba-bilhetes" class="tab-content">
        <h3> Últimos Bilhetes Emitidos (Todas as Vendas)</h3>
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

    <!-- ========================================== -->
    <!-- ABA 3: FROTA DE VEÍCULOS                   -->
    <!-- ========================================== -->
    <div id="aba-frota" class="tab-content">
        <h3> Frota de Veículos Cadastrada</h3>
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

    <!-- ========================================== -->
    <!-- ABA 4: ROTAS                               -->
    <!-- ========================================== -->
    <div id="aba-rotas" class="tab-content">
        <h3>️ Grade de Rotas Autorizadas</h3>
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

    <!-- ========================================== -->
    <!-- ABA 5: CONSULTORES DE VENDAS               -->
    <!-- ========================================== -->
    <div id="aba-consultores" class="tab-content">
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

    <!-- ===== SCRIPT DE NAVEGAÇÃO POR ABAS ===== -->
    <script>
        function alternarAba(evento, idAba) {
            // 1. Remove o estilo de botão ativo de todos os botões da barra
            const botoes = document.querySelectorAll('.tab-btn');
            botoes.forEach(btn => btn.classList.remove('active'));

            // 2. Esconde o conteúdo de todas as abas do painel
            const conteudos = document.querySelectorAll('.tab-content');
            conteudos.forEach(conteudo => conteudo.classList.remove('active-content'));

            // 3. Aplica o realce visual apenas no botão que recebeu o clique
            evento.currentTarget.classList.add('active');

            // 4. Torna visível apenas o bloco de conteúdo correspondente
            document.getElementById(idAba).classList.add('active-content');
        }
    </script>
    <!-- ===== FOOTER ===== -->
    <footer class="page-footer">
        &copy; <?php echo date('Y'); ?> Vá com Deus - Todos os direitos reservados.
    </footer>

</body>

</html>