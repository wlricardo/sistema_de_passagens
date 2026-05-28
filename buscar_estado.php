<?php
require_once 'conexao.php';

if (isset($_GET['cidade_id'])) {
    $banco = abrirBanco();
    $cidade_id = $banco->real_escape_string($_GET['cidade_id']);

    // Consulta o nome do estado
    $sql = "SELECT e.nome AS nome_estado 
            FROM cidade c 
            INNER JOIN estado e ON c.estado_id = e.id 
            WHERE c.id = '$cidade_id'";

    $resultado = $banco->query($sql);
    
    if ($resultado && $linha = $resultado->fetch_assoc()) {
        echo $linha['nome_estado'];
    } else {
        echo "Não localizado";
    }
    
    $banco->close();
}
?>
