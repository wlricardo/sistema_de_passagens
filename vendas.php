<?php
require_once 'login.php';
include 'crud_clientes.php';
include 'crud_viagens.php';

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
                    <a href="logout.php" class="btn-sair">Encerrar Sessão</a>
                </div>
            </aside>

            <!-- ===== TABELA DE CLIENTES (COM GESTÃO COMPLETA) ===== -->
            <section class="table-section">
                <h3>📊 Clientes cadastrados</h3>
                <div class="table-placeholder">
                    <!-- Botão Inserir Cliente -->
                    <form name="inserir" action="inserir_cliente.php" method="POST">
                        <!-- Enviamos a origem para o formulário saber para onde voltar se o usuário cancelar -->
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
                                                <input type="hidden" name="id" value="<?= $cliente["id"] ?>" />
                                                <input type="hidden" name="origem" value="vendas.php" />
                                                <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                            </form>
                                            <!-- Excluir Cliente -->
                                            <form name="excluir" action="crud_clientes.php" method="POST" style="margin:0;">
                                                <input type="hidden" name="id" value="<?= $cliente["id"] ?>" />
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

            <!-- ==== TABELA DE VIAGENS E OPERAÇÃO DE VENDA ==== -->
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
    </main>

    <footer class="page-footer">
        &copy; <?php echo date('Y'); ?> Vá com Deus - Todos os direitos reservados.
    </footer>

</body>

</html>