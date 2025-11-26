<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo de utilizadores</title>
</head>
<body>
<?php


require 'ligaBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST["codigo"];
    $password = $_POST["password"];
   
    $stmt = $conn->prepare("SELECT nomeutilizador FROM utilizadores WHERE codutilizador = ? AND password = ?");
    $stmt->bind_param("ss", $codigo, $password);
    $stmt->execute();
    $stmt->store_result();
   
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nome);
        $stmt->fetch();
        $_SESSION["user"] = ["codutilizador" => $codigo, "nomeutilizador" => $nome];
        header("Location: entrada.php");
        exit();
    } else {
        echo "<h3>Utilizador não encontrado. <a href='registo.php'>Registe-se aqui</a></h3>";
    }
    $stmt->close();
}
$pdo = null;
?>

   
</body>
</html>
