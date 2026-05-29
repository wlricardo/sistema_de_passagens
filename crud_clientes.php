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
    $senha = md5($banco->real_escape_string($_POST["senha"]));

    $sql = "INSERT INTO cliente(nome, cpf, email, login, senha)" . " VALUES 
            ('$nome','$cpf','$email','$login','$senha')";

    $banco->query($sql);
    $banco->close();

    // Se o perfil logado for o 2 (Vendas), vai para vendas.php, senão volta para analista.php
    $pagina_retorno = ($_SESSION['perfil_id'] == 2) ? "vendas.php" : "analista.php";
    header("Location: vendas.php");
    exit();;
}

function excluirCliente()
{
    $banco = abrirBanco();
    $sql = "delete from cliente where id='{$_POST["id"]}'";
    $banco->query($sql);
    $banco->close();

    // Se o perfil logado for o 2 (Vendas), vai para vendas.php, senão volta para analista.php
    $pagina_retorno = ($_SESSION['perfil_id'] == 2) ? "vendas.php" : "analista.php";
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
