<?php
$_SESSION['agendamento_csrf'] = $_SESSION['agendamento_csrf'] ?? bin2hex(random_bytes(32));
$valores = $_SESSION['agendamento_valores'] ?? [];
$erroAgendamento = $_SESSION['agendamento_erro'] ?? '';
unset($_SESSION['agendamento_valores'], $_SESSION['agendamento_erro']);
?>
<dialog class="appointment-dialog" id="novo-agendamento" aria-labelledby="appointment-title">
<header class="appointment-heading"><h2 id="appointment-title">Adicionar Novo Agendamento</h2><button type="button" class="appointment-close" data-close-appointment aria-label="Fechar janela">×</button></header>
<form action="agendar.php" method="post" class="appointment-form">
<input type="hidden" name="csrf" value="<?php echo escapar($_SESSION['agendamento_csrf']); ?>">
<?php if ($erroAgendamento): ?><div class="mensagem-erro" role="alert"><?php echo escapar($erroAgendamento); ?></div><?php endif; ?>
<?php if ($demonstracao): ?><p class="appointment-demo">Prévia do formulário. Entre na sua conta para salvar.</p><?php endif; ?>
<div class="appointment-row">
<div class="appointment-field"><label for="appointment-data">Data</label><input id="appointment-data" type="date" name="data" value="<?php echo escapar($valores['data'] ?? $hoje); ?>" required></div>
<div class="appointment-field"><label for="appointment-hora">Hora</label><input id="appointment-hora" type="time" name="hora" value="<?php echo escapar($valores['hora'] ?? ''); ?>" required></div>
</div>
<div class="appointment-field"><label for="appointment-cliente">Cliente</label><input id="appointment-cliente" name="cliente_nome" type="text" list="appointment-clientes" maxlength="100" placeholder="Selecione ou digite o nome..." value="<?php echo escapar($valores['cliente_nome'] ?? ''); ?>" required autocomplete="off"><datalist id="appointment-clientes"><option value="<?php echo escapar($nome); ?>"><?php foreach (array_unique(array_filter(array_column($agendamentos, 'cliente_nome'))) as $cliente): ?><option value="<?php echo escapar($cliente); ?>"><?php endforeach; ?></datalist></div>
<div class="appointment-service-row">
<div class="appointment-field"><label for="appointment-servico">Serviço</label><select id="appointment-servico" name="servico" required><option value="">Selecione o serviço</option><?php foreach ($servicos as $codigo => $rotulo): ?><option value="<?php echo escapar($codigo); ?>" <?php echo ($valores['servico'] ?? '') === $codigo ? 'selected' : ''; ?>><?php echo escapar($rotulo); ?></option><?php endforeach; ?></select></div>
<fieldset class="appointment-cost"><legend>Valor / Duração</legend><div><span>R$</span><input aria-label="Valor em reais, opcional" name="valor" type="number" min="0" max="99999999.99" step="0.01" placeholder="0,00" value="<?php echo escapar($valores['valor'] ?? ''); ?>"><span>·</span><input aria-label="Duração em minutos, opcional" name="duracao_minutos" type="number" min="1" max="1440" placeholder="45" value="<?php echo escapar($valores['duracao_minutos'] ?? ''); ?>"><span>min</span></div></fieldset>
</div>
<div class="appointment-field"><label for="appointment-profissional">Profissional <small>(opcional)</small></label><input id="appointment-profissional" type="text" name="profissional" list="appointment-profissionais" maxlength="100" placeholder="Selecione ou digite o nome..." value="<?php echo escapar($valores['profissional'] ?? ''); ?>"><datalist id="appointment-profissionais"><?php foreach (array_unique(array_filter(array_column($agendamentos, 'profissional'))) as $prof): ?><option value="<?php echo escapar($prof); ?>"><?php endforeach; ?></datalist></div>
<div class="appointment-field"><label for="appointment-observacoes">Observações</label><textarea id="appointment-observacoes" name="descricao" rows="2" maxlength="5000" placeholder="Observações adicionais..."><?php echo escapar($valores['descricao'] ?? ''); ?></textarea></div>
<div class="appointment-actions"><button class="appointment-cancel" type="button" data-close-appointment>CANCELAR</button><?php if ($demonstracao): ?><a class="appointment-save" href="login.php">ENTRAR PARA SALVAR</a><?php else: ?><button class="appointment-save" type="submit">SALVAR</button><?php endif; ?></div>
</form></dialog>
<script src="agendamento.js" defer></script>
