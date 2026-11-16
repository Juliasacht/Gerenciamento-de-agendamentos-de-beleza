<?php
session_start();
include 'config.php';
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit(); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php?novo=1'); exit(); }
$campos = ['data','hora','cliente_nome','servico','profissional','valor','duracao_minutos','descricao'];
$dados = [];
foreach ($campos as $campo) { $dados[$campo] = isset($_POST[$campo]) && is_string($_POST[$campo]) ? trim($_POST[$campo]) : ''; }
$token = is_string($_POST['csrf'] ?? null) ? $_POST['csrf'] : '';
$erro = '';
if (!isset($_SESSION['agendamento_csrf']) || !hash_equals($_SESSION['agendamento_csrf'], $token)) {
    $erro = 'A sessão deste formulário expirou. Confira os dados e tente novamente.';
}
$dataValida = DateTime::createFromFormat('!Y-m-d', $dados['data']);
if (!$erro && (!$dataValida || $dataValida->format('Y-m-d') !== $dados['data'] || !preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/', $dados['hora']))) {
    $erro = 'Informe uma data e um horário válidos.';
}
if (!$erro && ($dados['cliente_nome'] === '' || strlen($dados['cliente_nome']) > 100 || strlen($dados['profissional']) > 100 || !in_array($dados['servico'], ['corte','escova','hidratacao','coloracao','outro'], true))) {
    $erro = 'Preencha o cliente e selecione um serviço válido. Os nomes devem ter até 100 caracteres.';
}
$valor = $dados['valor'] === '' ? null : filter_var($dados['valor'], FILTER_VALIDATE_FLOAT);
$duracao = $dados['duracao_minutos'] === '' ? null : filter_var($dados['duracao_minutos'], FILTER_VALIDATE_INT);
if (!$erro && (($valor !== null && ($valor === false || $valor < 0 || $valor > 99999999.99)) || ($duracao !== null && ($duracao === false || $duracao < 1 || $duracao > 1440)) || strlen($dados['descricao']) > 5000)) {
    $erro = 'Confira o valor, a duração (1 a 1440 minutos) e as observações (até 5000 caracteres).';
}
if (!$erro) {
    include 'conexao.php';
    try {
        $stmt = mysqli_prepare($conexao, 'INSERT INTO agendamentos (usuario_id, data, hora, servico, descricao, cliente_nome, profissional, valor, duracao_minutos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'issssssdi', $_SESSION['usuario']['id'], $dados['data'], $dados['hora'], $dados['servico'], $dados['descricao'], $dados['cliente_nome'], $dados['profissional'], $valor, $duracao);
        mysqli_stmt_execute($stmt);
        unset($_SESSION['agendamento_csrf']);
        $_SESSION['agendamento_sucesso'] = 'Agendamento salvo com sucesso!';
        header('Location: dashboard.php');
        exit();
    } catch (mysqli_sql_exception $e) {
        error_log('Bela Agenda: erro ao salvar agendamento. Código: ' . $e->getCode());
        $erro = 'Não foi possível salvar agora. Seus dados foram mantidos; tente novamente.';
    }
}
$_SESSION['agendamento_erro'] = $erro;
$_SESSION['agendamento_valores'] = $dados;
header('Location: dashboard.php?novo=1');
exit();
