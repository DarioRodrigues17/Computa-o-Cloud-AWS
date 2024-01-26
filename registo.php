<?php
session_start();
@include 'config.php';

if(isset($_POST['submit'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $user_type = $_POST['user_type'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select = "SELECT * FROM registo WHERE email = '$email' && password = '$pass'";
   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){
      $error[] = 'Utilizador já existe!';
   } else {
      if($pass != $cpass){
         $error[] = 'As senhas não correspondem!';
      } else {
         $insert = "INSERT INTO registo(name, email, password, image, user_type, session_id) VALUES('$name', '$email', '$pass', '$image', '$user_type', NULL)";
         mysqli_query($conn, $insert);
         
         // Criar nova sessão de jogo ativa
         $query = "INSERT INTO game_sessions (status, session_id) VALUES ('active', NULL)";
         mysqli_query($conn, $query);
         $session_id = mysqli_insert_id($conn); // Obter o ID da nova sessão criada

         // Atualizar o session_id na tabela registo
         $updateQuery = "UPDATE registo SET session_id = '$session_id' WHERE email = '$email'";
         mysqli_query($conn, $updateQuery);

         // Redirecionar para o lobby com o ID da nova sessão
         header("Location: lobby.php?id=$session_id");
         exit();
      }
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
                    Registo
                </span>

                <div class="wrap-input100 validate-input" data-validate="Insira um Nome Valido">
                    <input class="input100" type="text" name="name" placeholder="Nome" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input class="input100" type="email" name="email" placeholder="Email" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <input class="input100" type="password" name="password" placeholder="Password" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Confirme a Password">
                    <input class="input100" type="password" name="cpassword" placeholder="Confirme a Password" required>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Escolha um tipo de usuário">
                    <select class="input100" name="user_type" required>
                        <option value="user">Utilizador</option>
                        <option value="admin">Administrador</option>
                    </select>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </span>
                </div>
                <div>
                    <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png" class="wrap-input100 validate-input" data-validate="Escolha uma imagem válida">
                </div>
                <div class="wrap-input100 validate-input">
                    <p>Já tem uma conta? <a href="login.php">Faça login agora</a></p>
                </div>
                <?php
                if (isset($_SESSION['msg'])) {
                    echo "<p style='color: #ff0000'>" . $_SESSION['msg'] . "</p>";
                    unset($_SESSION['msg']);
                }
                ?>
                <div>
                    <input type="submit" name="submit" value="Registar Agora" class="login100-form-btn">
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