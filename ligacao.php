<?php
session_start();

$servidor = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "registo";

// Cria a conexão
try {
    $ligacao = new PDO("mysql:host=$servidor;dbname=" . $dbname, $utilizador, $senha);
    // echo "Ligação com a base de dados com sucesso!";
} catch (PDOException $err) {
    echo "Ligação com a base de dados falhou!" . $err->getMessage();
}
?>
