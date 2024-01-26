<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit();
}

if (isset($_GET['logout'])) {
   unset($user_id);
   session_destroy();
   header('location:login.php');
   exit();
}

// Verifica se o formulário de atualização foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
   // Verifica se foi enviado um arquivo de imagem
   if ($_FILES['background_image']['size'] > 0) {
      $image = $_FILES['background_image']['name'];
      $image_tmp = $_FILES['background_image']['tmp_name'];
      $image_extension = pathinfo($image, PATHINFO_EXTENSION);
      $allowed_extensions = array('jpg', 'jpeg', 'png');

      // Verifica se a extensão do arquivo é permitida
      if (in_array($image_extension, $allowed_extensions)) {
         // Move o arquivo para a pasta de upload
         move_uploaded_file($image_tmp, 'uploaded_img/' . $image);

         // Atualiza o campo background_image na tabela registo
         $update = mysqli_query($conn, "UPDATE `registo` SET background_image = '$image' WHERE id = '$user_id'");
         if ($update) {
            // Atualização bem-sucedida
            header('location: Profilepage.php');
            exit();
         } else {
            // Erro na atualização do banco de dados
            $error_message = 'Erro ao atualizar o fundo da página de perfil.';
         }
      } else {
         // Extensão do arquivo não permitida
         $error_message = 'Apenas arquivos JPG, JPEG e PNG são permitidos.';
      }
   } else {
      // Nenhum arquivo de imagem enviado
      $error_message = 'Por favor, selecione um arquivo de imagem.';
   }
}

// Recupera as informações do usuário e o fundo da página atual
$select = mysqli_query($conn, "SELECT * FROM `registo` WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
   $fetch = mysqli_fetch_assoc($select);
   $background_image = $fetch['background_image'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style2.css">

   <style>
   body {
      background-image: url('uploaded_img/<?php echo $background_image; ?>');
      background-size: cover;
      background-position: center;
   }

   .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
   }

   .profile {
      text-align: center;
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
   }
   </style>

</head>

<body>

   <div class="container">

      <div class="profile">
         <?php
         if ($fetch['image'] == '') {
            echo '<img src="images/default-avatar.png">';
         } else {
            echo '<img src="uploaded_img/' . $fetch['image'] . '">';
         }
         ?>

         <h2>Welcome, <?php echo $fetch['name']; ?></h2>

         <a href="dashboardcliente.php" class="btn">Dashboard</a>
         <a href="dashboardcliente.php" class="btn">Atualizar Perfil</a>
         <a href="?logout=true" class="btn">Logout</a>

         <h3>Atualizar Fundo da Página</h3>

         <?php
         if (isset($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
         }
         ?>

         <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="background_image">
            <input type="submit" name="submit" value="Atualizar">
         </form>
      </div>

   </div>

</body>

</html>

