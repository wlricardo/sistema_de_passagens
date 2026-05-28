<?php
require_once 'conexao.php';

if (isset($_POST["acao"])) {
    if ($_POST["acao"] == "inserir") {
        inserirCidade();
    }
}

function inserirCidade()
{
    $banco = abrirBanco();

    // Captura o nome da cidade e o ID numérico do estado selecionado
    $nome      = $banco->real_escape_string($_POST["nome"]);
    $estado_id = $banco->real_escape_string($_POST["estado_id"]);

    // O SQL agora insere o relacionamento correto usando a chave estrangeira estado_id
    $sql = "INSERT INTO cidade (nome, estado_id) VALUES ('$nome', '$estado_id')";

    $banco->query($sql);
    $banco->close();

    header("Location:analista.php");
    exit();
}

function listarCidades()
{
    $cidades = [];
    $banco = abrirBanco();

    //Seleciona estado por extenso e dá o apelido 'uf'
    $sql = "SELECT c.id, c.nome AS nome_cidade, e.nome AS uf 
            FROM cidade c 
            INNER JOIN estado e ON c.estado_id = e.id 
            ORDER BY e.nome ASC";

    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $cidades[] = $row;
    }
    $banco->close();
    return $cidades;
}
