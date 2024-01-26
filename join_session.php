<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];
$session_id = $_POST['session_id'];

// Verifica se o usuário já está em uma sessão
$query = "SELECT session_id FROM registo WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
if ($row['session_id'] !== NULL) {
    header('Location: lobby.php');
    exit();
}

// Verifica se a sessão de jogo solicitada existe e está ativa
$query = "SELECT * FROM game_sessions WHERE id = $session_id AND status = 'active'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: lobby.php');
    exit();
}

// Adiciona o usuário à sessão de jogo
$query = "UPDATE registo SET session_id = '$session_id' WHERE email = '$email'";
mysqli_query($conn, $query);

// Obtém o nome da sala de jogo
$query = "SELECT name FROM game_sessions WHERE id = $session_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$session_name = $row['name'];

// Redireciona para a página game.php com o ID da sessão e o nome da sala como parâmetros
header("Location: game.php?id=$session_id&name=" . urlencode($session_name));
exit();
?>