$(document).ready(function() {
    // Verifique se o jogador atual está autorizado a fazer movimentos no jogo
    var isAuthorized = (userIsAdmin || joinedPlayerEmail === playerEmail);

    if (isAuthorized) {
        // Manipule o evento de clique nas células do jogo
        $('.cell').click(function() {
            var move = $(this).data('move');
            var gameState = '<?php echo $gameState; ?>';
            var clickedCell = $(this); // Armazene a célula clicada em uma variável para uso posterior

            // Faça uma requisição AJAX para atualizar o estado do jogo
            $.ajax({
                type: 'POST',
                url: 'update_game_state.php',
                data: {
                    session_id: '<?php echo $sessionId; ?>',
                    game_state: gameState
                },
                success: function(response) {
                    // Verifique se a atualização foi bem-sucedida
                    if (response === 'success') {
                        // Implemente a lógica para atualizar o estado do jogo no frontend
                        // Atualize a célula do jogo com o movimento feito pelo jogador
                        clickedCell.text(move);

                        // Atualize o estado do jogo no frontend
                        gameState = 'ongoing'; // Atualize com a lógica adequada do jogo

                        // Atualize o estado do jogo no banco de dados
                        updateGameState('<?php echo $sessionId; ?>', gameState);

                        // Verifique se houve um vencedor ou empate
                        if (checkWinner()) {
                            console.log('O jogador venceu!');
                            // Realize as ações necessárias quando um jogador vence o jogo
                        } else if (checkDraw()) {
                            console.log('Empate!');
                            // Realize as ações necessárias quando o jogo termina em empate
                        } else {
                            // Outras ações durante o jogo
                            // ...
                        }

                        // Update the game status
                        updateStatus("Player " + gameState.currentPlayer + "'s turn");
                    } else {
                        // Exiba uma mensagem de erro caso ocorra um problema na atualização do estado do jogo
                        console.log('Erro ao atualizar o estado do jogo.');
                    }
                },
                error: function() {
                    // Exiba uma mensagem de erro caso ocorra um erro na requisição AJAX
                    console.log('Erro na requisição AJAX.');
                }
            });
        });
    } else {
        // Exiba uma mensagem de erro caso o jogador atual não esteja autorizado a fazer movimentos no jogo
        console.log('Você não tem permissão para fazer movimentos neste jogo.');
    }
});

// Função para verificar se há um vencedor
function checkWinner() {
    // Implemente a lógica para verificar se há um vencedor
    // Retorne true se houver um vencedor, caso contrário, retorne false

    // Exemplo de implementação para um jogo da velha
    var cells = $('.cell'); // Obtenha todas as células do jogo
    var winningCombinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Linhas
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Colunas
        [0, 4, 8], [2, 4, 6] // Diagonais
    ];

    // Verifique todas as combinações de vitória
    for (var i = 0; i < winningCombinations.length; i++) {
        var combination = winningCombinations[i];
        var cell1 = cells.eq(combination[0]).text();
        var cell2 = cells.eq(combination[1]).text();
        var cell3 = cells.eq(combination[2]).text();

        // Verifique se todas as células da combinação são iguais e não estão vazias
        if (cell1 !== '' && cell1 === cell2 && cell2 === cell3) {
            return true; // Temos um vencedor
        }
    }

    return false; // Não há vencedor
}

// Função para verificar se o jogo terminou em empate
function checkDraw() {
    // Implemente a lógica para verificar se o jogo terminou em empate
    // Retorne true se o jogo terminou em empate, caso contrário, retorne false

    // Exemplo de implementação para um jogo da velha
    var cells = $('.cell'); // Obtenha todas as células do jogo

    // Verifique se todas as células estão preenchidas
    for (var i = 0; i < cells.length; i++) {
        if (cells.eq(i).text() === '') {
            return false; // Ainda há células vazias, o jogo não terminou em empate
        }
    }

    return true; // Todas as células estão preenchidas, o jogo terminou em empate
}

// Função para atualizar o estado do jogo no banco de dados
function updateGameState(sessionId, gameState) {
    // Implemente a lógica para atualizar o estado do jogo no banco de dados
    // Você pode usar uma requisição AJAX para enviar os dados para o servidor e atualizar o estado do jogo no banco de dados
    // Aqui está um exemplo de requisição AJAX usando jQuery:
    $.ajax({
        type: 'POST',
        url: 'update_game_state.php',
        data: {
            session_id: sessionId,
            game_state: gameState
        },
        success: function(response) {
            // Lógica para tratar a resposta do servidor, se necessário
        },
        error: function() {
            // Lógica para tratar erros na requisição AJAX, se necessário
        }
    });
}

// Função para atualizar o status do jogo no frontend
function updateStatus(status) {
    // Implemente a lógica para atualizar o status do jogo no frontend
    // Você pode exibir o status em um elemento HTML, por exemplo:
    $('#game-status').text(status);
}
