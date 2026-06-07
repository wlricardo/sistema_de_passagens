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
        /* 1. Oculta TODAS as abas por padrão */
        .tab-content {
            display: none;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* 2. Exibe EXCLUSIVAMENTE a aba que estiver ativa no momento */
        .tab-content.active-content {
            display: block !important;
        }


        /* Botão para encerrar sessão */
        .btn-sair {
            background-color: #dc3545;
            /* Vermelho moderno */
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-sair:hover {
            background-color: #bd2130;
            /* Vermelho mais escuro */
        }

        .btn-sair:active {
            transform: scale(0.98);
            /* Efeito físico de clique */
        }
    </style>
</head>

<body>

    <header class="page-header">
        <div>
            <h1>Sistema Vá com Deus de Passagens Rodoviárias</h1>
            <p>Painel de Vendas</p>
        </div>
    </header>


    <main class="page-content">
        <div class="content-grid">

            <!-- Informações do usuário -->
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

                    <!-- Botão para encerrar sessão -->
                    <form action="logout.php" method="POST" style="margin: 0; padding: 0;">
                        <button type="submit" class="btn-sair">
                            Encerrar Sessão
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Abas e conteúdos -->
            <div class="main-tabs-wrapper" style="flex: 1; display: flex; flex-direction: column;">

                <!-- Abas de navegação -->
                <div class="tabs-container">
                    <button class="tab-btn active" onclick="alternarAba(event, 'aba-viagens')">🗺️ Viagens Disponíveis</button>
                    <button class="tab-btn" onclick="alternarAba(event, 'aba-clientes')">👥 Gerenciar Clientes</button>
                    <button class="tab-btn" onclick="alternarAba(event, 'aba-historico')">🎟️ Segunda Via / Bilhetes</button>
                </div>

                <!-- ========================================== -->
                <!-- ABA 1: TABELA DE VIAGENS E OPERAÇÃO DE VENDA -->
                <!-- ========================================== -->
                <div id="aba-viagens" class="tab-content active-content">
                    <section class="table-section">
                        <h3>🚌 Viagens Disponíveis para Venda</h3>
                        <div class="table-placeholder">
                            <table border="1">
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
                <!-- ABA 2: TABELA DE CLIENTES (CRUD)           -->
                <!-- ========================================== -->
                <div id="aba-clientes" class="tab-content">
                    <section class="table-section">
                        <h3>📊 Clientes cadastrados</h3>
                        <div class="table-placeholder">
                            <!-- Botão Inserir Cliente -->
                            <form name="inserir" action="inserir_cliente.php" method="POST">
                                <input type="hidden" name="origem" value="vendas.php" />
                                <button type="submit" name="btn-inserir" class="btn-inserir">Inserir Novo Cliente</button>
                            </form>

                            <table border="1">
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
                                            <td> ****** </td>
                                            <td>
                                                <div style="display: flex; gap: 5px;">
                                                    <!-- Editar Cliente -->
                                                    <form name="alterar" action="alterar_cliente.php" method="POST" style="margin:0;">
                                                        <input type="hidden" name="cliente_id" value="<?= $cliente["id"] ?>" />
                                                        <input type="hidden" name="origem" value="vendas.php" />
                                                        <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                                    </form>
                                                    <!-- Excluir Cliente -->
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
                            <table border="1">
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
                                            <td colspan="7" align="center">Você ainda não realizou nenhuma venda hoje.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($historico_vendas as $reserva): ?>
                                            <tr>
                                                <!-- 1. Número do Bilhete -->
                                                <td># <?= htmlspecialchars($reserva["reserva_id"]) ?></td>

                                                <!-- 2. Data/Hora da Emissão -->
                                                <td><?= date('d/m/Y H:i', strtotime($reserva["data_venda"])) ?></td>

                                                <!-- 3. Cliente / Passageiro -->
                                                <td><?= htmlspecialchars($reserva["nome_cliente"]) ?></td>

                                                <!-- 4. Rota / Linha -->
                                                <td><?= htmlspecialchars($reserva["nome_rota"]) ?></td>

                                                <!-- 5. Data do Embarque -->
                                                <td><?= date('d/m/Y H:i', strtotime($reserva["data_viagem"])) ?></td>

                                                <!-- 6. Pagamento -->
                                                <td><?= htmlspecialchars($reserva["forma_pagamento"]) ?></td>

                                                <!-- 7. Valor Total -->
                                                <td>R$ <?= number_format($reserva["valor_pago"], 2, ',', '.') ?></td>
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
</body>