<?php
@include 'config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:login.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM registo WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=Registro excluído com sucesso!");
    } else {
        header("Location: admin.php?msg=Erro ao excluir o registro!");
    }
}

$sql = "SELECT * FROM registo";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

<div class="container">

    <div class="content">
        <h3>hi, <span>admin</span></h3>
        <h1>welcome <span><?php echo $_SESSION['admin_name'] ?></span></h1>
        <p>this is an admin page</p>
        <a href="login.php" class="btn">login</a>
        <a href="registo.php" class="btn">register</a>
        <a href="logout.php" class="btn">logout</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert">
            <?php echo $_GET['msg']; ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo de usuário</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['user_type']; ?></td>
                    <td><img src="<?php echo $row['image']; ?>" alt="Imagem do usuário" width="50" height="50"></td>
                    <td>
                        <a href="?id=<?php echo $row['id']; ?>" class="btn">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>