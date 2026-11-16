<?php
session_start();
include 'conexao.php'; 
include 'config.php';


$mensagem = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if (empty($email) || empty($senha)) {
        $mensagem = exibirMensagem("ERROR: Todos os campos são obrigatórios.", "erro");
    } else {
        $sql = "SELECT id, nome, senha FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);

            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $email
                ];

                header("Location: agendar.php"); 
                exit();
            } else {
                $mensagem = exibirMensagem("ERROR: Senha incorreta.", "erro");
            }
        } else {
            $mensagem = exibirMensagem("ERROR: E-mail não cadastrado.", "erro");
        }
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
