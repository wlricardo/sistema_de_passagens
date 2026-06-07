<?php
// 1. Verifica se a sessão já está ativa antes de tentar iniciar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    if ($_POST["acao"] == "cancelar") {
        header("Location: vendas.php");
        exit();
    }
}

function inserirCliente()
{
    $banco = abrirBanco();
    $nome  = $banco->real_escape_string($_POST["nome"]);
    $cpf   = $banco->real_escape_string($_POST["cpf"]);
    $email = $banco->real_escape_string($_POST["email"]);
    $login = $banco->real_escape_string($_POST["login"]);
    $senha = md5($banco->real_escape_string($_POST["senha"]));

    $sql = "INSERT INTO cliente(nome, cpf, email, login, senha)
            VALUES ('$nome', '$cpf', '$email', '$login', '$senha')";
    $banco->query($sql);
    $banco->commit();
    $banco->close();

    // Redirecionamento baseado no perfil
    $perfil = isset($_SESSION['perfil_id']) ? (int)$_SESSION['perfil_id'] : 3;
    $pagina_retorno = ($perfil == 2) ? "vendas.php" : "analista.php";

    header("Location: " . $pagina_retorno);
    exit();
}

function excluirCliente()
{
    $banco = abrirBanco();
    $id_cliente = $banco->real_escape_string($_POST["cliente_id"]);

    $sql_reservas = "DELETE FROM reserva WHERE cliente_id = '{$id_cliente}'";
    $banco->query($sql_reservas);

    $sql_cliente = "DELETE FROM cliente WHERE id = '{$id_cliente}'";
    $banco->query($sql_cliente);
    $banco->close();

    // Redirecionamento baseado no perfil
    $perfil = isset($_SESSION['perfil_id']) ? (int)$_SESSION['perfil_id'] : 3;
    $pagina_retorno = ($perfil == 2) ? "vendas.php" : "analista.php";

    header("Location: " . $pagina_retorno);
    exit();
}

function selecionarClienteId($id)
{
    $banco = abrirBanco();
    // Garante que o ID seja um número inteiro para evitar erros de SQL
    $id = (int)$id;
    $sql = "SELECT * FROM cliente WHERE id = $id";
    $resultado = $banco->query($sql);

    if ($resultado) {
        return mysqli_fetch_assoc($resultado);
    }
    return null;
}

function alterarCliente()
{
    $banco = abrirBanco();
    $id    = $banco->real_escape_string($_POST["id"]);
    $nome  = $banco->real_escape_string($_POST["nome"]);
    $cpf   = $banco->real_escape_string($_POST["cpf"]);
    $email = $banco->real_escape_string($_POST["email"]);
    $login = $banco->real_escape_string($_POST["login"]);

    $senha_digitada = $_POST["senha"];

    if (empty($senha_digitada)) {
        $sql = "UPDATE cliente SET
                nome='$nome', cpf='$cpf', email='$email', login='$login'
                WHERE id='$id'";
    } else {
        $senha_cripto = md5($banco->real_escape_string($senha_digitada));
        $sql = "UPDATE cliente SET
                nome='$nome', cpf='$cpf', email='$email', login='$login', senha='$senha_cripto'
                WHERE id='$id'";
    }

    $banco->query($sql);
    $banco->commit();
    $banco->close();

    // Redirecionamento baseado no perfil (Corrigido para evitar erros de variável indefinida)
    $perfil = isset($_SESSION['perfil_id']) ? (int)$_SESSION['perfil_id'] : 3;
    $pagina_retorno = ($perfil == 2) ? "vendas.php" : "analista.php";

    header("Location: " . $pagina_retorno);
    exit();
}

function listarClientes()
{
    $clientes_list = [];
    $banco = abrirBanco();
    $sql = "SELECT * FROM cliente ORDER BY nome";
    $resultado = $banco->query($sql);

    if ($resultado) {
        while ($row = mysqli_fetch_array($resultado)) {
            $clientes_list[] = $row;
        }
    }
    return $clientes_list;
}
