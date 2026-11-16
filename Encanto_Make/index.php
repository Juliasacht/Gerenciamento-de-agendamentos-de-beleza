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
        $result = consultar($conexao, "SELECT id FROM usuarios WHERE email = ?", [$email])->get_result();

        if (mysqli_num_rows($result) > 0) {
            $mensagem = exibirMensagem("ERROR: Este e-mail já está cadastrado.", "erro");
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            if (consultar($conexao, "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)", [$nome, $email, $senha_hash])) {
                $mensagem = exibirMensagem("Cadastro realizado com sucesso!", "sucesso");
                header("Location: login.php");
                exit();
            } else {
                $mensagem = exibirMensagem("ERROR: Erro ao cadastrar.", "erro");
            }
        }
    }
}
criarTopo($SITE . " - Cadastro de Usuário");
?>

<main class="inicio">
    <section class="apresentacao">
        <h1>Agende seu próximo horário de beleza.</h1>
        <p>Escolha um serviço e acompanhe seus horários na Bela Agenda.</p>
        <a class="botao botao-secundario" href="login.php">Acessar minha agenda</a>
    </section>
    <div class="agendamento">
        <h2>Crie sua conta</h2>
        <?php echo $mensagem;?>
        <form action="index.php" method="POST" class="agendamento-form">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit">Criar conta</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
    </div>
</main>

<?php criarRodape(); ?>