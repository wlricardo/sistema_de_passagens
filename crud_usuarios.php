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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $banco = abrirBanco();
    // Força o PHP a nos mostrar qualquer erro do banco imediatamente
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $id_usuario = $banco->real_escape_string($_POST["usuario_id"]);

        if (empty($id_usuario)) {
            throw new Exception("O ID do usuário chegou vazio no PHP. Verifique o HTML.");
        }

        // 1. Busca o perfil do usuário
        $sql_busca = "SELECT perfil_id FROM usuario WHERE id = '{$id_usuario}'";
        $resultado = $banco->query($sql_busca);
        $usuario_alvo = $resultado->fetch_assoc();

        if (!$usuario_alvo) {
            throw new Exception("Usuário com ID {$id_usuario} não foi encontrado no banco de dados.");
        }

        $perfil_alvo = $usuario_alvo['perfil_id'];

        if ($perfil_alvo == 2) {
            // Tenta desvincular as reservas do vendedor
            try {
                $sql_preservar = "UPDATE reserva SET usuario_id = NULL WHERE usuario_id = '{$id_usuario}'";
                $banco->query($sql_preservar);
            } catch (mysqli_sql_exception $e) {
                // Se cair aqui, a sua tabela 'reserva' não aceita valores NULL na coluna usuario_id
                throw new Exception("Erro de Banco de Dados: A coluna 'usuario_id' na tabela 'reserva' precisa aceitar valores NULOS (NULL) para podermos preservar o histórico de vendas. Vá no phpMyAdmin, mude a estrutura da tabela 'reserva', marque a coluna usuario_id como 'Nulo/Null' e tente novamente.");
            }
        } else {
            // Remove registros administrativos se houverem
            $sql_limpar = "DELETE FROM reserva WHERE usuario_id = '{$id_usuario}'";
            $banco->query($sql_limpar);
        }

        // 2. Exclui o usuário definitivamente
        $sql_deletar = "DELETE FROM usuario WHERE id = '{$id_usuario}'";
        $banco->query($sql_deletar);
        
        $banco->close();
        
        header("Location: analista.php");
        exit();

    } catch (Exception $e) {
        echo "<h3>[Vá com Deus] Erro no fluxo de exclusão:</h3>";
        echo "<p style='color:red; font-weight:bold;'>" . $e->getMessage() . "</p>";
        echo "<br><a href='analista.php'>Voltar para o Painel</a>";
        exit();
    }
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
