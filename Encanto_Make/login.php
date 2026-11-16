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
criarTopo($SITE . " - Login");
?>

<main id="conteudo">
    <div class="agendamento">
        <h2>Que bom ter você aqui.</h2><p class="section-description">Entre na sua conta para cuidar dos seus próximos horários.</p>
        <?php echo $mensagem;?>
        <form action="login.php" method="POST" class="agendamento-form">
            <label for="email">E-mail:</label>
            <input type="email" autocomplete="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" autocomplete="current-password" id="senha" name="senha" required>

            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="index.php">Cadastre-se</a></p>
    </div>
</main>

<?php criarRodape(); ?>