<?php
session_start();
include 'conexao.php';
include 'config.php';
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
        $mensagem = exibirMensagem('Preencha seu nome, um e-mail válido e sua senha.', 'erro');
    } elseif (strlen($nome) > 100 || strlen($email) > 100) {
        $mensagem = exibirMensagem('Nome e e-mail devem ter até 100 caracteres.', 'erro');
    } else {
        try {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conexao, 'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'sss', $nome, $email, $hash);
            mysqli_stmt_execute($stmt);
            header('Location: login.php?cadastro=sucesso');
            exit();
        } catch (mysqli_sql_exception $e) {
            $texto = $e->getCode() === 1062 ? 'Este e-mail já está cadastrado. Entre na sua conta.' : 'Não foi possível criar sua conta agora. Tente novamente em instantes.';
            $mensagem = exibirMensagem($texto, 'erro');
            error_log('Bela Agenda: erro no cadastro. Código: ' . $e->getCode());
        }
    }
}
criarTopoAcesso($SITE . " - Criar conta");
?>
<h2 id="form-title">Comece sua Bela Agenda</h2>
<p class="access-subtitle">Crie sua conta e organize seus próximos momentos de cuidado.</p>
<?php echo $mensagem; ?>
<form action="index.php" method="POST" class="access-form">
<div class="access-field"><label for="nome">NOME COMPLETO</label><input type="text" id="nome" name="nome" autocomplete="name" placeholder="Como podemos chamar você?" required></div>
<div class="access-field"><label for="email">EMAIL</label><input type="email" id="email" name="email" autocomplete="email" placeholder="voce@exemplo.com.br" required></div>
<div class="access-field"><label for="senha">SENHA</label><input type="password" id="senha" name="senha" autocomplete="new-password" placeholder="Crie sua senha" required></div>
<button type="submit">CRIAR MINHA CONTA</button>
</form>
<p class="access-switch">Já possui uma conta? <a href="login.php">Entrar no sistema</a></p>
<?php criarRodapeAcesso(); ?>
