<?php
require_once 'login.php';
include 'crud_clientes.php';
include 'crud_usuarios.php';
include 'crud_veiculos.php';
include 'crud_rotas.php';
include 'crud_cidades.php';
include 'crud_viagens.php';
$clientes = listarClientes();
$usuarios = listarUsuarios();
$veiculos = listarVeiculos();
$rotas = listarRotas();
$cidades = listarCidades();
$viagens = listarViagens();

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

    <style>
        /* Container que agrupa a barra de botões */
        .central-abas {
            background-color: #efebe9;
            border: 1px solid #d7ccc8;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 25px;
        }

        .abas-botoes-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        /* Estilo base de cada botão (aba) */
        .btn-aba {
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            font-size: 14px;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-aba:active {
            transform: scale(0.98);
        }

        /* Cores baseadas na imagem */
        .btn-aba.clientes {
            background-color: #2e7d32;
        }

        .btn-aba.usuarios {
            background-color: #0277bd;
        }

        .btn-aba.veiculos {
            background-color: #ef6c00;
        }

        .btn-aba.rotas {
            background-color: #6a1b9a;
        }

        .btn-aba.cidades {
            background-color: #c62828;
        }

        .btn-aba.viagens {
            background-color: #37474f;
        }

        /* Estado desativado para destacar qual aba está aberta */
        .btn-aba.opaco {
            opacity: 0.4;
        }

        /* Classe utilitária que esconde as seções que não estão ativas */
        .aba-conteudo {
            display: none;
        }

        .aba-conteudo.ativa {
            display: block;
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

<!-- Conteúdo principal da página : Perfil Analista de TI -->

<body>

    <div class="central-abas">
        <h3>⚙️ Central de Gerenciamento do Analista</h3>
        <p>Clique abaixo para alternar entre as tabelas de controle do sistema:</p>
        <div class="abas-botoes-container">
            <button class="btn-aba clientes" onclick="alternarAba('aba-clientes', this)">👤 Clientes Cadastrados</button>
            <button class="btn-aba usuarios opaco" onclick="alternarAba('aba-usuarios', this)">👥 Usuários Cadastrados</button>
            <button class="btn-aba veiculos opaco" onclick="alternarAba('aba-veiculos', this)">🚍 Frota de Veículos</button>
            <button class="btn-aba rotas opaco" onclick="alternarAba('aba-rotas', this)">🗺️ Grade de Rotas</button>
            <button class="btn-aba cidades opaco" onclick="alternarAba('aba-cidades', this)">🏙️ Cidades de Atendimento</button>
            <button class="btn-aba viagens opaco" onclick="alternarAba('aba-viagens', this)">📅 Viagens Programadas</button>
        </div>
    </div>

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

                    <!-- <a href="logout.php" class="btn-sair">Encerrar Sessão</a> -->
                    <form action="logout.php" method="POST" style="margin: 0; padding: 0;">
                        <button type="submit" class="btn-sair">
                            Encerrar Sessão
                        </button>
                    </form>
                </div>
            </aside>

            <!-- 1. BLOCO DE CLIENTES (Inicia ativo por padrão) -->
            <div id="aba-clientes" class="aba-conteudo ativa">
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
                                                <input type="hidden" name="cliente_id" value=<?= $cliente["id"] ?> />
                                                <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                            </form>
                                        </th>
                                        <th>
                                            <form name="excluir" action="crud_clientes.php" method="POST">
                                                <input type="hidden" name="cliente_id" value=<?= $cliente["id"] ?> />
                                                <input type="hidden" name="acao" value="excluir" />
                                                <button type="submit" name="btn-excluir" class="btn-excluir">Excluir</button>
                                            </form>
                                        </th>
                                    </tr>
                                <?php }
                                ?>
                            </tbody>
                        </table>
                </section>
            </div>

            <!-- 2. BLOCO DE USUÁRIOS -->
            <div id="aba-usuarios" class="aba-conteudo">
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
                                            <form action="crud_usuarios.php" method="POST">
                                                <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">                                                
                                                <input type="hidden" name="acao" value="excluir">
                                                <button type="submit" onclick="return confirm('Deseja mesmo excluir este usuário?')">
                                                    Excluir
                                                </button>
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

            <!-- 3. BLOCO DE VEÍCULOS -->
            <div id="aba-veiculos" class="aba-conteudo">
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
            </div>

            <!-- 4. BLOCO DE ROTAS -->
            <div id="aba-rotas" class="aba-conteudo">
                <section class="table-section">
                    <h3>📊 Grade de Rotas Autorizadas</h3>
                    <div class="table-placeholder">
                        <!-- Seu botão de Inserir Rota aqui -->
                        <form name="inserirRota" action="inserir_rota.php" method="POST">
                            <button type="submit" name="btn-inserir" class="btn-inserir">Inserir Nova Rota</button>
                        </form>

                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Identificação da Linha</th>
                                    <th>Cidade Origem</th>
                                    <th>Cidade Destino</th>
                                    <th>Tempo Viagem</th>
                                    <th>Preço Base</th>
                                    <th>Editar</th>
                                    <th>Excluir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rotas as $rota): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($rota["nome"]) ?></td>
                                        <td><?= htmlspecialchars($rota["origem"]) ?></td>
                                        <td><?= htmlspecialchars($rota["destino"]) ?></td>
                                        <td><?= htmlspecialchars($rota["tempo_viagem"]) ?></td>
                                        <td>R$ <?= number_format($rota["valor_base"], 2, ',', '.') ?></td>
                                        <th>
                                            <form name="alterar" action="alterar_rota.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $rota["id"] ?>" />
                                                <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                            </form>
                                        </th>
                                        <th>
                                            <form name="excluir" action="crud_rotas.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $rota["id"] ?>" />
                                                <input type="hidden" name="acao" value="excluir" />
                                                <button type="submit" name="btn-excluir" class="btn-excluir" onclick="return confirm('Deseja realmente excluir esta rota?')">Excluir</button>
                                            </form>
                                        </th>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- 5. BLOCO DE CIDADES -->
            <div id="aba-cidades" class="aba-conteudo">
                <section class="table-section">
                    <h3>📊 Cidades de Atendimento</h3>
                    <div class="table-placeholder">
                        <form name="inserirCidade" action="inserir_cidade.php" method="POST">
                            <button type="submit" name="btn-inserir" class="btn-inserir">Inserir Nova Cidade</button>
                        </form>

                        <table border="1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome da Cidade</th>
                                    <th>Estado (Por Extenso)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cidades as $cidade): ?>
                                    <tr>
                                        <td><?= $cidade["id"] ?></td>
                                        <td><?= htmlspecialchars($cidade["nome_cidade"]) ?></td>
                                        <td><?= htmlspecialchars($cidade["uf"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- 6. BLOCO DE VIAGENS PROGRAMADAS -->
            <div id="aba-viagens" class="aba-conteudo">
                <section class="table-section">
                    <h3>📊 Viagens Programadas</h3>
                    <div class="table-placeholder">
                        <form name="inserirViagem" action="inserir_viagem.php" method="POST">
                            <button type="submit" name="btn-inserir" class="btn-inserir">Programar Nova Viagem</button>
                        </form>

                        <table border="1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Rota / Linha</th>
                                    <th>Veículo (Modelo - Tipo)</th>
                                    <th>Data da Viagem</th>
                                    <th>Valor da Passagem</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($viagens)): ?>
                                    <tr>
                                        <td colspan="6" align="center">Nenhuma viagem programada no sistema.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($viagens as $viagem): ?>
                                        <tr>
                                            <td><?= $viagem["id"] ?></td>
                                            <td><?= htmlspecialchars($viagem["nome_rota"]) ?></td>
                                            <td><?= htmlspecialchars($viagem["modelo_veiculo"] . " (" . $viagem["tipo_veiculo"] . ")") ?></td>
                                            <td><?= date('d/m/Y', strtotime($viagem["data"])) ?></td>
                                            <td>R$ <?= number_format($viagem["valor"], 2, ',', '.') ?></td>
                                            <td>
                                                <div style="display: flex; gap: 5px;">
                                                    <form name="alterarViagem" action="alterar_viagem.php" method="POST" style="margin:0;">
                                                        <input type="hidden" name="id" value="<?= $viagem["id"] ?>" />
                                                        <button type="submit" name="btn-editar" class="btn-editar">Editar</button>
                                                    </form>
                                                    <form name="excluirViagem" action="crud_viagens.php" method="POST" style="margin:0;">
                                                        <input type="hidden" name="id" value="<?= $viagem["id"] ?>" />
                                                        <input type="hidden" name="acao" value="excluir" />
                                                        <button type="submit" name="btn-excluir" class="btn-excluir" onclick="return confirm('Deseja realmente apagar esta viagem?')">Excluir</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="page-footer">
        &copy; <?php echo date('Y'); ?> Vá com Deus - Todos os direitos reservados.
    </footer>

    <script>
        function alternarAba(idAbaDestino, botaoClicado) {
            // 1. Oculta todos os conteúdos das abas removendo a classe 'ativa'
            const conteudos = document.querySelectorAll('.aba-conteudo');
            conteudos.forEach(function(conteudo) {
                conteudo.classList.remove('ativa');
            });

            // 2. Torna opacos todos os botões de abas para desativá-los visualmente
            const botoes = document.querySelectorAll('.btn-aba');
            botoes.forEach(function(botao) {
                botao.classList.add('opaco');
            });

            // 3. Exibe o conteúdo correspondente à aba clicada
            document.getElementById(idAbaDestino).classList.add('ativa');

            // 4. Remove a opacidade do botão atual para destacá-lo
            botaoClicado.classList.remove('opaco');
        }
    </script>
</body>

</html>