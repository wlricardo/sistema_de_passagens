<?php
require_once 'conexao.php';

if (isset($_POST["acao"])) {
    if ($_POST["acao"] == "inserir") {
        inserirCliente();
    }
    if ($_POST["acao"] == "alterar") {
        alterarCliente();
    }
    if ($_POST["acao"] == "excluir") {
        excluirCliente();
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

function inserirCliente()
{
    $banco = abrirBanco();

    $nome = $banco->real_escape_string($_POST["nome"]);
    $cpf = $banco->real_escape_string($_POST["cpf"]);   
    $email = $banco->real_escape_string($_POST["email"]);
    $login = $banco->real_escape_string($_POST["login"]);
    $senha = $banco->real_escape_string($_POST["senha"]);
        $sql = "INSERT INTO cliente(nome, cpf, email, login, senha)" . " VALUES 
                ('$nome','$cpf','$email','$login','$senha')";   

    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}

function excluirCliente()
{
    $banco = abrirBanco();
    $sql = "delete from cliente where id='{$_POST["id"]}'";
    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}

function SelecionarClienteId($id)
{
    $banco = abrirBanco();
    $sql = "select * from cliente where id=" . $id;
    $resultado = $banco->query($sql);
    $cliente = mysqli_fetch_assoc($resultado);
    return $cliente;
}

function alterarCliente()
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

function listarClientes()
{
    $clientes_list = [];
    $banco = abrirBanco();
    $sql = "select * from cliente order by nome";
    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $clientes_list[] = $row;
    }
    return $clientes_list;
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
*/


/*function voltarIndex()
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
