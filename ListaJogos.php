<?php
session_start();

if (!isset($_SESSION['user_id'])) {
   header('location: login.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Lista de Jogos</title>
   </head>
   <body>
      <h1>Lista de Jogos</h1>
      <ul>
         <li><a href="JogoSingleplayer.html">Jogo Singleplayer</a></li>
         <li><a href="JogoMaquina.html">Jogo contra a Máquina</a></li>
         <li><a href="Lobby.php">Lobby Multiplayer</a></li>
      </ul>
      <a href="dashboardcliente.php">Voltar</a>
   </body>
</html>
