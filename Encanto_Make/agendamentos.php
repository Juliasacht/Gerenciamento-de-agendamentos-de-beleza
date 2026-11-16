<?php
session_start();
include 'conexao.php'; 
include 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}


$mensagem = ""; 

$_SESSION['token_agenda'] ??= bin2hex(random_bytes(32));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
    if (!hash_equals($_SESSION['token_agenda'], (string) ($_POST['token'] ?? ''))) {
        http_response_code(403);
        exit('Requisição inválida.');
    }

    $id = $_POST['excluir'];
    $usuario_id = $_SESSION['usuario']['id'];

    if (consultar($conexao, "DELETE FROM agendamentos WHERE id = ? AND usuario_id = ?", [(int) $id, $usuario_id])) {
        $mensagem = exibirMensagem("Agendamento excluído com sucesso!", "sucesso");
    } else {
        $mensagem = exibirMensagem("ERROR: Erro ao excluir agendamento.", "erro");
    }
}


$usuario_id = $_SESSION['usuario']['id'];
$result = consultar($conexao, "SELECT * FROM agendamentos WHERE usuario_id = ? ORDER BY data, hora", [$usuario_id])->get_result();

$servicos = [
    'corte' => 'Corte de cabelo',
    'escova' => 'Escova',
    'hidratacao' => 'Hidratação',
    'coloracao' => 'Coloração',
    'outro' => 'Outro',
];
$agendamentos = []; 
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $agendamentos[] = $row;
    }
}
criarTopo($SITE . " - Agendamentos");
?>

<main>
    <div class="agendamento painel-agenda">
        <div class="cabecalho-agenda">
            <h1>Meus agendamentos</h1>
            <a href="agendar.php" class="botao">Novo agendamento</a>
        </div>
        <?php echo $mensagem;?>

        <div class="tabela-rolagem" tabindex="0" aria-label="Lista de agendamentos">
        <table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Serviço</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($agendamentos)): ?>
            <tr>
                <td colspan="5">Nenhum agendamento encontrado.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($agendamentos as $agendamento): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($agendamento['data'])); ?></td>
                    <td><?php echo substr($agendamento['hora'], 0, 5); ?></td>
                    <td><?php echo escapar($servicos[$agendamento['servico']] ?? $agendamento['servico']); ?></td>
                    <td><?php echo escapar($agendamento['descricao'] ?? '') ?: '—'; ?></td>
                    <td>
                        <a href="editar_agendamento.php?id=<?php echo $agendamento['id']; ?>">Editar</a>
                        <form class="acao-agenda" action="agendamentos.php" method="post" onsubmit="return confirm('Excluir este agendamento?')">
                            <input type="hidden" name="token" value="<?php echo escapar($_SESSION['token_agenda']); ?>">
                            <button type="submit" name="excluir" value="<?php echo (int) $agendamento['id']; ?>">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
        </div>
    </div>
</main>

<?php criarRodape(); ?>