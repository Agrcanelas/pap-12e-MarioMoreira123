<?php
// Defina os parâmetros de ligação com o base de dados
$servidor = "localhost";    // Nome do servidor do base de dados (geralmente localhost)
$utilizador = "root";          // Nome de utilizadores padrão do MySQL (no XAMPP é root)
$senha = "";                // Senha do MySQL (no XAMPP é uma senha em branco)
$base = "ajudar";     // Nome da base de dados

// Estabelecendo a ligação com a base de dados usando mysqli
$conn = new mysqli($servidor, $utilizador, $senha, $base);

// Verificando se houve erro na ligaçao
if ($conn->connect_error) {
    // Caso haja erro, exibe a mensagem e para o script
    die("Falha na ligação com o base de dados: " . $conn->connect_error);
}

// Caso contrário, a ligação foi bem-sucedida
// Aqui você pode realizar operações no base de dados, como inserções, consultas, etc.

echo "<script>alert('Ligação estabelecida com sucesso!');</script>";
?>
