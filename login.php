<?php
session_start();
require 'ligaBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $codigo = $_POST["codigo"];
    $password = $_POST["password"];
    
    // Prepara a consulta SQL
    $stmt = $conn->prepare("SELECT nomeutilizador FROM utilizadores WHERE codutilizador = ? AND password = ?");
    $stmt->bind_param("ss", $codigo, $password);
    $stmt->execute();
    $stmt->store_result();
    
    // Verifica se o utilizador foi encontrado
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nome);
        $stmt->fetch();
        
        // Cria a sessão para o utilizador logado
        $_SESSION["user"] = ["codutilizador" => $codigo, "nomeutilizador" => $nome];
        
        // Redireciona para a página de entrada
        header("Location: entrada.php");
        exit();
    } else {
        // Se o utilizador não for encontrado
        $erro_login = "Utilizador não encontrado. <a href='registo.php'>Registe-se aqui</a>";
    }
    $stmt->close();
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Utilizadores</title>
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
            <h2>Login</h2>
            
            <?php if (isset($erro_login)) { ?>
                <div class="error-message"><?php echo $erro_login; ?></div>
            <?php } ?>
            
            <form method="POST" action="login.php">
                <label for="codigo">Código de Utilizador</label>
                <input type="text" id="codigo" name="codigo" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Entrar</button>
            </form>
        </div>
    </main>
</body>
</html>
