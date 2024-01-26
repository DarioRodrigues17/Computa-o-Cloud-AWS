<?php
@include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['make_move']) && isset($_POST['game_state'])) {
    $move = $_POST['make_move'];
    $gameState = $_POST['game_state'];

    if (isValidMove($move, $gameState)) {
      $gameState = makeMove($move, $gameState);

      $query = "UPDATE game_sessions SET game_state = '$gameState' WHERE session_id = '{$_POST['session_id']}'";
      mysqli_query($conn, $query);

      echo $gameState;
      exit();
    }
  }
}

echo 'error';
exit();

function isValidMove($move, $gameState) {
    $position = $move['position'];
    return $gameState[$position] === '';
  }
  
  function makeMove($move, $gameState) {
    $position = $move['position'];
    $symbol = $move['symbol'];
    $gameState[$position] = $symbol;
    return $gameState;
  }
?>