<?php
function abrirBanco() {
    $connect = mysqli_connect("localhost", "usuario", "senha", "banco");
    return $connect;
}
?>
