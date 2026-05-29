<?php
require_once 'conexao.php';

// 1. BLOCO DE ROTEAMENTO (Tratamento dos formulários)
if (isset($_POST['acao'])) {
    if ($_POST['acao'] == 'inserir') {
        inserirReserva();
    }
    // No futuro, rotas de alterar/excluir reservas entrarão aqui se necessário
}

// 2. FUNÇÃO QUE INSERE A RESERVA E GERA O BILHETE
function inserirReserva()
{
    $banco = abrirBanco();

    $usuario_id      = $banco->real_escape_string($_POST['usuario_id']);
    $cliente_id      = $banco->real_escape_string($_POST['cliente_id']);
    $viagem_id       = $banco->real_escape_string($_POST['viagem_id']);
    $forma_pagamento = $banco->real_escape_string($_POST['forma_pagamento']);
    $valor_pago      = $banco->real_escape_string($_POST['valor_final']);
    $data_atual      = date('Y-m-d H:i:s');

    // Regra das parcelas: se for cartão, pega do POST. Se for à vista, define como 1.
    $parcelas = ($forma_pagamento == 'cartao') ? $banco->real_escape_string($_POST['parcelas']) : 1;

    $sql = "INSERT INTO reserva (usuario_id, cliente_id, viagem_id, data, forma_pagamento, valor_pago, parcelas) 
            VALUES ('$usuario_id', '$cliente_id', '$viagem_id', '$data_atual', '$forma_pagamento', '$valor_pago', '$parcelas')";

    if ($banco->query($sql)) {
        $reserva_id = $banco->insert_id;

        // Busca dados detalhados para o layout do bilhete
        $sql_dados = "SELECT r.nome AS rota_nome, v.data AS viagem_data, c.nome AS cliente_nome, c.cpf AS cliente_cpf, u.nome AS vendedor_nome
                      FROM viagem v
                      INNER JOIN rota r ON v.rota_id = r.id
                      INNER JOIN cliente c ON c.id = '$cliente_id'
                      INNER JOIN usuario u ON u.id = '$usuario_id'
                      WHERE v.id = '$viagem_id'";

        $res_dados = $banco->query($sql_dados);
        $dados = $res_dados->fetch_assoc();
        $banco->close();

?>
        <!DOCTYPE html>
        <html lang="pt-br">

        <head>
            <meta charset="UTF-8">
            <title>Bilhete de Passagem Eletrônico</title>
            <style>
                .bilhete-box {
                    border: 2px dashed #000;
                    padding: 20px;
                    width: 450px;
                    margin: 30px auto;
                    font-family: monospace;
                    background: #fffde7;
                }

                .topo {
                    text-align: center;
                    border-bottom: 1px solid #000;
                    padding-bottom: 10px;
                }

                .item {
                    margin: 8px 0;
                }

                .rodape {
                    border-top: 1px solid #000;
                    padding-top: 10px;
                    text-align: center;
                    margin-top: 15px;
                }

                @media print {
                    .btn-print {
                        display: none;
                    }
                }
            </style>
        </head>

        <body>

            <div class="bilhete-box">
                <div class="topo">
                    <h2>VÁ COM DEUS RODOVIÁRIO</h2>
                    <strong>BILHETE DE PASSAGEM ELETRÔNICO</strong><br>
                    Nº CONTROLE: #<?= $reserva_id ?>
                </div>

                <div class="item"><strong>PASSAGEIRO:</strong> <?= htmlspecialchars($dados['cliente_nome']) ?></div>
                <div class="item"><strong>CPF:</strong> <?= htmlspecialchars($dados['cliente_cpf']) ?></div>
                <hr>
                <div class="item"><strong>LINHA:</strong> <?= htmlspecialchars($dados['rota_nome']) ?></div>
                <div class="item"><strong>EMBARQUE:</strong> <?= date('d/m/Y', strtotime($dados['viagem_data'])) ?></div>
                <hr>
                <div class="item"><strong>PAGAMENTO:</strong> <?= strtoupper($forma_pagamento) ?></div>
                <div class="item">
                    <strong>CONDIÇÃO:</strong>
                    <?php if ($forma_pagamento == 'cartao'): ?>
                        <?= $parcelas ?>x de R$ <?= number_format($valor_pago / $parcelas, 2, ',', '.') ?>
                    <?php else: ?>
                        À Vista (10% de Desconto incluso)
                    <?php endif; ?>
                </div>
                <div class="item" style="font-size: 16px;"><strong>VALOR TOTAL: R$ <?= number_format($valor_pago, 2, ',', '.') ?></strong></div>

                <div class="rodape">
                    <p>Emitido por: <?= htmlspecialchars($dados['vendedor_nome']) ?><br>Em: <?= date('d/m/Y H:i') ?></p>
                    <p><strong>BOA VIAGEM! VÁ COM DEUS!</strong></p>

                    <button class="btn-print" onclick="window.print()">Imprimir Bilhete</button>
                    <a href="vendas.php" class="btn-print" style="margin-left:10px;">Voltar ao Painel</a>
                </div>
            </div>

        </body>

        </html>
<?php
    } else {
        echo "Erro ao processar reserva: " . $banco->error;
        $banco->close();
    }
}

// 3. FUNÇÃO QUE BUSCA O HISTÓRICO PARA OS PAINÉIS (Vendas e Gerente)
function listarHistoricoReservas($usuario_id = null)
{
    $banco = abrirBanco();
    $reservas = [];

    $sql = "SELECT res.id AS reserva_id, res.data AS data_venda, res.forma_pagamento, res.valor_pago, res.parcelas,
                   c.nome AS nome_cliente,
                   r.nome AS nome_rota,
                   v.data AS data_viagem,
                   u.nome AS nome_vendedor
            FROM reserva res
            INNER JOIN cliente c ON res.cliente_id = c.id
            INNER JOIN viagem v ON res.viagem_id = v.id
            INNER JOIN rota r ON v.rota_id = r.id
            INNER JOIN usuario u ON res.usuario_id = u.id";

    if ($usuario_id !== null) {
        $usuario_id = $banco->real_escape_string($usuario_id);
        $sql .= " WHERE res.usuario_id = '$usuario_id'";
    }

    $sql .= " ORDER BY res.data DESC";

    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $reservas[] = $row;
    }

    $banco->close();
    return $reservas;
}
?>