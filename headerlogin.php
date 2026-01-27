<?php
// Inicia sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <div class="nav">
        <!-- LOGO -->
        <div class="logo">
            <a href="index.php">
                <img src="imagens/logotipopap3.jpg" alt="Logotipo">
            </a>
        </div>

        <!-- NOME DO UTILIZADOR -->
        <?php if (isset($_SESSION["user"])): ?>
            <div class="user-name">
                <?php echo htmlspecialchars($_SESSION["user"]["nome"]); ?>
            </div>
        <?php endif; ?>

        <!-- MENU -->
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">Sobre</a></li>
            <li><a href="donate.php">Doar</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</header>
