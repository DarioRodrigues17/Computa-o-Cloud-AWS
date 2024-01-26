// Define a variable for the AI difficulty
var aiDifficulty = 'easy';

// Define the difficulty select element
var difficultySelect = document.getElementById('difficulty');

// Add an event listener to update the AI difficulty
difficultySelect.addEventListener('change', function() {
    var selectedDifficulty = this.value;
    aiDifficulty = difficultyLevels[selectedDifficulty];
    handleRestart();
});

// Define the game state
var gameState = {
    spaces: Array(9).fill(null),
    currentPlayer: 'X',
    winner: null,
};

// Define the difficulty levels
var difficultyLevels = {
    easy: 0,
    medium: 1,
    hard: 2,
};

// Define a function to handle clicks on the boxes
function handleClick(id) {
    // Check if the space is already occupied or if there is a winner
    if (gameState.spaces[id] || gameState.winner) {
        return;
    }

    // Update the player's move
    gameState.spaces[id] = gameState.currentPlayer;

    // Check if the current player has won
    if (playerHasWon(gameState.spaces)) {
        gameState.winner = gameState.currentPlayer;
    } else {
        // Switch players
        gameState.currentPlayer = gameState.currentPlayer === 'X' ? 'O' : 'X';

        // Make AI move if it's the AI's turn
        if (gameState.currentPlayer === 'O') {
            makeAIMove();
            // Check if the AI wins
            if (playerHasWon(gameState.spaces)) {
                gameState.winner = gameState.currentPlayer;
            }
        }
    }

    // Update the game board
    updateGameBoard();
}


// Define a function to check if a player has won
function playerHasWon(spaces) {
    // Define the winning combinations
    var winningCombos = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6]
    ];

    // Check all possible winning combinations
    for (var i = 0; i < winningCombos.length; i++) {
        var [a, b, c] = winningCombos[i];

        // Check if all spaces in the winning combination are occupied by the same player
        if (spaces[a] && spaces[a] === spaces[b] && spaces[a] === spaces[c]) {
            return true;
        }
    }

    return false;
}

// Define a function to make AI move
function makeAIMove() {
    if (aiDifficulty === difficultyLevels.easy) {
        makeRandomMove();
    } else if (aiDifficulty === difficultyLevels.medium) {
        makeMediumMove();
    } else if (aiDifficulty === difficultyLevels.hard) {
        makeHardMove();
    }
}

// Define a function to make a random move
function makeRandomMove() {
    var availableSpaces = [];

    // Get the indices of available spaces
    for (var i = 0; i < gameState.spaces.length; i++) {
        if (!gameState.spaces[i]) {
            availableSpaces.push(i);
        }
    }

    // Make a random move
    var randomIndex = Math.floor(Math.random() * availableSpaces.length);
    var randomSpace = availableSpaces[randomIndex];
    gameState.spaces[randomSpace] = gameState.currentPlayer;
}

// Define a function to make a medium difficulty move
function makeMediumMove() {
    // Check if AI can win in the next move
    for (var i = 0; i < gameState.spaces.length; i++) {
        if (!gameState.spaces[i]) {
            gameState.spaces[i] = 'O';
            if (playerHasWon(gameState.spaces)) {
                return;
            }
            gameState.spaces[i] = null;
        }
    }

    // Check if player can win in the next move and block the player
    for (var i = 0; i < gameState.spaces.length; i++) {
        if (!gameState.spaces[i]) {
            gameState.spaces[i] = 'X';
            if (playerHasWon(gameState.spaces)) {
                gameState.spaces[i] = 'O';
                return;
            }
            gameState.spaces[i] = null;
        }
    }

    // If no winning move is possible, make a random move
    makeRandomMove();
}

// Define a function to make a hard difficulty move (minimax algorithm)
function makeHardMove() {
    var bestScore = -Infinity;
    var bestMove;

    // Check all possible moves and choose the one with the highest score
    for (var i = 0; i < gameState.spaces.length; i++) {
        if (!gameState.spaces[i]) {
            gameState.spaces[i] = 'O';
            var score = minimax(gameState.spaces, 0, false);
            gameState.spaces[i] = null;

            if (score > bestScore) {
                bestScore = score;
                bestMove = i;
            }
        }
    }

    // Make the best move
    gameState.spaces[bestMove] = gameState.currentPlayer;
}

// Define the minimax function
function minimax(spaces, depth, isMaximizing) {
    // Check if the current node is a terminal node
    if (playerHasWon(spaces)) {
        return isMaximizing ? -1 : 1;
    } else if (!spaces.includes(null)) {
        return 0;
    }

    // Recursive calls for all possible moves
    if (isMaximizing) {
        var bestScore = -Infinity;

        for (var i = 0; i < spaces.length; i++) {
            if (!spaces[i]) {
                spaces[i] = 'O';
                var score = minimax(spaces, depth + 1, false);
                spaces[i] = null;

                bestScore = Math.max(score, bestScore);
            }
        }

        return bestScore;
    } else {
        var bestScore = Infinity;

        for (var i = 0; i < spaces.length; i++) {
            if (!spaces[i]) {
                spaces[i] = 'X';
                var score = minimax(spaces, depth + 1, true);
                spaces[i] = null;

                bestScore = Math.min(score, bestScore);
            }
        }

        return bestScore;
    }
}

// Define a function to update the game board
function updateGameBoard() {
    var boxes = document.getElementsByClassName('box');
    for (var i = 0; i < boxes.length; i++) {
        boxes[i].innerText = gameState.spaces[i];
    }

    var playerText = document.getElementById('playerText');
    if (gameState.winner) {
        playerText.innerText = 'Player ' + gameState.winner + ' Ganha!';
    } else if (!gameState.spaces.includes(null)) {
        playerText.innerText = "É empate!";
    } else {
        playerText.innerText = "Ves do " + gameState.currentPlayer + "Player";
    }
}

// Define a function to handle restart
function handleRestart() {
    // Reset the game state
    gameState.spaces = Array(9).fill(null);
    gameState.currentPlayer = 'X';
    gameState.winner = null;

    // Update the game board
    updateGameBoard();
}

// Add event listener to restart button
document.addEventListener('DOMContentLoaded', function() {
    var restartButton = document.getElementById('restartButton');
    restartButton.addEventListener('click', handleRestart);
});

// Add event listeners to the boxes
var boxes = document.getElementsByClassName('box');
for (var i = 0; i < boxes.length; i++) {
    boxes[i].addEventListener('click', function() {
        handleClick(parseInt(this.id));
    });
}

// Initialize the game
handleRestart();