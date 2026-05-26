<?php
require_once 'conexao.php';

function listarViagens() {
    $viagens = [];
    $banco = abrirBanco();
    
    // Relaciona a tabela 'viagem' com as chaves estrangeiras das tabelas 'rota' e 'veiculo'
    $sql = "SELECT v.id, v.data, v.valor, 
                   r.nome AS nome_rota, 
                   vei.modelo AS modelo_veiculo, vei.tipo AS tipo_veiculo
            FROM viagem v
            INNER JOIN rota r ON v.rota_id = r.id
            INNER JOIN veiculo vei ON v.veiculo_id = vei.id
            ORDER BY v.data ASC";
            
    $resultado = $banco->query($sql);
    
    while ($row = mysqli_fetch_array($resultado)) {
        $viagens[] = $row;
    }
    
    $banco->close();
    return $viagens;
}
