<?php
// ligaBD.php
// Ficheiro único que fornece: ligação à base de dados (PDO) + formulário de login + processamento.
// Atualize as constantes DB_* com as suas credenciais antes de usar.

// --- Configuração ---
const DB_HOST = '127.0.0.1';
const DB_NAME = 'sua_base_dados';
const DB_USER = 'seu_utilizador';
const DB_PASS = 'sua_senha';
const DB_CHARSET = 'utf8mb4';

// Opcional: página para onde enviar depois do login bem sucedido
const AFTER_LOGIN = 'dashboard.php';

// --- Inicialização ---
session_start();
// Protege a sessão: (ajuste conforme necessário)
if (empty($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Função para obter ligação PDO
function getPDO() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Não exiba erros de BD em produção
        http_response_code(500);
        echo 'Erro ao ligar à base de dados.';
        error_log('DB connection error: ' . $e->getMessage());
        exit;
    }
}

// --- CSRF token simples ---
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// --- Processamento do POST (login) ---
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

    if (!validate_csrf($token)) {
        $errors[] = 'Pedido inválido (CSRF).';
    }

    if ($username === '' || $password === '') {
        $errors[] = 'Preencha o nome de utilizador e a palavra-passe.';
    }

    if (empty($errors)) {
        $pdo = getPDO();

        // Procure pelo utilizador. Ajuste a coluna (username/email) conforme a sua tabela.
        $sql = 'SELECT id, username, password_hash, active FROM users WHERE username = :username LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && !empty($user['password_hash'])) {
            // password_hash deve ser gerada no registo com password_hash(\$password, PASSWORD_DEFAULT)
            if (password_verify($password, $user['password_hash'])) {
                // Opcional: verificar se a conta está ativa
                if (isset($user['active']) && !$user['active']) {
                    $errors[] = 'Conta desativada. Contacte o administrador.';
                } else {
                    // Login bem-sucedido
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    // pode guardar outros dados necessários na sessão

                    // Redireciona para a página definida
                    header('Location: ' . AFTER_LOGIN);
                    exit;
                }
            } else {
                $errors[] = 'Nome de utilizador ou palavra-passe incorretos.';
            }
        } else {
            $errors[] = 'Nome de utilizador ou palavra-passe incorretos.';
        }
    }
}

// --- Formulário de login (mostrado em GET ou se houver erros) ---
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        .box { max-width: 420px; margin: 0 auto; border: 1px solid #ddd; padding: 1rem 1.2rem; border-radius: 6px; }
        .error { color: #9b0000; }
        label { display:block; margin-top:0.8rem; }
        input[type=text], input[type=password] { width:100%; padding:8px; box-sizing:border-box; }
        button { margin-top:1rem; padding:8px 12px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Iniciar sessão</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

        <label for="username">Nome de utilizador</label>
        <input id="username" name="username" type="text" autocomplete="username" required value="<?php echo isset($username) ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : ''; ?>">

        <label for="password">Palavra-passe</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>

        <button type="submit">Entrar</button>
    </form>

    <p style="margin-top:1rem; font-size:0.9rem; color:#666;">Não tem conta? Registe-se.</p>
</div>
</body>
</html>
