<?php
declare(strict_types=1);

$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    http_response_code(500);
    exit('Configurazione mancante.');
}
require $configFile;

function redirect_esito(string $esito): void {
    header('Location: /contatti/?esito=' . $esito);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_esito('errore');
}

if (!empty($_POST['sito_web'])) {
    redirect_esito('ok');
}

$nome = trim((string)($_POST['nome'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$messaggio = trim((string)($_POST['messaggio'] ?? ''));
$privacy = isset($_POST['privacy']);

if ($nome === '' || $messaggio === '' || !$privacy || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_esito('errore');
}

$ip = $_SERVER['REMOTE_ADDR'] ?? null;

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare(
        'INSERT INTO contatti_lead (nome, email, messaggio, consenso_privacy, consenso_ip, privacy_versione)
         VALUES (:nome, :email, :messaggio, 1, :ip, :privacy_versione)'
    );
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'messaggio' => $messaggio,
        'ip' => $ip,
        'privacy_versione' => defined('PRIVACY_VERSION') ? PRIVACY_VERSION : null,
    ]);
} catch (Throwable $e) {
    error_log('[contatti.php] ' . $e->getMessage());
    redirect_esito('errore');
}

if (defined('NOTIFY_EMAIL') && NOTIFY_EMAIL) {
    $subject = 'Nuovo messaggio da ingciambrone.it';
    $body = "Nome: $nome\nEmail: $email\n\nMessaggio:\n$messaggio\n\nIP: $ip";
    $headers = 'Reply-To: ' . $email . "\r\n" . 'Content-Type: text/plain; charset=UTF-8';
    @mail(NOTIFY_EMAIL, $subject, $body, $headers);
}

redirect_esito('ok');
