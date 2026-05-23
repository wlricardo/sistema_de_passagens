<?php
require_once 'login.php';
include 'crud_clientes.php';
$clientes = listarClientes();

// Impede acesso direto à página sem estar logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('location:index.php');
    exit();
}
$id = mysqli_real_escape_string($connect, $_SESSION['id_cliente']);
$sql = "select * from cliente where id = '$id'";
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
    <title>Analista de TI - Ambiente administrativo</title>
</head>

<!-- Conteúdo principal da página : Perfil Analista de TI -->
<body>

    <!-- ===== HEADER ===== -->
    <header class="page-header">
        <div>
            <h1>Sistema de Gestão COPEL</h1>
            <p>Painel do Usuário</p>
        </div>
    </header>

    <!-- ===== CONTEÚDO PRINCIPAL ===== -->
    <main class="page-content">

        <div class="content-grid">

            <!-- INFORMAÇÕES DO USUÁRIO -->
            <aside class="user-card">
                <div class="card-body">
                    <div class="welcome-box">
                        <h2>Bem-vindo, <?php echo htmlspecialchars($dados['nome']); ?>!</h2>
                        <span>Autenticado com sucesso.</span>
                    </div>

                    <div class="info-grid">  <!-- AJUSTAR PARA PEGAR O PERFIL DO USUÁRIO -->
                        <div class="info-item">
                            <label>Perfil</label>
                            <span><?php echo htmlspecialchars($dados['funcao'] ?? 'Não definido'); ?></span>
                        </div>
                    </div>

                    <a href="logout.php" class="btn-sair">Encerrar Sessão</a>
                </div>
            </aside>

            <!-- ===== TABELA DE CLIENTES ===== -->
            <section class="table-section">
                <h3>📊 Clientes cadastrados </h3>
                <div class="table-placeholder">
                    <form name="inserir" action="inserir.php" method="POST">
                        <button type="submit" name="btn-inserir" class="btn-inserir">Inserir</button>
                        <!--<input type="submit" value="Inserir" name="inserir">
                        <a href="inserir.php"> Adicionar Cliente</a>-->
                    </form>

                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Sexo</th>
                                <th>CPF</th>
                                <th>Data de nascimento</th>
                                <th>Endereço</th>
                                <th>Bairro</th>
                                <th>CEP</th>
                                <th>Unidade Consumidora</th>
                                <th>Kwh consumidos</th>
                                <th>Valor total</th>
                                <th>Data do vencimento</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($grupo as $cliente) { ?>
                                <tr>
                                    <td><?= $cliente["nome"] ?></td>
                                    <td><?= $cliente["sexo"] ?></td>
                                    <td><?= $cliente["cpf"] ?></td>
                                    <td><?= $cliente["dataNascimento"] ?></td>
                                    <td><?= $cliente["endereco"] ?></td>
                                    <td><?= $cliente["bairro"] ?></td>
                                    <td><?= $cliente["cep"] ?></td>
                                    <td><?= $cliente["unidadeConsumidora"] ?></td>
                                    <td><?= $cliente["kwhConsumido"] ?></td>
                                    <td><?= $cliente["valorTotal"] ?></td>
                                    <td><?= $cliente["dataVencimento"] ?></td>
                                    <th>
                                        <form name="alterar" action="alterar.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $cliente["id"] ?> />
                                            <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                        </form>
                                    </th>
                                    <th>
                                        <form name="excluir" action="crud.php" method="POST">
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
                </div>
                <!-- ==== RELATÓRIO === -->
                <div class="emitir-relatorio">
                    <form name="relatorio" action="relatorio.php" method="POST">
                        <button type="submit" name="btn-relatorio" class="btn-relatorio">Emitir relatório</button>
                    </form>
                    <!--<section class="relatorio">
                </section>-->
                </div>
            </section>

        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="page-footer">
        &copy; <?php echo date('Y'); ?> COPEL - Todos os direitos reservados.
    </footer>

</body>

</html>