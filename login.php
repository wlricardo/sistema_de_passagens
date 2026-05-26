<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "sistema_venda_passagens";

$connect = mysqli_connect($servername, $username, $password, $db_name);

if (mysqli_connect_error()) {
    $_SESSION['erro'] = "Falha na conexao: " . mysqli_connect_error();
    header('location:index.php');
    exit();
}

if (isset($_POST['btn-entrar'])) {
    $login = mysqli_real_escape_string($connect, $_POST['login']);
    $senha = md5(mysqli_real_escape_string($connect, $_POST['senha']));

    if (empty($login) or empty($senha)) {
        $_SESSION['erro'] = 'Favor preencher os dados';
        header('location:index.php');
        exit();
    } else {
        //$senha_hash = md5($senha);
        $sql = "SELECT u.*, p.nome AS nome_perfil FROM usuario u 
                INNER JOIN perfil p ON u.perfil_id = p.id 
                WHERE u.login = '$login' AND u.senha = '$senha'";
        // $sql = "select * from usuario where login = '$login' and senha = '$senha'";

        $resultado = mysqli_query($connect, $sql);

        if (mysqli_num_rows($resultado) == 1) {
            $dados = mysqli_fetch_array($resultado);

            $_SESSION['logado'] = true;
            $_SESSION["id_usuario"] = $dados['id'];
            $_SESSION["perfil_usuario"] = $dados['nome_perfil']; // Guarda o nome do perfil na sessão

            // Direcionamento baseado no nome do perfil cadastrado no banco
            if ($dados['nome_perfil'] == 'Gerente') {
                header('location:gerente.php');
            } elseif ($dados['nome_perfil'] == 'Consultor de Vendas') {
                header('location:vendas.php');
            } elseif ($dados['nome_perfil'] == 'Analista de TI') {
                header('location:analista.php');
            } else {
                $_SESSION['erro'] = "Perfil não reconhecido no sistema.";
                header('location:index.php');
            }
            exit();
        } else {
            $_SESSION['erro'] = "Login ou senha inválidos";
            header('location:index.php');
            exit();
        }
    }
}
