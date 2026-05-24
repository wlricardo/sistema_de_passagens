<?php
require_once 'login.php';
include 'crud_clientes.php';
include 'crud_usuarios.php';
$clientes = listarClientes();
$usuarios = listarUsuarios();

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
    <title>Analista de TI - Ambiente administrativo</title>
</head>

<!-- Conteúdo principal da página : Perfil Analista de TI -->

<body>

    <!-- ===== HEADER ===== -->
    <header class="page-header">
        <div>
            <h1>Sistema de Vá com Deus Passagens Rodoviárias</h1>
            <p>Painel do Usuário</p>
        </div>
    </header>

    <!-- ===== CONTEÚDO PRINCIPAL ===== -->
    <main class="page-content">

        <div class="content-grid">

            <!-- PERFIL DE ANALISTA DE TI -->
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

            <!-- ==== TABELA DE USUÁRIOS ==== -->
            <section class="table-section">
                <h3>📊 Usuários cadastrados </h3>
                <div class="table-placeholder">
                    <form name="inserir" action="inserir_usuario.php" method="POST">
                        <button type="submit" name="btn-inserir" class="btn-inserir">Inserir</button>
                    </form>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Login</th>
                                <th>Senha</th>
                                <th>Perfil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($usuarios as $usuario) { ?>
                                <tr>
                                    <td><?= $usuario["nome"] ?></td>
                                    <td><?= $usuario["login"] ?></td>
                                    <td><?= $usuario["senha"] ?></td>
                                    <td><?= $usuario["nome_perfil"] ?></td>
                                    <th>
                                        <form name="alterar" action="alterar_usuario.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $usuario["id"] ?> />
                                            <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                        </form>
                                    </th>
                                    <th>
                                        <form name="excluir" action="crud_usuarios.php" method="POST">
                                            <input type="hidden" name="id" value=<?= $usuario["id"] ?> />
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

        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="page-footer">
        &copy; <?php echo date('Y'); ?> Vá com Deus - Todos os direitos reservados.
    </footer>

</body>

</html>