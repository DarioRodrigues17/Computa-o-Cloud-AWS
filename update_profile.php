<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
   header('location: login.php');
   exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

   $update_query = "UPDATE `registo` SET name = '$update_name', email = '$update_email' WHERE id = '$user_id'";
   mysqli_query($conn, $update_query) or die('Query failed');

   $old_pass = $_POST['old_pass'];
   $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
   $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
   $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

   if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
      if ($update_pass != $old_pass) {
         $message[] = 'Old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'Confirm password not matched!';
      } else {
         $update_pass_query = "UPDATE `registo` SET password = '$confirm_pass' WHERE id = '$user_id'";
         mysqli_query($conn, $update_pass_query) or die('Query failed');
         $message[] = 'Password updated successfully!';
      }
   }

   $update_image = $_FILES['update_image']['name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_folder = 'uploaded_img/' . $update_image;

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'Image is too large';
      } else {
         $update_image_query = "UPDATE `registo` SET image = '$update_image' WHERE id = '$user_id'";
         mysqli_query($conn, $update_image_query) or die('Query failed');
         move_uploaded_file($update_image_tmp_name, $update_image_folder);
         $message[] = 'Image updated successfully!';
      }
   }
}

$select_query = "SELECT * FROM `registo` WHERE id = '$user_id'";
$select_result = mysqli_query($conn, $select_query) or die('Query failed');
$fetch = mysqli_fetch_assoc($select_result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/updateprofile.css">

   <!-- Font Awesome Icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<body>
<div class="update-profile">
   <form action="" method="post" enctype="multipart/form-data">
      <?php if ($fetch['image'] == '') : ?>
         <img src="images/default-avatar.png">
      <?php else : ?>
         <img src="uploaded_img/<?php echo $fetch['image']; ?>">
      <?php endif; ?>
      <?php if (isset($message)) : ?>
         <?php foreach ($message as $msg) : ?>
            <div class="message"><?php echo $msg; ?></div>
         <?php endforeach; ?>
      <?php endif; ?>
      <div class="flex">
         <div class="inputBox">
            <span>Nome :</span>
            <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
            <span>Email :</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
            <span>Atualizar Prefile :</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
            <span>Password Antiga:</span>
            <input type="password" name="update_pass" placeholder="Enter Previous Password" class="box">
            <span>Password Nova :</span>
            <input type="password" name="new_pass" placeholder="Enter New Password" class="box">
            <span>Confirme a Password :</span>
            <input type="password" name="confirm_pass" placeholder="Confirm New Password" class="box">
         </div>
      </div>
      <!-- Move o botão para dentro do <form> -->
      <div class="button-container">
         <input type="submit" value="Update Profile" name="update_profile" class="btn">
         <a href="dashboardcliente.php" class="delete-btn"><i class="fas fa-arrow-left"></i> Voltar Atrás</a>
      </div>
   </form>
</div>

</body>

</html>