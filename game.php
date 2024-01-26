<?php
session_start();
require_once 'config.php';

$playerSymbol = isset($_POST['symbol']) ? $_POST['symbol'] : '';

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'registo';

// Connect to the database
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    die("Failed to connect to the database: " . mysqli_connect_error());
}

// Fetch the session ID from the database based on the user ID
$userId = $_SESSION['user_id'];
$sessionQuery = "SELECT session_id FROM registo WHERE id = '$userId'";
$sessionResult = mysqli_query($conn, $sessionQuery);

if ($sessionResult && mysqli_num_rows($sessionResult) > 0) {
    $sessionData = mysqli_fetch_assoc($sessionResult);
    $_SESSION['session_id'] = $sessionData['session_id']; // Store the session ID in $_SESSION
} else {
    echo "Error: Failed to fetch session ID from the database";
    exit();
}

// Check if the user has joined a specific room
if (isset($_GET['id'])) {
    $gameSessionId = $_GET['id'];

    // Fetch the player from the database
    $playerQuery = "SELECT * FROM registo WHERE session_id = '{$_SESSION['session_id']}'";
    $playerResult = mysqli_query($conn, $playerQuery);

    if ($playerResult && mysqli_num_rows($playerResult) > 0) {
        $player = mysqli_fetch_assoc($playerResult);

        // Check if the room is already full (2 players)
        $roomQuery = "SELECT * FROM game_sessions WHERE id = '$gameSessionId'";
        $roomResult = mysqli_query($conn, $roomQuery);

        if ($roomResult && mysqli_num_rows($roomResult) > 0) {
            $roomData = mysqli_fetch_assoc($roomResult);

            if ($roomData['player2_id'] !== NULL) {
                // Room is already full, redirect to some error page or display a message
                echo "Error: The room is already full.";
                exit();
            }
        } else {
            // Handle the query error or invalid room
            echo "Error: Invalid room or database query error: " . mysqli_error($conn);
            exit();
        }

        // Update the game state for the current player in the room
        $updateQuery = "UPDATE game_sessions SET player_symbol = '$playerSymbol'";

        // If the player is the first one in the room, assign them as player 1
        if ($roomData['player1_id'] == null) {
            $updateQuery .= ", player1_id = '{$player['id']}', player1_symbol = '$playerSymbol'";
        }
        // If the player is the second one in the room, assign them as player 2
        else if ($roomData['player2_id'] == null) {
            $updateQuery .= ", player2_id = '{$player['id']}', player2_symbol = '$playerSymbol'";
        } else {
            // Handle the case when the room is already full (more than 2 players)
            echo "Error: Room is already full";
            exit();
        }

        $updateQuery .= " WHERE id = '$gameSessionId' AND session_id = '{$_SESSION['session_id']}'"; // Add condition for the specific game session ID
        $updateResult = mysqli_query($conn, $updateQuery);

        if (!$updateResult) {
            echo "Error: Failed to update player's game state: " . mysqli_error($conn);
            exit();
        }
    } else {
        // Handle the query error or invalid player
        echo "Error: Invalid player or database query error: " . mysqli_error($conn);
        exit();
    }
} else {
    // Handle the case when $gameSessionId is not defined
    echo "Error: Game Session ID is not defined";
    exit();
}

// Fetch the number of players in the room
$playerCountQuery = "SELECT COUNT(*) AS player_count FROM game_sessions 
                     INNER JOIN registo ON game_sessions.player1_id = registo.id OR game_sessions.player2_id = registo.id
                     WHERE game_sessions.id = '$gameSessionId'";
$playerCountResult = mysqli_query($conn, $playerCountQuery);

if ($playerCountResult) {
    $playerCountData = mysqli_fetch_assoc($playerCountResult);
    $playerCount = $playerCountData['player_count'];

    // Display the number of players in the room
    echo "Number of Players in the Room: " . intval($playerCount);
} else {
    // Handle the query error
    echo "Error: Failed to fetch player count: " . mysqli_error($conn);
    exit();
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo do Galo🐓</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Finger+Paint&display=swap" rel="stylesheet"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.0/socket.io.js"></script>
    <style>
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            text-align: center;
        }
    </style>
</head>
<body style="background-color: #37505C">
    <div class="center-content">
        <h1>Tic Tac Toe</h1>
        
        <div id="symbolSelection">
            <form id="symbolForm" method="POST" action="">
                <input type="radio" name="symbol" value="X"> X
                <input type="radio" name="symbol" value="O"> O
                <button type="submit" id="selectSymbolBtn">Select Symbol</button>
            </form>
        </div>

        <div id="gameboard" class="game-container"> 
            <?php
            $gameState = ['', '', '', '', '', '', '', '', ''];
            for ($i = 0; $i < count($gameState); $i++) {
            ?>
                <div class="box"></div>
            <?php
            }
            ?>
        </div>

        <button id="restartBtn">Restart</button>
        <div class="button-container">
            <a href="dashboardcliente.php" class="delete-btn"><i class="fas fa-arrow-left"></i> Go Back</a>
        </div>
    </div>
    <script>
        const gameboard = document.getElementById('gameboard');
        const boxes = gameboard.getElementsByClassName('box');
        const restartBtn = document.getElementById('restartBtn');
        const symbolForm = document.getElementById('symbolForm');
        const socket = io(); // Assuming Socket.io is properly initialized

        // Initialize game state
        let gameState = <?php echo json_encode($gameState); ?>;
        let winner = null;
        let playerSymbol = '<?php echo $playerSymbol; ?>';

        // Event listener for symbol selection form submission
        symbolForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get the selected symbol
            const selectedSymbol = document.querySelector('input[name="symbol"]:checked');
            if (selectedSymbol) {
            playerSymbol = selectedSymbol.value;

            // Emit an event to the server with the selected symbol
            socket.emit('symbolSelected', playerSymbol);

            // Hide the symbol selection form
            symbolForm.style.display = 'none';
            }
        });

        // Event listener for box clicks
        gameboard.addEventListener('click', function(e) {
            if (e.target.classList.contains('box')) {
            const boxIndex = Array.from(boxes).indexOf(e.target);
            // Emit an event to the server with the clicked box index
            socket.emit('boxClicked', boxIndex);
            }
        });

        // Event listener for restart button click
        restartBtn.addEventListener('click', function() {
            // Emit a restart event to the server
            socket.emit('restartGame');
        });

        // Socket event listeners
        socket.on('gameState', function(updatedGameState) {
            // Update the game state on the client-side
            gameState = updatedGameState;

            // Update the boxes
            for (let i = 0; i < gameState.length; i++) {
            boxes[i].textContent = gameState[i];
            }
        });

        socket.on('playerCount', function(playerCount) {
            // Update the player count on the client-side
            const playerCountElement = document.createElement('p');
            playerCountElement.textContent = 'Number of Players in the Room: ' + playerCount;
            gameboard.prepend(playerCountElement);
        });

        socket.on('playerSymbol', function(playerSymbol) {
            // Display the player's symbol on the client-side
            const symbolSelection = document.getElementById('symbolSelection');
            symbolSelection.innerHTML = 'Your Symbol: ' + playerSymbol;
        });
    </script>
</body>
</html>