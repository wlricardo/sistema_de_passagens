<?php
require_once 'conexao.php';

// Verifica se a requisição foi feita direto para este arquivo (POST do formulário)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["acao"]) && basename($_SERVER['PHP_SELF']) == 'crud_veiculos.php') {
    if ($_POST["acao"] == "inserir") { 
        inserirVeiculo(); 
    }
    if ($_POST["acao"] == "alterar") { 
        alterarVeiculo(); 
    }
    if ($_POST["acao"] == "excluir") { 
        excluirVeiculo(); 
    }
}

function inserirVeiculo() {
    $banco = abrirBanco();
    $marca = $banco->real_escape_string($_POST["marca"]);
    $modelo = $banco->real_escape_string($_POST["modelo"]);
    $poltrona = $banco->real_escape_string($_POST["poltrona"]);
    $tipo = $banco->real_escape_string($_POST["tipo"]);

    $sql = "INSERT INTO veiculo (marca, modelo, poltrona, tipo) VALUES ('$marca', '$modelo', '$poltrona', '$tipo')";
    $banco->query($sql);
    $banco->close();
    header("Location:analista.php");
    exit();
}

function alterarVeiculo() {
    $banco = abrirBanco();
    $id = $banco->real_escape_string($_POST["id"]);
    $marca = $banco->real_escape_string($_POST["marca"]);
    $modelo = $banco->real_escape_string($_POST["modelo"]);
    $poltrona = $banco->real_escape_string($_POST["poltrona"]);
    $tipo = $banco->real_escape_string($_POST["tipo"]);

    $sql = "UPDATE veiculo SET marca='$marca', modelo='$modelo', poltrona='$poltrona', tipo='$tipo' WHERE id='$id'";
    $banco->query($sql);
    $banco->close();
    header("Location:analista.php");
    exit();
}

function excluirVeiculo() {
    $banco = abrirBanco();
    $id = $banco->real_escape_string($_POST["id"]);
    $sql = "DELETE FROM veiculo WHERE id='$id'";
    $banco->query($sql);
    $banco->close();
    header("Location:analista.php");
    exit();
}

function selecionarVeiculoId($id) {
    $banco = abrirBanco();
    $sql = "SELECT * FROM veiculo WHERE id=$id";
    $resultado = $banco->query($sql);
    $veiculo = mysqli_fetch_assoc($resultado);
    $banco->close();
    return $veiculo;
}

function listarVeiculos() {
    $veiculos = [];
    $banco = abrirBanco();
    $sql = "SELECT * FROM veiculo ORDER BY modelo";
    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) { $veiculos[] = $row; }
    $banco->close();
    return $veiculos;
}
