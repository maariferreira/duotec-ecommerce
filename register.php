<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teste";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;

    // Validate inputs
    if ($full_name && $email && $password && $phone) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $phone);

        if ($stmt->execute()) {
            // Display a success message and redirect to index.php
            echo "<script>
                    alert('Registro concluído com sucesso!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            // Display an error message
            echo "<script>
                    alert('Erro ao registrar: " . $stmt->error . "');
                    window.history.back();
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Todos os campos são obrigatórios.');
                window.history.back();
              </script>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleuser.css">
    <title>Registro - DuoTec</title>
</head>
<body>
    <div class="container">
        <h1>Faça o seu registro</h1>
        <form action="register.php" method="post">
            <input type="text" name="full_name" placeholder="Nome Completo" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Senha" required>
            <input type="tel" name="phone" placeholder="Telefone" required>
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>
