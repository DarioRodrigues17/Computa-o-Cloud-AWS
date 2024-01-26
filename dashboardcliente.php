<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
   exit();
}

$select = mysqli_query($conn, "SELECT * FROM `registo` WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
   $fetch = mysqli_fetch_assoc($select);
}

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <title>Jogo do galo</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="Mystery Company">
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/responsive.css">
      <link rel="icon" href="images/default-avatar.png" type="image/gif" />
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
      
   </head>
   <body class="main-layout">
   <div class="loader_bg">
        <div class="loader">
        <img src="images/loading.gif"/></div>
      </div>
      <header>
         <div class="head_top">
            <div class="header">
               <div class="container">
                  <div class="row">
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                           <div class="logo">
                              <div class="logo">
                              <a href="Profilepage.php"><img src="uploaded_img/<?php echo $fetch['image']; ?>" /></a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <nav class="navigation navbar navbar-expand-md navbar-dark ">
                           <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                           <span class="navbar-toggler-icon"></span>
                           </button>
                           <div class="collapse navbar-collapse" id="navbarsExample04">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="listaJogosDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Lista de Jogos
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="listaJogosDropdown">
                                                <a class="dropdown-item" href="JogoSingleplayer.html">Jogo Singleplayer</a>
                                                <a class="dropdown-item" href="JogoMaquina.html">Jogo contra a Máquina</a>
                                                <a class="dropdown-item" href="lobby.php">Lobby Multiplayer</a>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="sign_btn"><a href="Profilepage.php">Conta</a></div>
                                </div>
                        </nav>
                     </div>
                  </div>
               </div>
            </div>
            <section class="banner_main">
               <div class="container-fluid">
                  <div class="row d_flex">
                     <div class="col-md-5">
                        <div class="text-bg">
                           <h1>MENU</h1>
                           <strong>Jogo do Galo🐓</strong>
                           <form action="lobby.php" method="post">
                              <div class="about_box">
                                 <figure></figure>
                                 <button class="read_more" type="submit">Lobby</button>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="col-md-7 padding_right1">
                        <div class="text-img">
                           
                           <h3>🐓</h3>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         </div>
      </header>
      <div id="about" class="about">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Jogo do Galo🐓</h2>
                     <br>
                     <br>
                     <span>O jogo do galo é um jogo clássico e bastante popular que pode ser jogado com um papel e lápis por dois jogadores </span>
                     <span>que alternadamente vão desenhando um X e um O numa grelha de tamanho 3 x 31. </span>
                     <span>Ganha o jogador que primeiro conseguir alinhar três símbolos na vertical, na horizontal ou na diagonal2. Se for jogado sem falhas,</span> 
                     <span> este é um tipo de jogo que termina sempre empatado2.</span>
                     <br>
                     <a href="JogoSingleplayer.html"><img src="images/jogodogalo.png" alt="#" /></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <footer>
         <div class="footer">
            <div class="container">
               <div class="row">
                  <div class="col-md-6">
                     <div class="cont">
                        <h3> <strong class="multi">Dario Rodrigues</strong><br>
                        </h3>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="cont_call">
                        <h3> <strong class="multi">A038042@umaia.pt</strong><br>
                        </h3>
                     </div>
                  </div>
               </div>
            </div>
            <div class="copyright">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <p>© 2023 All Rights Reserved to Dario Rodrigues </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- end footer -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
   </body>
</html>
