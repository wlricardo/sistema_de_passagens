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
    if ($_POST["acao"] == "cancelar") {
        voltarLoginAnalista();
    }
}

/*function inserirUsuario()
{
    $banco = abrirBanco();

    $nome = $banco->real_escape_string($_POST["nome"]);
    $cpf = $banco->real_escape_string($_POST["cpf"]);
    $email = $banco->real_escape_string($_POST["email"]);
    $login = $banco->real_escape_string($_POST["login"]);
    $senha = md5($banco->real_escape_string($_POST["senha"]));

    $sql = "INSERT INTO usuario(nome, cpf, email, login, senha)" . " VALUES 
            ('$nome','$cpf','$email','$login','$senha')";

    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}*/

function inserirUsuario()
{
    $banco = abrirBanco();

    // Captura e limpa os campos da tabela usuario
    $nome      = $banco->real_escape_string($_POST["nome"]);
    $login     = $banco->real_escape_string($_POST["login"]);
    $senha     = md5($banco->real_escape_string($_POST["senha"]));
    
    // Captura o ID do perfil selecionado no menu suspenso (1, 2 ou 3)
    $perfil_id = $banco->real_escape_string($_POST["perfil_id"]);

    // CORREÇÃO: SQL ajustado com as colunas corretas da tabela usuario
    $sql = "INSERT INTO usuario (nome, login, senha, perfil_id) 
            VALUES ('$nome', '$login', '$senha', '$perfil_id')";

    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}


function excluirUsuario()
{
    $banco = abrirBanco();
    $sql = "delete from usuario where id='{$_POST["id"]}'";
    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}

function selecionarUsuarioId($id)
{
    $banco = abrirBanco();
    $sql = "select * from usuario where id=" . $id;
    $resultado = $banco->query($sql);
    $usuario = mysqli_fetch_assoc($resultado);
    return $usuario;
}

function alterarUsuario()
{
    $banco = abrirBanco();

    // Captura e limpa os dados textuais enviados pelo formulário
    $id    = $banco->real_escape_string($_POST["id"]);
    $nome  = $banco->real_escape_string($_POST["nome"]);
    $login = $banco->real_escape_string($_POST["login"]);

    // Captura a senha sem limpar ainda, para testar se está vazia
    $senha_digitada = $_POST["senha"];

    // Evitar que atualize a senha para um valor vazio
    if (empty($senha_digitada)) { // Se estiver vazia, atualiza TODOS os campos, EXCETO a senha
        $sql = "UPDATE usuario SET 
                       nome='$nome', login='$login'
                WHERE id='$id'";
    } else { // Se digitou algo, limpa o texto e aplica a criptografia MD5        
        $senha_cripto = md5($banco->real_escape_string($senha_digitada));

        // Inclui a nova senha criptografada na consulta SQL
        $sql = "UPDATE usuario SET 
                       nome='$nome', login='$login', senha='$senha_cripto'
                WHERE id='$id'";
    }

    $banco->query($sql);
    $banco->close();
    voltarLoginAnalista();
}

/*function listarUsuarios()
{
    $usuarios_list = [];
    $banco = abrirBanco();
    $sql = "select * from usuario order by nome";
    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $usuarios_list[] = $row;
    }
    return $usuarios_list;
}*/

function listarUsuarios()
{
    $usuarios_list = [];
    $banco = abrirBanco();
    
    // Exibe lista de usuários com o nome do perfil, usando JOIN para relacionar as tabelas usuario e perfil
    $sql = "SELECT usuario.*, perfil.nome AS nome_perfil 
            FROM usuario 
            INNER JOIN perfil ON usuario.perfil_id = perfil.id 
            ORDER BY usuario.nome";
            
    $resultado = $banco->query($sql);
    
    while ($row = mysqli_fetch_array($resultado)) {
        $usuarios_list[] = $row;
    }
    
    $banco->close();
    return $usuarios_list;
}