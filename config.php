<?php

$conn = mysqli_connect('localhost','root','','registo');

$gameState = array_fill(0, 9, "");
$gameState['playerSymbol'] = '';

$userIsAdmin = true;

?>