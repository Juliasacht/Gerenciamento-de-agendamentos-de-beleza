<?php
session_start();
include 'config.php';
date_default_timezone_set('America/Sao_Paulo');
$demonstracao = isset($_GET['demo']) && $_GET['demo'] === '1';
$servicos = ['corte'=>'Corte','escova'=>'Escova','hidratacao'=>'Hidratação','coloracao'=>'Coloração','outro'=>'Outro'];
if (!$demonstracao && !isset($_SESSION['usuario'])) { header('Location: login.php'); exit(); }
$hoje = date('Y-m-d');
if ($demonstracao) {
    $nome = 'Maria';
    $agendamentos = [
        ['id'=>0,'data'=>$hoje,'hora'=>'08:00:00','servico'=>'corte','descricao'=>''],
        ['id'=>0,'data'=>$hoje,'hora'=>'10:00:00','servico'=>'escova','descricao'=>''],
        ['id'=>0,'data'=>$hoje,'hora'=>'10:30:00','servico'=>'hidratacao','descricao'=>''],
        ['id'=>0,'data'=>$hoje,'hora'=>'14:00:00','servico'=>'coloracao','descricao'=>'']
    ];
} else {
    include 'conexao.php';
    $nome = $_SESSION['usuario']['nome'];
    $stmt = mysqli_prepare($conexao, 'SELECT id, data, hora, servico, descricao FROM agendamentos WHERE usuario_id = ? ORDER BY data, hora');
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['usuario']['id']);
    mysqli_stmt_execute($stmt);
    $agendamentos = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}
$agendaHoje = array_values(array_filter($agendamentos, function($a) use ($hoje) { return $a['data'] === $hoje; }));
$contagens = [];
foreach ($agendamentos as $a) { $contagens[$a['servico']] = ($contagens[$a['servico']] ?? 0) + 1; }
arsort($contagens);
$total = count($agendamentos);
$dias = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
$meses = [1=>'janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
$primeiroNome = explode(' ', trim($nome))[0];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Bela Agenda - Dashboard</title>
<link rel="icon" href="imagens/bela-agenda.svg" type="image/svg+xml"><link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>
<aside class="dashboard-sidebar">
<a class="dashboard-brand" href="dashboard.php<?php echo $demonstracao ? '?demo=1' : ''; ?>"><span aria-hidden="true">✂</span>Bela Agenda</a>
<p class="nav-caption">NAVEGAÇÃO</p>
<nav aria-label="Menu principal">
<a class="dashboard-nav active" href="#conteudo" aria-current="page"><span aria-hidden="true">⌂</span>Dashboard</a>
<a class="dashboard-nav" href="#perfil"><span aria-hidden="true">♙</span>Minha conta</a>
<a class="dashboard-nav" href="#servicos"><span aria-hidden="true">✂</span>Serviços</a>
<a class="dashboard-nav" href="<?php echo $demonstracao ? 'login.php' : 'agendamentos.php'; ?>"><span aria-hidden="true">▦</span>Agendamentos</a>
<a class="dashboard-nav" href="#resumo"><span aria-hidden="true">▥</span>Resumo</a>
</nav>
<a class="dashboard-exit" href="<?php echo $demonstracao ? 'login.php' : 'sair.php'; ?>"><?php echo $demonstracao ? 'Entrar na minha conta →' : 'Sair da conta →'; ?></a>
</aside>
<div class="dashboard-workspace">
<header class="dashboard-header">
<div><h1>Olá, <?php echo escapar($primeiroNome); ?>!</h1><p><?php echo $dias[date('w')] . ', ' . date('d') . ' de ' . $meses[(int)date('n')]; ?></p></div>
<a class="dashboard-profile" href="#perfil"><span class="dashboard-avatar"><?php echo escapar(function_exists('mb_substr') ? mb_substr($nome, 0, 1, 'UTF-8') : substr($nome, 0, 1)); ?></span><span><strong><?php echo escapar($nome); ?></strong><small><?php echo $demonstracao ? 'Demonstração' : 'Minha conta'; ?></small></span></a>
</header>
<main class="dashboard-content" id="conteudo">
<?php if ($demonstracao): ?><p class="demo-notice">Prévia do dashboard · Dados ilustrativos. <a href="login.php">Entre para ver seus agendamentos.</a></p><?php endif; ?>
<div class="dashboard-grid">
<div class="dashboard-primary">
<section class="dashboard-card today-card" aria-labelledby="today-title">
<div class="dashboard-card-heading"><h2 id="today-title">Agendamentos de hoje</h2><span class="count-badge"><?php echo count($agendaHoje); ?> horários</span></div>
<div class="dashboard-table-scroll"><table><thead><tr><th>Horário</th><th>Cliente</th><th>Serviço</th><th>Agenda</th><th><span class="sr-only">Detalhes</span></th></tr></thead><tbody>
<?php if (!$agendaHoje): ?><tr><td colspan="5" class="empty-state">Você ainda não tem horários para hoje.<br>Que tal reservar seu próximo cuidado?</td></tr><?php endif; ?>
<?php foreach ($agendaHoje as $a): ?><tr><td><strong><?php echo escapar(substr($a['hora'],0,5)); ?></strong></td><td><?php echo escapar($primeiroNome); ?></td><td><?php echo escapar($servicos[$a['servico']] ?? $a['servico']); ?></td><td><span class="dashboard-status">Agendado</span></td><td><a class="detail-link" aria-label="Ver agendamento das <?php echo escapar(substr($a['hora'],0,5)); ?>" href="<?php echo $demonstracao ? 'login.php' : 'editar_agendamento.php?id=' . (int)$a['id']; ?>">›</a></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<a class="dashboard-new" href="<?php echo $demonstracao ? 'login.php' : 'agendar.php'; ?>"><span aria-hidden="true">＋</span> NOVO AGENDAMENTO</a>
<section class="dashboard-card summary-card" id="resumo"><div><span>Total de agendamentos</span><strong><?php echo $total; ?></strong></div><div><span>Horários hoje</span><strong><?php echo count($agendaHoje); ?></strong></div><div><span>Serviços escolhidos</span><strong><?php echo count($contagens); ?></strong></div></section>
</div>
<div class="dashboard-secondary">
<section class="dashboard-card account-card" id="perfil"><div class="dashboard-card-heading"><h2>Seu espaço</h2><span>Minha conta</span></div><div class="account-overview"><span class="dashboard-avatar large"><?php echo escapar(function_exists('mb_substr') ? mb_substr($nome,0,1,'UTF-8') : substr($nome,0,1)); ?></span><div><strong><?php echo escapar($nome); ?></strong><p>Todos os seus momentos de cuidado em um só lugar.</p></div></div></section>
<section class="dashboard-card popular-card" id="servicos"><div class="dashboard-card-heading"><h2>Serviços populares</h2><span>Na sua agenda</span></div>
<?php if (!$contagens): ?><p class="dashboard-empty">Seus serviços mais escolhidos aparecerão aqui após o primeiro agendamento.</p><?php endif; ?>
<?php foreach ($contagens as $codigo => $quantidade): ?><div class="popular-service"><div><span><?php echo escapar($servicos[$codigo] ?? $codigo); ?></span><strong><?php echo $quantidade; ?></strong></div><div class="service-track"><span style="width:<?php echo round($quantidade / max(1,$total) * 100); ?>%"></span></div></div><?php endforeach; ?>
</section>
</div></div></main></div></body></html>
