<?php
require_once 'login.php';
include 'crud_clientes.php';
include 'crud_veiculos.php';
include 'crud_rotas.php';
include 'crud_viagens.php';
$clientes = listarClientes();
$veiculos = listarVeiculos();
$rotas = listarRotas();
$viagens_disponiveis = listarViagens();

// Impede acesso direto à página sem estar logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('location:index.php');
    exit();
}

$id = mysqli_real_escape_string($connect, $_SESSION['id_usuario']);

// Consulta para obter os dados do usuário e seu perfil
$sql = "SELECT usuario.nome AS nome_usuario, perfil.nome AS nome_perfil 
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
?>

<!DOCTYPE html>
<html lang="en">

<!-- Cabeçalho HTML e estilos -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultor de Vendas - Ambiente operacional</title>
</head>

<!-- Conteúdo principal da página : Perfil Consultor de Vendas -->

<body>

    <!-- ===== HEADER ===== -->
    <header class="page-header">
        <div>
            <h1>Sistema Vá com Deus de Passagens Rodoviárias</h1>
            <p>Painel do Usuário</p>
        </div>
    </header>

    <!-- ===== CONTEÚDO PRINCIPAL ===== -->
    <main class="page-content">

        <div class="content-grid">

            <!-- PERFIL DE CONSULTOR DE VENDAS -->
            <aside class="user-card">
                <div class="card-body">
                    <div class="welcome-box">
                        <h2>Bem-vindo, <?php echo htmlspecialchars($dados['nome_usuario']); ?>!</h2>
                        <span>Autenticado com sucesso.</span>
                    </div>

                    <div class="info-grid"> <!-- AJUSTAR PARA PEGAR O PERFIL DO USUÁRIO -->
                        <div class="info-item">
                            <label>Perfil</label>
                            <span><?php echo htmlspecialchars($dados['nome_perfil'] ?? 'Não definido'); ?></span>
                        </div>
                    </div>

                    <a href="logout.php" class="btn-sair">Encerrar Sessão</a>
                </div>
            </aside>

            <!-- ===== TABELA DE CLIENTES ===== -->
            <section class="table-section">
                <h3>📊 Clientes cadastrados </h3>
                <div class="table-placeholder">
                    <form name="inserir" action="inserir_cliente.php" method="POST">
                        <button type="submit" name="btn-inserir" class="btn-inserir">Inserir</button>
                        <!-- <a href="inserir_cliente.php"> Adicionar Cliente</a> 
                        <input type="submit" value="Inserir" name="inserir"> -->
                    </form>

                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Email</th>
                                <th>Login</th>
                                <th>Senha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($clientes as $cliente) { ?>
                                <tr>
                                    <td><?= $cliente["nome"] ?></td>
                                    <td><?= $cliente["cpf"] ?></td>
                                    <td><?= $cliente["email"] ?></td>
                                    <td><?= $cliente["login"] ?></td>
                                    <td> ****** </td>
                                    <th>
                                        <form name="alterar" action="alterar_cliente.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $cliente["id"] ?> />
                                            <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                        </form>
                                    </th>
                                    <th>
                                        <form name="excluir" action="crud_clientes.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $cliente["id"] ?> />
                                            <input type="hidden" name="acao" value="excluir" />
                                            <button type="submit" name="btn-excluir" class="btn-excluir">Excluir</button>
                                        </form>
                                    </th>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                    <!--</div>                
                ==== RELATÓRIO === 
                <div class="emitir-relatorio">
                    <form name="relatorio" action="relatorio.php" method="POST">
                        <button type="submit" name="btn-relatorio" class="btn-relatorio">Emitir relatório</button>
                    </form>
                    <!--<section class="relatorio">
                </section>
                </div> -->
            </section>

            <!-- ==== TABELA DE VEÍCULOS ==== -->
            <section class="table-section">
                <h3>📊 Veículos cadastrados </h3>
                <div class="table-placeholder">
                    <form name="inserir" action="inserir_veiculo.php" method="POST">
                        <button type="submit" name="btn-inserir" class="btn-inserir">Inserir</button>
                    </form>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Poltrona</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($veiculos as $veiculo) { ?>
                                <tr>
                                    <td><?= $veiculo["marca"] ?></td>
                                    <td><?= $veiculo["modelo"] ?></td>
                                    <td><?= $veiculo["poltrona"] ?></td>
                                    <td><?= $veiculo["tipo"] ?></td>
                                    <th>
                                        <form name="alterar" action="alterar_veiculo.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $veiculo["id"] ?> />
                                            <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                        </form>
                                    </th>
                                    <th>
                                        <form name="excluir" action="crud_veiculos.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $veiculo["id"] ?> />
                                            <input type="hidden" name="acao" value="excluir" />
                                            <button type="submit" name="btn-excluir" class="btn-excluir">Excluir</button>
                                        </form>
                                    </th>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ==== TABELA DE ROTAS ==== -->
            <section class="table-section">
                <h3>📊 Rotas cadastradas </h3>
                <div class="table-placeholder">
                    <form name="inserir" action="inserir_rota.php" method="POST">
                        <button type="submit" name="btn-inserir" class="btn-inserir">Inserir</button>
                    </form>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cidade de Origem</th>
                                <th>Cidade de Destino</th>
                                <th>Tempo de Viagem</th>
                                <th>Valor Base</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($rotas as $rota) { ?>
                                <tr>
                                    <td><?= $rota["nome"] ?></td>
                                    <td><?= $rota["origem"] ?></td>
                                    <td><?= $rota["destino"] ?></td>
                                    <td><?= $rota["tempo_viagem"] ?></td>
                                    <td><?= $rota["valor_base"] ?></td>
                                    <th>
                                        <form name="alterar" action="alterar_rota.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $rota["id"] ?> />
                                            <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                        </form>
                                    </th>
                                    <th>
                                        <form name="excluir" action="crud_rotas.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $rota["id"] ?> />
                                            <input type="hidden" name="acao" value="excluir" />
                                            <button type="submit" name="btn-excluir" class="btn-excluir">Excluir</button>
                                        </form>
                                    </th>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ==== TABELA DE VIAGENS ==== -->
            <section class="table-section">
                <h3>🚌 Viagens Disponíveis para Venda</h3>

                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Rota / Linha</th>
                            <th>Veículo (Modelo - Tipo)</th>
                            <th>Data da Viagem</th>
                            <th>Valor da Passagem</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($viagens_disponiveis)): ?>
                            <tr>
                                <td colspan="6" align="center">Nenhuma viagem localizada para os próximos dias.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($viagens_disponiveis as $viagem): ?>
                                <tr>
                                    <td><?= $viagem["id"] ?></td>
                                    <td><?= htmlspecialchars($viagem["nome_rota"]) ?></td>
                                    <td><?= htmlspecialchars($viagem["modelo_veiculo"] . " (" . $viagem["tipo_veiculo"] . ")") ?></td>

                                    <!-- Formata a data de YYYY-MM-DD para DD/MM/YYYY -->
                                    <td><?= date('d/m/Y', strtotime($viagem["data"])) ?></td>

                                    <!-- Formata o valor monetário com vírgula para os centavos -->
                                    <td>R$ <?= number_format($viagem["valor"], 2, ',', '.') ?></td>

                                    <td>
                                        <!-- Formulário para iniciar o processo de venda/emissão da passagem -->
                                        <form action="vender_passagem.php" method="POST">
                                            <input type="hidden" name="viagem_id" value="<?= $viagem["id"] ?>" />
                                            <button type="submit" name="btn-vender">Vender Passagem</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

        </div>

        </div>


        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="page-footer">
        &copy; <?php echo date('Y'); ?> Vá com Deus - Todos os direitos reservados.
    </footer>

</body>

</html>