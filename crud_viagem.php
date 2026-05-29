<?php
require_once 'conexao.php';

// Bloco de Roteamento de Ações
if (isset($_POST["acao"])) {
    if ($_POST["acao"] == "inserir") {
        inserirViagem();
    }
    if ($_POST["acao"] == "alterar") {
        alterarViagem();
    }
    if ($_POST["acao"] == "excluir") {
        excluirViagem();
    }
    if ($_POST["acao"] == "cancelar") {
        header("Location:analista.php");
        exit();
    }
}

function inserirViagem()
{
    $banco = abrirBanco();

    // Captura e limpa os dados enviados pelo formulário
    $rota_id    = $banco->real_escape_string($_POST["rota_id"]);
    $veiculo_id = $banco->real_escape_string($_POST["veiculo_id"]);
    $data       = $banco->real_escape_string($_POST["data"]);
    $valor      = $banco->real_escape_string($_POST["valor"]);

    // Insere os registros na tabela viagem
    $sql = "INSERT INTO viagem (data, valor, rota_id, veiculo_id) 
            VALUES ('$data', '$valor', '$rota_id', '$veiculo_id')";

    $banco->query($sql);
    $banco->close();

    // Retorna ao perfil de analista com a nova viagem inserida
    header("Location:analista.php");
    exit();
}

function alterarViagem()
{
    $banco = abrirBanco();

    $id         = $banco->real_escape_string($_POST["id"]);
    $rota_id    = $banco->real_escape_string($_POST["rota_id"]);
    $veiculo_id = $banco->real_escape_string($_POST["veiculo_id"]);
    $data       = $banco->real_escape_string($_POST["data"]);
    $valor      = $banco->real_escape_string($_POST["valor"]);

    $sql = "UPDATE viagem SET 
                   rota_id='$rota_id', veiculo_id='$veiculo_id', data='$data', valor='$valor' 
            WHERE id='$id'";

    $banco->query($sql);
    $banco->close();

    header("Location:analista.php");
    exit();
}

function excluirViagem()
{
    $banco = abrirBanco();
    $id = $banco->real_escape_string($_POST["id"]);

    $sql = "DELETE FROM viagem WHERE id='$id'";

    $banco->query($sql);
    $banco->close();

    header("Location:analista.php");
    exit();
}

function selecionarViagemId($id)
{
    $banco = abrirBanco();
    $sql = "SELECT * FROM viagem WHERE id=$id";
    $resultado = $banco->query($sql);
    $viagem = mysqli_fetch_assoc($resultado);
    $banco->close();
    return $viagem;
}

function listarViagens()
{
    $viagens = [];
    $banco = abrirBanco();

    // Consulta robusta trazendo nomes ao invés de IDs para o analista
    $sql = "SELECT v.id, v.data, v.valor, 
                   r.nome AS nome_rota, 
                   vei.modelo AS modelo_veiculo, vei.tipo AS tipo_veiculo
            FROM viagem v
            INNER JOIN rota r ON v.rota_id = r.id
            INNER JOIN veiculo vei ON v.veiculo_id = vei.id
            ORDER BY v.data ASC";

    $resultado = $banco->query($sql);
    while ($row = mysqli_fetch_array($resultado)) {
        $viagens[] = $row;
    }
    $banco->close();
    return $viagens;
}
