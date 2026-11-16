<?php
$SITE = "Bela Agenda";
$SITE_MENU = [
'Cadastrar' => ['index.php'],
'Login' => ['login.php'],
];

$SITE_MENU_LOGADO = [
    'Agendar' => ['agendar.php'],
    'Agendamentos' => ['agendamentos.php'],
    'Sair' => ['sair.php']
    ];

function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function paginaRestrita($logado = 0) {
    if($logado == 0)
    header('Location: login.php?error=Página Restrita, apenas usuários logados tem acesso. ');
}

function exibirMensagem($texto, $tipo = "sucesso") {
    $classe = ($tipo == "erro") ? "mensagem-erro" : "mensagem-sucesso";
    return "<div class='$classe' role='status'>" . escapar($texto) . "</div>";
}
function estaAtivo($pagina) {
    $paginaAtual = basename($_SERVER['PHP_SELF']);
    return $paginaAtual === $pagina ? 'ativo' : '';
}

function criarMenu($items) {
    $menu = "";
    foreach ($items as $nome => $info) {
        $menu .= '<a class="btn ' . estaAtivo($info[0]) . '" href="' . $info[0] . '">' . $nome . '</a>';
    }
    return $menu;
}


function criarTopo($titulo = "Bela Agenda") {
    global $SITE_MENU;
    global $SITE_MENU_LOGADO;

    if (isset($_SESSION['usuario'])) {
        $menu = criarMenu($SITE_MENU_LOGADO); 
    } else {
        $menu = criarMenu($SITE_MENU); 
    }

    $topo = '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $titulo . '</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <div class="logo-container">
                <span class="marca" aria-hidden="true">B</span>
                <a class="nome-marca" href="index.php">Bela Agenda</a>
            </div>
            <nav>
               ' . $menu . '    
            </nav>
        </header>';

    echo $topo;
}


function criarRodape() {
    $rodape = '<footer class="dflex-center">
    <p>Bela Agenda · Projeto acadêmico</p>
</footer>
   
</body>
</html>';

echo $rodape;
}

?>