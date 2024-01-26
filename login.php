<?php
@include 'config.php';
session_start();

if (isset($_POST['submit'])) {
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = md5($_POST['password']);

   $select = "SELECT * FROM registo WHERE email = '$email' && password = '$password'";
   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_array($result);
      
      // Definir a variável de sessão $_SESSION['email'] com o email do usuário
      $_SESSION['email'] = $row['email'];

      if ($row['user_type'] == 'admin') {
         $_SESSION['admin_name'] = $row['name'];
         header('location:dashboardadmin.php');
         exit();
      } elseif ($row['user_type'] == 'user') {
         $_SESSION['user_id'] = $row['id'];
         header('location:dashboardcliente.php');
         exit();
      }
   } else {
      $error[] = 'Email ou password incorretos!';
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Jogo da Velha</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!--===============================================================================================-->   
   <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
   <!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
   <!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
   <!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
   <!--===============================================================================================-->   
   <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
   <!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
   <!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="css/util.css">
   <link rel="stylesheet" type="text/css" href="css/main.css">
   <!--===============================================================================================-->
</head>
<body>
   
   <div class="limiter">
      <div class="container-login100">
         <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
               <img src="images/img-01.png" alt="IMG">
            </div>

            <form action="" method="POST" class="login100-form validate-form">
               <span class="login100-form-title">
                  Login
               </span>

               <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                  <input class="input100" type="email" name="email" placeholder="Insira o seu Email" required>
                  <span class="focus-input100"></span>
                  <span class="symbol-input100">
                     <i class="fa fa-envelope" aria-hidden="true"></i>
                  </span>
               </div>

               <div class="wrap-input100 validate-input" data-validate="Password is required">
                  <input class="input100" type="password" name="password" placeholder="Insira a sua Password" required>
                  <span class="focus-input100"></span>
                  <span class="symbol-input100">
                     <i class="fa fa-lock" aria-hidden="true"></i>
                  </span>
               </div>
               <div>
                  <input type="submit" name="submit" value="Login" class="login100-form-btn">
               </div>
               <?php
               if(isset($error)){
                  foreach($error as $error){
                     echo '<span class="error-msg">'.$error.'</span>';
                  };
               };
               ?>
               <div class="text-center p-t-136">
                  <a class="txt2" href="registo.php">
                     Criar Registro
                     <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                  </a>
               </div>
            </form>
         </div>
      </div>
   </div>
   
   <!--===============================================================================================-->   
   <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
   <!--===============================================================================================-->
   <script src="vendor/bootstrap/js/popper.js"></script>
   <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
   <!--===============================================================================================-->
   <script src="vendor/select2/select2.min.js"></script>
   <!--===============================================================================================-->
   <script src="vendor/tilt/tilt.jquery.min.js"></script>
   <script>
      $('.js-tilt').tilt({
         scale: 1.1
      })
   </script>
   <!--===============================================================================================-->
   <script src="js/main.js"></script>

</body>
</html>