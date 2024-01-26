// Game Logic for Tic Tac Toe

const gameboard = document.getElementById('gameboard');
const boxes = gameboard.getElementsByClassName('box');
const restartBtn = document.getElementById('restartBtn');

// Add event listener to each box
for (let i = 0; i < boxes.length; i++) {
  boxes[i].addEventListener('click', function() {
    if (this.innerHTML === ' ' && gameState[i] === ' ' && !winner) {
      this.innerHTML = playerSymbol;
      gameState[i] = playerSymbol;

      // Emitir evento de atualização para o servidor
      socket.emit('updateGameState', gameState);

      // Verificar se há um vencedor
      const winner = checkWinner();
      if (winner) {
        alert(`O jogador ${winner} venceu!`);
      }
    }
  });
}

// Add event listener to restart button
restartBtn.addEventListener('click', function() {
  // Limpar o jogo e reiniciar para o estado inicial
  resetGame();

  // Emitir evento de reinício para o servidor
  socket.emit('restartGame');
});

// Função para verificar se há um vencedor
function checkWinner() {
  const winningPatterns = [
    [0, 1, 2], // Linhas
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6], // Colunas
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8], // Diagonais
    [2, 4, 6]
  ];

  for (let i = 0; i < winningPatterns.length; i++) {
    const [a, b, c] = winningPatterns[i];
    if (
      gameState[a] !== ' ' &&
      gameState[a] === gameState[b] &&
      gameState[a] === gameState[c]
    ) {
      return gameState[a];
    }
  }

  if (!gameState.includes(' ')) {
    return 'Empate';
  }

  return null;
}
