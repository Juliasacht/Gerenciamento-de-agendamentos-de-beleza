<?php
$SITE = "Bela Agenda";
$SITE_MENU = ['Criar conta' => ['index.php'], 'Entrar' => ['login.php']];
$SITE_MENU_LOGADO = ['Novo horário' => ['agendar.php'], 'Minha agenda' => ['agendamentos.php'], 'Sair' => ['sair.php']];
function escapar($valor) { return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8'); }
function paginaRestrita($logado = 0) {
    if (!$logado) { header('Location: login.php'); exit(); }
}
function exibirMensagem($texto, $tipo = "sucesso") {
    $classe = $tipo === "erro" ? "mensagem-erro" : "mensagem-sucesso";
    return '<div class="' . $classe . '" role="status">' . escapar($texto) . '</div>';
}
function estaAtivo($pagina) {
    return basename($_SERVER['PHP_SELF']) === $pagina ? 'ativo' : '';
}
function criarMenu($items) {
    $menu = "";
    foreach ($items as $nome => $info) {
        $ativo = estaAtivo($info[0]);
        $menu .= '<a class="nav-link ' . $ativo . '" href="' . escapar($info[0]) . '"' . ($ativo ? ' aria-current="page"' : '') . '>' . escapar($nome) . '</a>';
    }
    return $menu;
}
function criarTopo($titulo = "Bela Agenda") {
    global $SITE_MENU, $SITE_MENU_LOGADO;
    $logado = isset($_SESSION['usuario']);
    $menu = criarMenu($logado ? $SITE_MENU_LOGADO : $SITE_MENU);
    $destino = $logado ? 'agendamentos.php' : 'login.php';
    echo '<!DOCTYPE html>
<html lang="pt-BR"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#513448">
<meta name="description" content="Bela Agenda: organize seus horários e reserve seu próximo momento de cuidado.">
<title>' . escapar($titulo) . '</title>
<link rel="icon" href="imagens/bela-agenda.svg" type="image/svg+xml">
<link rel="stylesheet" href="style.css">
</head><body>
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>
<header class="site-header">
<a class="brand" href="' . $destino . '"><img src="imagens/bela-agenda.svg" width="44" height="44" alt=""><span>Bela <strong>Agenda</strong><small>SEU TEMPO, SEU CUIDADO</small></span></a>
<nav aria-label="Menu principal">' . $menu . '</nav>
</header>
<section class="intro" aria-labelledby="intro-title">
<span class="eyebrow">BELEZA EM CADA DETALHE</span>
<h1 id="intro-title">Um tempo só <em>para você.</em></h1>
<p>Seus próximos momentos de cuidado começam aqui.</p>
<div class="intro-tags"><span>Horários organizados</span><span>Mais praticidade</span><span>Seu cuidado em dia</span></div>
</section>';
}
function criarRodape() {
    echo '<footer><span class="footer-brand">Bela Agenda</span><p>Mais cuidado com você. Mais leveza para sua rotina.</p><small>&copy; ' . date('Y') . ' Bela Agenda</small></footer></body></html>';
}
