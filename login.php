<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica se o usuário existe no banco de dados
    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Senha correta, inicia a sessão
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            // Senha incorreta
            $error_message = "Senha incorreta.";
        }
    } else {
        // Usuário não encontrado
        $error_message = "Usuário não encontrado. Por favor, registre-se.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleuser.css">
    <title>Login - DuoTec</title>
</head>
<body>
    <div class="container">
        <h1>Faça o seu login</h1>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Sua senha" required>
            <button type="submit">Fazer Login</button>
        </form>
        <?php
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
        <a href="register.php">Registre-se</a>
    </div>
</body>
</html>
