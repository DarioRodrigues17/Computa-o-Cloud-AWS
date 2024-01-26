<?php
session_start();
require_once 'config.php';

// Verifica se o usuário já está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Cria uma nova sala de jogo
function createGameRoom($conn, $playerId)
{
    $insertRoomQuery = "INSERT INTO game_rooms (player1_id) VALUES ('$playerId')";
    $insertRoomResult = mysqli_query($conn, $insertRoomQuery);

    if ($insertRoomResult) {
        $roomId = mysqli_insert_id($conn);
        return $roomId;
    } else {
        echo "Error: Failed to create game room: " . mysqli_error($conn);
        exit();
    }
}

// Procura uma sala de jogo disponível
function findAvailableGameRoom($conn, $playerId)
{
    $findRoomQuery = "SELECT * FROM game_rooms WHERE player2_id IS NULL";
    $findRoomResult = mysqli_query($conn, $findRoomQuery);

    if ($findRoomResult && mysqli_num_rows($findRoomResult) > 0) {
        $room = mysqli_fetch_assoc($findRoomResult);
        $roomId = $room['id'];

        // Atualiza o jogador 2 na sala
        $updateRoomQuery = "UPDATE game_rooms SET player2_id = '$playerId' WHERE id = '$roomId'";
        $updateRoomResult = mysqli_query($conn, $updateRoomQuery);

        if (!$updateRoomResult) {
            echo "Error: Failed to update game room: " . mysqli_error($conn);
            exit();
        }

        return $roomId;
    } else {
        // Cria uma nova sala de jogo
        $roomId = createGameRoom($conn, $playerId);
        return $roomId;
    }
}

// Obtém o ID do jogador atual
$playerId = $_SESSION['id'];

// Verifica se o jogador já está em uma sala de jogo
$getGameSessionQuery = "SELECT * FROM game_sessions WHERE player_id = '$playerId'";
$getGameSessionResult = mysqli_query($conn, $getGameSessionQuery);

if ($getGameSessionResult && mysqli_num_rows($getGameSessionResult) > 0) {
    $gameSession = mysqli_fetch_assoc($getGameSessionResult);
    $roomId = $gameSession['room_id'];
} else {
    // Procura uma sala de jogo disponível
    $roomId = findAvailableGameRoom($conn, $playerId);

    // Cria uma nova sessão de jogo para o jogador
    $insertGameSessionQuery = "INSERT INTO game_sessions (room_id, player_id) VALUES ('$roomId', '$playerId')";
    $insertGameSessionResult = mysqli_query($conn, $insertGameSessionQuery);

    if (!$insertGameSessionResult) {
        echo "Error: Failed to create game session: " . mysqli_error($conn);
        exit();
    }
}

// Redireciona o jogador para a sala de jogo correspondente
header("Location: game.php?id=$roomId");
exit();
?>