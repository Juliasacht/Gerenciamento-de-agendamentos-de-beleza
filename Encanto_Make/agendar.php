<?php
session_start();
include 'conexao.php'; 
include 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}


$mensagem = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST["data"];
    $hora = $_POST["hora"];
    $servico = $_POST["servico"];
    $descricao = $_POST["descricao"];
    $usuario_id = $_SESSION['usuario']['id']; 

    if (empty($data) || empty($hora) || empty($servico)) {
        $mensagem = exibirMensagem("ERROR: Preencha todos os campos obrigatórios.", "erro");
    } else {
        $sql = "INSERT INTO agendamentos (usuario_id, data, hora, servico, descricao) 
                VALUES ('$usuario_id', '$data', '$hora', '$servico', '$descricao')";

        if (mysqli_query($conexao, $sql)) {
            header("Location: agendamentos.php");
            exit();
        } else {
            $mensagem = exibirMensagem("ERROR: Erro ao agendar: " . mysqli_error($conexao), "erro");
        }
    }
}
criarTopo($SITE . " - Agendar Horário");
?>

<main id="conteudo">
    <div class="agendamento">
        <h2>Reserve seu momento.</h2><p class="section-description">Escolha o serviço, a data e o horário do seu atendimento.</p>
        <?php echo $mensagem;?>
        <form action="agendar.php" method="POST" class="agendamento-form">
            <label for="data">Data:</label>
            <input type="date" id="data" name="data" required>

            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora" required>

            <label for="servico">Serviço:</label>
            <select id="servico" name="servico" required>
                <option value="corte">Corte de Cabelo</option>
                <option value="escova">Escova</option>
                <option value="hidratacao">Hidratação</option>
                <option value="coloracao">Coloração</option>
                <option value="outro">Outro</option>
            </select>

            <label for="descricao">Descrição (opcional):</label>
            <textarea id="descricao" name="descricao" rows="4"></textarea>

            <button type="submit">Agendar</button>
        </form>
    </div>
</main>

<?php criarRodape(); ?>