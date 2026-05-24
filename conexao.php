<?php
function abrirBanco() {
    $connect = mysqli_connect("localhost", "root", "", "sistema_venda_passagens");
    return $connect;
}

function voltarIndex()
{
    header("Location:index.php");
}

function voltarLoginAnalista()
{
    header("Location:analista.php");
}
?>
