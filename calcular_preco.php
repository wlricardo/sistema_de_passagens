<?php
require_once 'conexao.php';

if (isset($_GET['rota_id']) && isset($_GET['veiculo_id'])) {
    $banco = abrirBanco();

    $rota_id    = $banco->real_escape_string($_GET['rota_id']);
    $veiculo_id = $banco->real_escape_string($_GET['veiculo_id']);

    // 1. Busca o valor base da rota
    $sql_rota = "SELECT valor_base FROM rota WHERE id = '$rota_id'";
    $res_rota = $banco->query($sql_rota);
    $v_base = ($res_rota && $l_rota = $res_rota->fetch_assoc()) ? floatval($l_rota['valor_base']) : 0;

    // 2. Busca o tipo do veículo para aplicar o multiplicador
    $sql_vei = "SELECT tipo FROM veiculo WHERE id = '$veiculo_id'";
    $res_vei = $banco->query($sql_vei);
    $tipo = ($res_vei && $l_vei = $res_vei->fetch_assoc()) ? $l_vei['tipo'] : 'Convencional';

    // 3. Aplica o critério de cálculo baseado no tipo
    $multiplicador = 1.0;
    if ($tipo == 'Leito') {
        $multiplicador = 1.3;
    } elseif ($tipo == 'Semi-Leito') {
        $multiplicador = 1.6;
    }

    // Calcula o valor final da passagem
    $valor_final = $v_base * $multiplicador;

    // Retorna apenas o número formatado para o JavaScript ler
    echo number_format($valor_final, 2, '.', '');

    $banco->close();
}