<?php
require_once 'conexao.php';

if (isset($_POST["acao"])) {
    if ($_POST["acao"] == "inserir") {
        inserirUsuario();
    }
    if ($_POST["acao"] == "alterar") {
        alterarUsuario();
    }
    if ($_POST["acao"] == "excluir") {
        excluirUsuario();
    }
}

/* function abrirBanco()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "sistema_venda_passagens";

    $conexao = new mysqli($servername, $username, $password, $db_name);
    return $conexao;
}*/

function inserirUsuario()
{
    $banco = abrirBanco();
    $sql = "INSERT INTO cliente(id, nome, cpf, email, login, senha)" . " VALUES 
            ({$_POST["nome"]}','{$_POST["cpf"]}','{$_POST["email"]}',
            '{$_POST["login"]}','{$_POST["senha"]}')";

    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}

function excluirUsuario()
{
    $banco = abrirBanco();
    $sql = "delete from cliente where id='{$_POST["id"]}'";
    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}

function SelecionarUsuarioId($id)
{
    $banco = abrirBanco();
    $sql = "select * from cliente where id=" . $id;
    $resultado = $banco->query($sql);
    $cliente = mysqli_fetch_assoc($resultado);
    return $cliente;
}

function alterarUsuario()
{
    $banco = abrirBanco();
    $sql = "UPDATE cliente SET 
                   nome='{$_POST["nome"]}', cpf='{$_POST["cpf"]}', email='{$_POST["email"]}',
                   login='{$_POST["login"]}', senha='{$_POST["senha"]}'
            WHERE id='{$_POST["id"]}'";

    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}

/*function listarUsuarios()
{
    $usuarios_list = [];
    $banco = abrirBanco();
    $sql = "select * from cliente order by nome";
    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $usuarios_list[] = $row;
    }
    return $usuarios_list;
}*/

function listarUsuarios() {
    $usuarios_list = [];
    $connect = abrirBanco();
    $sql = "SELECT usuario.*, perfil.nome AS nome_perfil 
            FROM usuario 
            INNER JOIN perfil ON usuario.perfil_id = perfil.id";
    $resultado = mysqli_query($connect, $sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $usuarios_list[] = $row;
    }
    return $usuarios_list;
}


/*
function buscarConsumoAcimaDaMedia()
{
    $banco = abrirBanco();
    // O SQL compara o consumo de cada cliente com a média calculada na hora
    $sql = "SELECT * FROM cliente 
            WHERE kwhConsumido > " . calcularMediaDeConsumo() . " ORDER BY kwhConsumido DESC";

    return $banco->query($sql);
}

function calcularMediaDeConsumo()
{
    $banco = abrirBanco();
    $sql = "SELECT AVG(kwhConsumido) as media FROM cliente";
    $resultado = $banco->query($sql);
    $linha = $resultado->fetch_assoc();
    return $linha['media'];
}

function totalCliente()
{
    $banco = abrirBanco();
    $sql = "SELECT COUNT(*) as total FROM cliente";
    $resultado = $banco->query($sql);
    $linha = $resultado->fetch_assoc();
    return $linha['total'];
}

function voltarIndex()
{
    header("Location:index.php");
}

function voltarConectado()
{
    header("Location:conectado.php");
}

function voltarLoginAnalista()
{
    header("Location:analista.php");
}*/
