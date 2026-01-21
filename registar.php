<?php
session_start();
require 'ligaBD.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $password = $_POST["senha"];
    $telefone = $_POST["telefone"];
    $data_nasc = $_POST["data_nasc"];
    $nif = $_POST["nif"];

    if (!preg_match('/^\d{9}$/', $nif)) {
        $mensagem = "NIF inválido (9 dígitos).";
    } else {
        $senhaHash = password_hash($password, PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT email FROM utilizador WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $mensagem = "Este email já está registado.";
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO utilizador (nome, email, senha, telefone, data_nasc, nif) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssss", $nome, $email, $senhaHash, $telefone, $data_nasc, $nif);

            if ($stmt->execute()) {
                header("Location: entradalogin.php");
                exit();
            } else {
                $mensagem = "O registo não funcionou: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo de Utilizadores</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="nav">  
        <ul>
            <li class="home"><a href="index.php">Home</a></li>
            <li class="about"><a href="about.php">Sobre</a></li>
            <li class="donate"><a href="donate.php">Doar</a></li>
            <li class="login"><a href="login.php">Login</a></li>  
        </ul>     
    </div>
</header>

<main>
    <div class="login-container">
        <h2>Registar</h2>

        <?php if (!empty($mensagem)) { ?>
            <div class="error-message"><?php echo $mensagem; ?></div>
        <?php } ?>

        <form method="POST" action="entradalogin.php">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Password</label>
            <input type="password" id="senha" name="senha" required>

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" required>

            <label for="data_nasc">Data de Nascimento</label>
            <input type="date" id="data_nasc" name="data_nasc" required>

            <label for="nif">NIF</label>
            <input type="text" id="nif" name="nif" maxlength="9" required>

            <button type="submit">Registar</button>
        </form>

        <div class="register-link">
            <p>Já tem conta? <a href="login.php">Carregue aqui</a></p>
        </div>
    </div>
</main>

</body>
</html>
