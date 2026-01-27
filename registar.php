<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'ligaBD.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $telefone = $_POST["telefone"];
    $data_nasc = $_POST["data_nasc"];
    $nif = $_POST["nif"]; // ⚠ nome do campo no HTML deve ser minúsculo

    // Validação simples do NIF
    if (!preg_match('/^\d{9}$/', $nif)) {
        $mensagem = "NIF inválido (9 dígitos).";
    } else {

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Verificar email duplicado
        $check = $conn->prepare("SELECT U_id FROM utilizador WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $mensagem = "Este email já está registado.";
        } else {

            // Inserir na base de dados
            $stmt = $conn->prepare(
                "INSERT INTO utilizador (nome, email, senha, telefone, data_nasc, nif)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssss", $nome, $email, $senhaHash, $telefone, $data_nasc, $nif);

            if (!$stmt->execute()) {
                die("ERRO MYSQL: " . $stmt->error);
            }

            // Guardar dados do utilizador na sessão
            $_SESSION["user"] = [
                "U_id" => $stmt->insert_id,
                "nome" => $nome,
                "email" => $email
            ];

            // Redirecionar para index.php
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar Utilizador</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="news">

<?php include 'headerlogin.php'; ?>

<main>
    <div class="login-container">
        <h2>Registar</h2>

        <?php if (!empty($mensagem)) { ?>
            <div class="error-message"><?php echo $mensagem; ?></div>
        <?php } ?>

        <form method="POST">
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
