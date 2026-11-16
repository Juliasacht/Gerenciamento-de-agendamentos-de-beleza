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
criarTopo($SITE . " - Cadastro de Usuário");
?>

<main id="conteudo">
    <div class="agendamento">
        <h2>Seu cuidado começa aqui.</h2><p class="section-description">Crie sua conta e tenha seus agendamentos sempre à mão.</p>
        <?php echo $mensagem;?>
        <form action="index.php" method="POST" class="agendamento-form">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" autocomplete="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" autocomplete="new-password" id="senha" name="senha" required>

            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</main>

<?php criarRodape(); ?>