<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$dbname = getenv('DB_NAME') ?: 'bela_agenda';
$conexao = null;
try {
    $conexao = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conexao, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('Bela Agenda: falha na conexão com o banco. Código: ' . $e->getCode());
    http_response_code(503);
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Bela Agenda</title><link rel="stylesheet" href="style.css"></head><body><main><section class="agendamento" style="margin-top:60px"><h2>Voltamos em instantes</h2><p>Não foi possível acessar a agenda agora. Tente novamente em alguns minutos.</p><a href="login.php">Tentar novamente</a></section></main></body></html>';
    exit();
}
