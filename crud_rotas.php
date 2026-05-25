<?php
require_once 'conexao.php';

if (isset($_POST["acao"])) {
    if ($_POST["acao"] == "inserir") { inserirRota(); }
    if ($_POST["acao"] == "alterar") { alterarRota(); }
    if ($_POST["acao"] == "excluir") { excluirRota(); }
}

function inserirRota() {
    $banco = abrirBanco();
    $nome = $banco->real_escape_string($_POST["nome"]);
    $origem = $banco->real_escape_string($_POST["cidade_origem_id"]);
    $destino = $banco->real_escape_string($_POST["cidade_destino_id"]);
    $tempo = $banco->real_escape_string($_POST["tempo_viagem"]);
    $valor = $banco->real_escape_string($_POST["valor_base"]);

    $sql = "INSERT INTO rota (nome, cidade_origem_id, cidade_destino_id, tempo_viagem, valor_base) 
            VALUES ('$nome', '$origem', '$destino', '$tempo', '$valor')";
    $banco->query($sql);
    $banco->close();
    header("Location:analista.php");
}

function alterarRota() {
    $banco = abrirBanco();
    $id = $banco->real_escape_string($_POST["id"]);
    $nome = $banco->real_escape_string($_POST["nome"]);
    $origem = $banco->real_escape_string($_POST["cidade_origem_id"]);
    $destino = $banco->real_escape_string($_POST["cidade_destino_id"]);
    $tempo = $banco->real_escape_string($_POST["tempo_viagem"]);
    $valor = $banco->real_escape_string($_POST["valor_base"]);

    $sql = "UPDATE rota SET nome='$nome', cidade_origem_id='$origem', cidade_destino_id='$destino', tempo_viagem='$tempo', valor_base='$valor' WHERE id='$id'";
    $banco->query($sql);
    $banco->close();
    header("Location:analista.php");
}

function excluirRota() {
    $banco = abrirBanco();
    $id = $banco->real_escape_string($_POST["id"]);
    $sql = "DELETE FROM rota WHERE id='$id'";
    $banco->query($sql);
    $banco->close();
    header("Location:analista.php");
}

function selecionarRotaId($id) {
    $banco = abrirBanco();
    $sql = "SELECT * FROM rota WHERE id=$id";
    $resultado = $banco->query($sql);
    $rota = mysqli_fetch_assoc($resultado);
    $banco->close();
    return $rota;
}

function listarRotas() {
    $rotas = [];
    $banco = abrirBanco();
    // INNER JOIN duplo para conseguir trazer de forma correta o nome legível da cidade de origem e destino
    $sql = "SELECT r.*, o.nome AS origem, d.nome AS destino 
            FROM rota r
            INNER JOIN cidade o ON r.cidade_origem_id = o.id
            INNER JOIN cidade d ON r.cidade_destino_id = d.id
            ORDER BY r.nome";
    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) { $rotas[] = $row; }
    $banco->close();
    return $rotas;
}
