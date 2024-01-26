<?php
include 'config.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}



$email = $_SESSION['email'];

// Check if the user is already in a session
$query = "SELECT session_id FROM registo WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
if ($row['session_id'] !== NULL) {
    header('location: lobby.php');
    exit();
}

// Create a new game session
$query = "INSERT INTO game_sessions (creator_email) VALUES ('$email')";
mysqli_query($conn, $query);
$session_id = mysqli_insert_id($conn);

// Update user's session ID in the database
$query = "UPDATE registo SET session_id = $session_id WHERE email = '$email'";
mysqli_query($conn, $query);

// Redirect to the game.php page with the session ID as a parameter
header("location: game.php?id=$session_id");
exit();
?>
