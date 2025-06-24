<?php
    require_once 'conexao.php';

    date_default_timezone_set('America/Sao_Paulo');
    $msg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $procedimento = $_POST['procedimento'];
        $data = $_POST['data'];
        $hora = $_POST['hora'];

        // Verificar se o nome está cadastrado na tabela cliente
        $verifica_cliente = $pdo->prepare("SELECT COUNT(*) FROM cliente WHERE nome = ?");
        $verifica_cliente->execute([$nome]);
        $cliente_existe = $verifica_cliente->fetchColumn();

        if ($cliente_existe == 0) {
            $msg = "❌ Este nome não está cadastrado como cliente. Cadastre-se antes de agendar.";
        } else {
            // Verificar conflito de agendamento
            $verifica = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE data = ? AND hora = ?");
            $verifica->execute([$data, $hora]);
            $existe = $verifica->fetchColumn();

            if ($existe > 0) {
                $msg = "❌ Já existe um procedimento agendado neste horário!";
            } else {
                $sql = "INSERT INTO agendamentos (nome, procedimento, data, hora) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute([$nome, $procedimento, $data, $hora])) {
                    $msg = "✅ Agendamento cadastrado com sucesso!";
                } else {
                    $msg = "❌ Erro ao cadastrar agendamento.";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Íris Essence - Agendar</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="icon" href="../imgs/logo.jpg" type="image/x-icon">
</head>
<body class="cadastro-fundo">

<header>
<nav>
<ul>
<a href="../html/index.html">
<img src="../imgs/logo.jpg" class="logo" alt="Logo">
</a>
<li><a href="../html/index.html">HOME</a></li>
<li>
<a href="#">PROCEDIMENTOS FACIAIS</a>
<div class="submenu">
<a href="../html/limpezapele.html">Limpeza de Pele</a>
<a href="../html/labial.html">Preenchimento labial</a>
<a href="../html/microagulhamento.html">Microagulhamento</a>
<a href="../html/botoxfacial.html">Botox</a>
<a href="../html/acne.html">Tratamento para Acne</a>
<a href="../html/rinomodelacao.html">Rinomodelação</a>
</div>
</li>
<li>
<a href="#">PROCEDIMENTOS CORPORAIS</a>
<div class="submenu">
<a href="../html/massagemmodeladora.html">Massagem Modeladora</a>
<a href="../html/drenagemlinfatica.html">Drenagem Linfática</a>
<a href="../html/depilacaolaser.html">Depilação a Laser</a>
<a href="../html/depilacaocera.html">Depilação de cera</a>
<a href="../html/massagemrelaxante.html">Massagem Relaxante</a>
</div>
</li>
<li><a href="produtos.php">PRODUTOS</a></li>
|<li><a href="cadastro_agendamento.php">AGENDAR</a></li>|
<li><a href="login.php">LOGIN</a></li>|
<li><a href="cadastro_cliente.php">CADASTRO</a></li>|
</ul>
</nav>
</header>
<br>
<div class="formulario">
<fieldset>
<form action="cadastro_agendamento.php" method="POST">
<legend>Cadastro de Agendamento</legend>

<label for="nome">Nome do Cliente:</label>
<select name="nome" required>
    <option value="">Selecione um cliente</option>
    <?php
    $clientes = $pdo->query("SELECT nome FROM cliente ORDER BY nome");
    while ($c = $clientes->fetch()) {
        echo "<option value=\"{$c['nome']}\">{$c['nome']}</option>";
    }
    ?>
</select>

<label for="procedimento">Procedimento:</label>
<select name="procedimento" required>
    <option value="">Selecione</option>
    <optgroup label="Procedimentos Faciais">
        <option value="Limpeza de Pele">Limpeza de Pele</option>
        <option value="Preenchimento Labial">Preenchimento Labial</option>
        <option value="Microagulhamento">Microagulhamento</option>
        <option value="Botox">Botox</option>
        <option value="Tratamento para Acne">Tratamento para Acne</option>
        <option value="Rinomodelação">Rinomodelação</option>
    </optgroup>
    <optgroup label="Procedimentos Corporais">
        <option value="Massagem Modeladora">Massagem Modeladora</option>
        <option value="Drenagem Linfática">Drenagem Linfática</option>
        <option value="Depilação a Laser">Depilação a Laser</option>
        <option value="Depilação com Cera">Depilação com Cera</option>
        <option value="Massagem Relaxante">Massagem Relaxante</option>
    </optgroup>
</select>

<label for="data">Data:</label>
<input type="date" name="data" id="data" min="<?= date('Y-m-d') ?>" required>

<label for="hora">Hora:</label>
<select name="hora" id="hora" required>
    <option value="">Selecione uma data primeiro</option>
</select>

<div class="botoes">
    <button class="botao_cadastro" type="submit">Agendar</button>
    <button class="botao_limpeza" type="reset">Cancelar</button>
</div>

<br>
<button type="button" class="voltar-button" onclick="window.history.back();">Voltar</button>
</form>

<p><?= $msg ?></p>
<br>
</fieldset>
</div>

<footer class="l-footer">&copy; 2025 Íris Essence - Beauty Clinic. Todos os direitos reservados.</footer>

<script>
document.getElementById("data").addEventListener("change", function () {
    const data = this.value;

    fetch("horarios_disponiveis.php?data=" + data)
        .then(response => response.json())
        .then(horarios => {
            const horaSelect = document.getElementById("hora");
            horaSelect.innerHTML = "";

            if (horarios.length === 0) {
                horaSelect.innerHTML = '<option value="">Nenhum horário disponível</option>';
            } else {
                horarios.forEach(h => {
                    const opt = document.createElement("option");
                    opt.value = h;
                    opt.textContent = h;
                    horaSelect.appendChild(opt);
                });
            }
        });
});
</script>

</body>
</html>
