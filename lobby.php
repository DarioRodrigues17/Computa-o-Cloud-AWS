<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Fetch user details from the database
$query = "SELECT * FROM registo WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Fetch active game sessions
$query = "SELECT * FROM game_sessions WHERE status = 'active'";
$result = mysqli_query($conn, $query);
$activeGameSessions = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Check if there are two players in the session
function isSessionFull($sessionId)
{
    global $conn;
    $query = "SELECT COUNT(*) as player_count FROM players WHERE session_id = '$sessionId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['player_count'] === '2';
    }

    return false;
}

// Check if the user is already in a session
function isInSession($sessionId)
{
    global $conn, $email;
    $query = "SELECT COUNT(*) as player_count FROM players WHERE session_id = '$sessionId' AND email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['player_count'] === '1';
    }

    return false;
}

// Join a game session
if (isset($_POST['joinSession']) && isset($_POST['sessionId'])) {
    $sessionId = $_POST['sessionId'];
    if (isSessionFull($sessionId)) {
        $joinError = "A sala já está cheia. Escolha outra sala para entrar.";
    } elseif (isInSession($sessionId)) {
        $joinError = "Você já está nesta sala.";
    } else {
        // Check if the room is already full
        $query = "SELECT COUNT(*) as player_count FROM players WHERE session_id = '$sessionId'";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $numPlayers = $row['player_count'];
                if ($numPlayers == '2') {
                    $joinError = "A sala está cheia. Por favor, tente outra sala.";
                } else {
                    // Insert the player into the session
                    $insertQuery = "INSERT INTO players (email, session_id) VALUES ('$email', '$sessionId')";
                    mysqli_query($conn, $insertQuery);
                    header("Location: game.php?id=$sessionId");
                    exit();
                }
            } else {
                $joinError = "ID de sala inválido. Por favor, tente novamente.";
            }
        } else {
            $joinError = "Ocorreu um erro ao consultar o banco de dados. Por favor, tente novamente.";
        }
    }
}

// Handle chat message submission
if (isset($_POST['chatMessage'])) {
    $sender = $user['name'];
    $message = $_POST['chatMessage'];

    // Save the chat message to the database
    $insertQuery = "INSERT INTO chat_messages (sender, message) VALUES ('$sender', '$message')";
    mysqli_query($conn, $insertQuery);

    // Emit the chat message to all connected clients
    if (isset($io)) {
        $chatMessage = ['sender' => $sender, 'message' => $message];
        $io->emit('chatMessage', $chatMessage);
    }
}

// Check if the room is already full
$roomFull = false;
if (isset($_GET['id'])) {
    $roomId = $_GET['id'];
    $query = "SELECT COUNT(*) as player_count FROM players WHERE session_id = '$roomId'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $numPlayers = $row['player_count'];
            if ($numPlayers === '2') {
                $roomFull = true;
            }
        } else {
            $roomId = null;
        }
    } else {
        $roomId = null;
    }
} else {
    $roomId = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Jogo da Velha - Lobby</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" href="css/updateprofile.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="https://cdn.jsdelivr.net/npm/tilt.js@1.2.1"></script>
    <!--===============================================================================================-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.2.0/socket.io.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-tilt').tilt({
                scale: 1.1
            });
        });

        const socket = io(); // Establish WebSocket connection

        // Handle chat message event
        socket.on('chatMessage', function(data) {
            const messageList = document.querySelector('.message-list');
            const newMessage = document.createElement('li');
            newMessage.textContent = data.sender + ': ' + data.message;
            messageList.appendChild(newMessage);
        });
    </script>
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="images/img-01.png" alt="IMG">
                </div>
                <div class="login100-form">
                    <span class="login100-form-title">
                        Bem-vindo, <?php echo $user['name']; ?>!
                    </span>
                    <div class="wrap-input100 validate-input">
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <p><strong>Tipo de Utilizador:</strong> <?php echo ucfirst($user['user_type']); ?></p>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <p><strong>Imagem:</strong> <img src="uploaded_img/<?php echo $user['image']; ?>" alt="User Image" style="width: 100px; height: 100px;"></p>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <p><a href="logout.php">Sair</a></p>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <h2>Salas de Jogo Disponíveis:</h2>
                        <?php if (count($activeGameSessions) > 0) : ?>
                            <ul>
                                <?php foreach ($activeGameSessions as $session) : ?>
                                    <li><a href="game.php?id=<?php echo $session['id']; ?>">Sala <?php echo $session['id']; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>Nenhuma sala de jogo disponível no momento.</p>
                        <?php endif; ?>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <h2>Número de jogadores na sala:</h2>
                        <?php
                        if ($roomId !== null) {
                            $query = "SELECT COUNT(*) as player_count FROM players WHERE session_id = '$roomId'";
                            $result = mysqli_query($conn, $query);
                            if ($result) {
                                $row = mysqli_fetch_assoc($result);
                                if ($row) {
                                    $numPlayers = $row['player_count'];
                                    echo $numPlayers . ' jogador(es) nesta sala';
                                } else {
                                    echo 'ID de sala inválido.';
                                }
                            } else {
                                echo 'Ocorreu um erro ao consultar o banco de dados.';
                            }
                        } else {
                            echo 'ID de sala não encontrado.';
                        }
                        ?>
                    </div>
                    <!-- Add the join session form -->
                    <div class="wrap-input100 validate-input">
                        <h2>Juntar-se a uma Sala de Jogo:</h2>
                        <form action="" method="POST">
                            <input type="text" name="sessionId" placeholder="Digite o ID da sala" required>
                            <input type="submit" name="joinSession" value="Juntar-se à Sala">
                        </form>
                        <?php if (isset($joinError)) : ?>
                            <p class="error"><?php echo $joinError; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="button-container">
                        <a href="dashboardcliente.php" class="delete-btn"><i class="fas fa-arrow-left"></i> Voltar Atrás</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Section -->
    <div class="chat-section">
        <h2>Chat</h2>
        <ul class="message-list">
            <?php
            // Fetch chat messages from the database
            $chatQuery = "SELECT * FROM chat_messages ORDER BY timestamp DESC LIMIT 10";
            $chatResult = mysqli_query($conn, $chatQuery);
            while ($row = mysqli_fetch_assoc($chatResult)) {
                echo "<li><strong>" . $row['sender'] . ":</strong> " . $row['message'] . "</li>";
            }
            ?>
        </ul>
        <form method="POST" action="">
            <input type="text" name="chatMessage" placeholder="Digite sua mensagem" required>
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>

</html>