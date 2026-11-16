<?php
session_start();
include 'conexao.php'; 
include 'config.php';


$mensagem = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = exibirMensagem("ERROR: Todos os campos são obrigatórios.", "erro");
    } else {
        $sql = "SELECT id FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($result) > 0) {
            $mensagem = exibirMensagem("ERROR: Este e-mail já está cadastrado.", "erro");
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";

            if (mysqli_query($conexao, $sql)) {
                $mensagem = exibirMensagem("Cadastro realizado com sucesso!", "sucesso");
                header("Location: login.php");
                exit();
            } else {
                $mensagem = exibirMensagem("ERROR: Erro ao cadastrar: " . mysqli_error($conexao), "erro");
            }
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
