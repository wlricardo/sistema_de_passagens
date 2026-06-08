<?php
require_once 'login.php';
include 'crud_clientes.php';
include 'crud_viagens.php';
include 'crud_reservas.php';

$historico_vendas = listarHistoricoReservas($_SESSION['id_usuario']);
$clientes = listarClientes();
$viagens_disponiveis = listarViagens();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('location:index.php');
    exit();
}

$id = mysqli_real_escape_string($connect, $_SESSION['id_usuario']);
$sql = "SELECT usuario.nome AS nome_usuario, usuario.perfil_id, perfil.nome AS nome_perfil
FROM usuario
INNER JOIN perfil ON usuario.perfil_id = perfil.id
WHERE usuario.id = '$id'";
$resultado = mysqli_query($connect, $sql);
$dados = mysqli_fetch_array($resultado);

if (!$dados) {
    session_unset();
    session_destroy();
    header('location:index.php');
    exit();
}

if ($dados['perfil_id'] != 2) {
    header('location:index.php?erro=acesso_negado');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultor de Vendas - Ambiente operacional</title>
    <style>
        /* ===== RESET E BASE ===== */
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
        }

        /* ===== HEADER ===== */
        .page-header {
            background-color: #1a237e;
            color: white;
            padding: 20px 30px;
            border-radius: 5px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .page-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        /* ===== LAYOUT PRINCIPAL ===== */
        .page-content {
            padding: 0 20px 40px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 25px;
            align-items: start;
        }

        /* ===== CARD DO USUÁRIO ===== */
        .user-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-body {
            padding: 25px;
        }

        .welcome-box {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .welcome-box h2 {
            font-size: 20px;
            color: #1a237e;
            margin-bottom: 8px;
        }

        .welcome-box span {
            font-size: 13px;
            color: #666;
        }

        .info-grid {
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }

        .info-item span {
            color: #333;
            font-size: 14px;
        }

        /* ===== BOTÃO SAIR ===== */
        .btn-sair {
            background-color: #dc3545;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .btn-sair:hover {
            background-color: #bd2130;
        }

        .btn-sair:active {
            transform: scale(0.98);
        }

        /* ===== SISTEMA DE ABAS (Estilo Moderno) ===== */
        .tabs-container {
            display: flex;
            gap: 5px;
            margin-bottom: 0;
            flex-wrap: wrap;
            background: #e0e0e0;
            padding: 5px 5px 0;
            border-radius: 8px 8px 0 0;
        }

        .tab-btn {
            background-color: #e0e0e0;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px 6px 0 0;
            transition: background 0.3s, color 0.3s;
            color: #555;
        }

        .tab-btn:hover {
            background-color: #d5d5d5;
            color: #333;
        }

        .tab-btn.active {
            background-color: #ffffff;
            color: #1a237e;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
        }

        /* ===== CONTEÚDO DAS ABAS ===== */
        .tab-content {
            display: none;
            padding: 25px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .tab-content.active-content {
            display: block !important;
        }

        .tab-content h3 {
            font-size: 20px;
            color: #1a237e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        /* ===== BOTÕES DE AÇÃO ===== */
        .btn-inserir {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 15px;
            transition: background-color 0.2s ease;
        }

        .btn-inserir:hover {
            background-color: #1b5e20;
        }

        .btn-editar {
            background-color: #0277bd;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-editar:hover {
            background-color: #01579b;
        }

        .btn-excluir {
            background-color: #c62828;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-excluir:hover {
            background-color: #b71c1c;
        }

        .btn-vender {
            background-color: #ef6c00;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .btn-vender:hover {
            background-color: #e65100;
        }

        /* ===== TABELAS ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
        }

        table thead {
            background-color: #f5f5f5;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        table td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 14px;
            color: #555;
        }

        table tbody tr:hover {
            background-color: #f9f9f9;
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

        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .tabs-container {
                flex-direction: column;
            }

            .tab-btn {
                width: 100%;
                border-radius: 6px;
                margin-bottom: 2px;
            }
        }
    </style>
</head>

<body>

    <!-- ===== HEADER ===== -->
    <header class="page-header">
        <div>
            <h1>Sistema Vá com Deus de Passagens Rodoviárias</h1>
            <p>Painel de Vendas</p>
        </div>
    </header>

    <!-- ===== CONTEÚDO PRINCIPAL ===== -->
    <main class="page-content">
        <div class="content-grid">

            <!-- CARD DO USUÁRIO -->
            <aside class="user-card">
                <div class="card-body">
                    <div class="welcome-box">
                        <h2>Bem-vindo, <?php echo htmlspecialchars($dados['nome_usuario']); ?>!</h2>
                        <span>Autenticado com sucesso.</span>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Perfil</label>
                            <span><?php echo htmlspecialchars($dados['nome_perfil'] ?? 'Não definido'); ?></span>
                        </div>
                    </div>
                    <form action="logout.php" method="POST" style="margin: 0; padding: 0;">
                        <button type="submit" class="btn-sair">Encerrar Sessão</button>
                    </form>
                </div>
            </aside>

            <!-- ÁREA DE ABAS E CONTEÚDO -->
            <div class="main-tabs-wrapper" style="flex: 1; display: flex; flex-direction: column;">

                <!-- Navegação das Abas -->
                <div class="tabs-container">
                    <button class="tab-btn active" onclick="alternarAba(event, 'aba-viagens')">🗺️ Viagens Disponíveis</button>
                    <button class="tab-btn" onclick="alternarAba(event, 'aba-clientes')">👥 Gerenciar Clientes</button>
                    <button class="tab-btn" onclick="alternarAba(event, 'aba-historico')">️ Segunda Via / Bilhetes</button>
                </div>

                <!-- ============================================ -->
                <!-- ABA 1: TABELA DE VIAGENS E OPERAÇÃO DE VENDA -->
                <!-- ============================================ -->
                <div id="aba-viagens" class="tab-content active-content">
                    <section class="table-section">
                        <h3>🚌 Viagens Disponíveis para Venda</h3>
                        <div class="table-placeholder">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID Viagem</th>
                                        <th>Rota / Linha</th>
                                        <th>Veículo</th>
                                        <th>Data</th>
                                        <th>Valor da Tarifa</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($viagens_disponiveis)): ?>
                                        <tr>
                                            <td colspan="6" align="center">Nenhuma viagem localizada para venda.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($viagens_disponiveis as $viagem): ?>
                                            <tr>
                                                <td><?= $viagem["id"] ?></td>
                                                <td><?= htmlspecialchars($viagem["nome_rota"]) ?></td>
                                                <td><?= htmlspecialchars($viagem["modelo_veiculo"] . " (" . $viagem["tipo_veiculo"] . ")") ?></td>
                                                <td><?= date('d/m/Y', strtotime($viagem["data"])) ?></td>
                                                <td>R$ <?= number_format($viagem["valor"], 2, ',', '.') ?></td>
                                                <td>
                                                    <form name="venderPassagem" action="vender_passagem.php" method="POST" style="margin:0;">
                                                        <input type="hidden" name="viagem_id" value="<?= $viagem["id"] ?>" />
                                                        <button type="submit" name="btn-vender" class="btn-vender">Vender</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- ========================================== -->
                <!-- ABA 2: TABELA DE CLIENTES                  -->
                <!-- ========================================== -->
                <div id="aba-clientes" class="tab-content">
                    <section class="table-section">
                        <h3>📊 Clientes cadastrados</h3>
                        <div class="table-placeholder">
                            <form name="inserir" action="inserir_cliente.php" method="POST">
                                <input type="hidden" name="origem" value="vendas.php" />
                                <button type="submit" name="btn-inserir" class="btn-inserir">Inserir Novo Cliente</button>
                            </form>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>CPF</th>
                                        <th>Email</th>
                                        <th>Login</th>
                                        <th>Senha</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clientes as $cliente) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($cliente["nome"]) ?></td>
                                            <td><?= htmlspecialchars($cliente["cpf"]) ?></td>
                                            <td><?= htmlspecialchars($cliente["email"]) ?></td>
                                            <td><?= htmlspecialchars($cliente["login"]) ?></td>
                                            <td>******</td>
                                            <td>
                                                <div style="display: flex; gap: 5px;">
                                                    <form name="alterar" action="alterar_cliente.php" method="POST" style="margin:0;">
                                                        <input type="hidden" name="cliente_id" value="<?= $cliente["id"] ?>" />
                                                        <input type="hidden" name="origem" value="vendas.php" />
                                                        <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                                    </form>
                                                    <form name="excluir" action="crud_clientes.php" method="POST" style="margin:0;">
                                                        <input type="hidden" name="cliente_id" value="<?= $cliente["id"] ?>" />
                                                        <input type="hidden" name="acao" value="excluir" />
                                                        <input type="hidden" name="origem" value="vendas.php" />
                                                        <button type="submit" name="btn-excluir" class="btn-excluir" onclick="return confirm('Deseja realmente excluir este cliente?')">Excluir</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- ========================================== -->
                <!-- ABA 3: HISTÓRICO DE VENDAS DO CONSULTOR    -->
                <!-- ========================================== -->
                <div id="aba-historico" class="tab-content">
                    <section class="table-section">
                        <h3>📜 Meus Bilhetes Emitidos (Histórico de Vendas)</h3>
                        <div class="table-placeholder">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nº Bilhete</th>
                                        <th>Data/Hora Emissão</th>
                                        <th>Cliente / Passageiro</th>
                                        <th>Rota / Linha</th>
                                        <th>Data Embarque</th>
                                        <th>Pagamento</th>
                                        <th>Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($historico_vendas)): ?>
                                        <tr>
                                            <td colspan="7" align="center">Você ainda não realizou nenhuma venda.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($historico_vendas as $reserva): ?>
                                            <tr>
                                                <td># <?= htmlspecialchars($reserva["reserva_id"]) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($reserva["data_venda"])) ?></td>
                                                <td><?= htmlspecialchars($reserva["nome_cliente"]) ?></td>
                                                <td><?= htmlspecialchars($reserva["nome_rota"]) ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($reserva["data_viagem"])) ?></td>
                                                <td><?= strtoupper(htmlspecialchars($reserva["forma_pagamento"])) ?></td>
                                                <td style="color: #2e7d32; font-weight: bold;">R$ <?= number_format($reserva["valor_pago"], 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

            </div>
        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="page-footer">
        &copy; <?php echo date('Y'); ?> Vá com Deus - Todos os direitos reservados.
    </footer>

    <script>
        function alternarAba(evento, idAba) {
            const botoes = document.querySelectorAll('.tab-btn');
            botoes.forEach(btn => btn.classList.remove('active'));

            const conteudos = document.querySelectorAll('.tab-content');
            conteudos.forEach(conteudo => conteudo.classList.remove('active-content'));

            evento.currentTarget.classList.add('active');
            document.getElementById(idAba).classList.add('active-content');
        }
    </script>
</body>

</html>