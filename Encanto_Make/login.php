<?php
session_start();
include 'conexao.php';
include 'config.php';
$mensagem = isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso' ? exibirMensagem('Conta criada com sucesso! Entre com seu e-mail e senha.') : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    try {
        $stmt = mysqli_prepare($conexao, 'SELECT id, nome, email, senha FROM usuarios WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id'=>$usuario['id'], 'nome'=>$usuario['nome'], 'email'=>$usuario['email']];
            header('Location: dashboard.php');
            exit();
        }
        $mensagem = exibirMensagem('E-mail ou senha incorretos.', 'erro');
    } catch (mysqli_sql_exception $e) {
        error_log('Bela Agenda: erro no login. Código: ' . $e->getCode());
        $mensagem = exibirMensagem('Não foi possível entrar agora. Tente novamente em instantes.', 'erro');
    }
}
criarTopoAcesso($SITE . " - Entrar");
?>
<h2 id="form-title">Bem-vinda de volta</h2>
<p class="access-subtitle">Acesse sua conta para gerenciar seus agendamentos.</p>
<?php echo $mensagem; ?>
<form action="login.php" method="POST" class="access-form">
<div class="access-field"><label for="email">EMAIL</label><input type="email" id="email" name="email" autocomplete="email" placeholder="voce@exemplo.com.br" required></div>
<div class="access-field"><label for="senha">SENHA</label><input type="password" id="senha" name="senha" autocomplete="current-password" placeholder="Digite sua senha" required></div>
<button type="submit">ENTRAR NO SISTEMA</button>
</form>
<p class="access-switch">Ainda não possui acesso? <a href="index.php">Cadastrar-se</a></p>
<?php criarRodapeAcesso(); ?>
