<?php
session_start();

// Se não estiver logado, volta ao login
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Nome do utilizador
$nome = $_SESSION["user"]["nome"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Utilizador</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Mostra o nome no canto superior direito */
        .user-info {
            position: absolute;
            top: 15px;
            right: 20px;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body class="news">

    <?php 
    include 'headerlogin.php'; 
    ?>

    <!-- Mostrar nome do utilizador no canto superior direito -->
    <div class="user-info">
        👤 <?php echo htmlspecialchars($nome); ?>
    </div>

    <main>
        <h2>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h2>
        <p>A sua conta foi criada com sucesso.</p>
    </main>

</body>
</html>
