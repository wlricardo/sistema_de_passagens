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

    // Verifica qual o perfil logado
    // Se perfil_id for 2 (Consultor de Vendas), vai para vendas.php. Se for 3 (Analista), vai para analista.php
    if (isset($_SESSION['perfil_id']) && $_SESSION['perfil_id'] == 2) {
        header("Location: vendas.php");
    } else {
        header("Location: analista.php");
    }
    exit();
}

function excluirCliente()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $banco = abrirBanco();

    // Captura apenas o nome correto e limpo do campo
    $id_cliente = $banco->real_escape_string($_POST["cliente_id"]);

    // ETAPA 1: Remove as reservas vinculadas a este cliente
    $sql_reservas = "DELETE FROM reserva WHERE cliente_id = '{$id_cliente}'";
    $banco->query($sql_reservas);

    // ETAPA 2: Remove o cliente do sistema
    $sql_cliente = "DELETE FROM cliente WHERE id = '{$id_cliente}'";
    $banco->query($sql_cliente);

    $banco->close();

    // Redirecionamento baseado no perfil logado
    $perfil = isset($_SESSION['perfil_id']) ? $_SESSION['perfil_id'] : 3;
    $pagina_retorno = ($perfil == 2) ? "vendas.php" : "analista.php";

    header("Location: " . $pagina_retorno);
    exit();
}

function selecionarClienteId($id)
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

    // Captura e limpa os dados textuais enviados pelo formulário
    $id    = $banco->real_escape_string($_POST["id"]);
    $nome  = $banco->real_escape_string($_POST["nome"]);
    $cpf   = $banco->real_escape_string($_POST["cpf"]);
    $email = $banco->real_escape_string($_POST["email"]);
    $login = $banco->real_escape_string($_POST["login"]);

    // Captura a senha sem limpar ainda, para testar se está vazia
    $senha_digitada = $_POST["senha"];

    // Evitar que atualize a senha para um valor vazio
    if (empty($senha_digitada)) { // Se estiver vazia, atualiza TODOS os campos, EXCETO a senha
        $sql = "UPDATE cliente SET 
                       nome='$nome', cpf='$cpf', email='$email', login='$login'
                WHERE id='$id'";
    } else { // Se digitou algo, limpa o texto e aplica a criptografia MD5        
        $senha_cripto = md5($banco->real_escape_string($senha_digitada));

        // Inclui a nova senha criptografada na consulta SQL
        $sql = "UPDATE cliente SET 
                       nome='$nome', cpf='$cpf', email='$email', login='$login', senha='$senha_cripto'
                WHERE id='$id'";
    }

    $banco->query($sql);
    $banco->commit();
    $banco->close();

    // Redireciona de volta à página de vendas, para mostrar a lista atualizada de clientes
    /*$retorno = (isset($_POST['origem']) && $_POST['origem'] == 'vendas.php') ? 'vendas.php' : 'analista.php';
    header("Location: " . $retorno);
    exit();*/
    $pagina_retorno = ($_SESSION['perfil_id'] == 2) ? "vendas.php" : "analista.php";
    header("Location: " . $pagina_retorno);
    exit();
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