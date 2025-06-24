<?php
session_start();
require 'conexao.php';



$cliente = null;

// Processa alteração de dados se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cliente']) && isset($_POST['acao']) && $_POST['acao'] === 'alterar') {
    $id_cliente = $_POST['id_cliente'];
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $email = trim($_POST['email']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $genero = trim($_POST['genero']);
    

    $sql = "UPDATE cliente SET nome = :nome, telefone = :telefone, endereco = :endereco, email = :email, data_nascimento = :data_nascimento,
    genero = :genero";

    $sql .= " WHERE id_cliente = :id_cliente";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente alterado com sucesso!'); window.location.href='alterar_cliente.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao alterar cliente!'); window.location.href='alterar_cliente.php';</script>";
        exit();
    }
}

// Processa busca de usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['busca_cliente']) && (!isset($_POST['acao']) || $_POST['acao'] !== 'alterar')) {
    $busca = trim($_POST['busca_cliente']);

    if (is_numeric($busca)) {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM cliente WHERE nome LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        echo "<script>alert('Cliente não encontrado!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Íris Essence - Alterar Cliente</title>
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
            <li><a href="../html/produtos.html">PRODUTOS</a></li>|
            <li><a href="../html/login.php">LOGIN</a></li>|
            <li><a href="../html/cadastro.html">CADASTRO</a></li>|

            <div class="logout">
                <form action = "logout.php" method= "POST">
                <button type="submit">Logout</button>
            </div>
        </ul>
    </nav>
</header>

<br>

<div class="formulario">
    <fieldset>
        <!-- Formulário para buscar usuário pelo ID ou Nome -->
        <form action="alterar_cliente.php" method="POST">
            <legend>Alterar Cliente</legend>
            <label for="busca_cliente">Digite o ID ou Nome do cliente:</label>
            <input type="text" id="busca_cliente" name="busca_cliente" required>
            <div id="sugestoes"></div>
            <button class="botao_cadastro" type="submit">Buscar</button>
        </form>

        <?php if ($cliente): ?>
            <!-- Formulário para alterar usuário -->
            <form action="alterar_cliente.php" method="POST">
                <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">
                <input type="hidden" name="acao" value="alterar">

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>" required>

                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>" required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($cliente['data_nascimento']) ?>" required>

                <label for="genero">Gênero:</label>
                <input type="text" id="genero" name="genero" value="<?= htmlspecialchars($cliente['genero']) ?>" required>


                <div class="botoes">
                    <button class="botao_cadastro" type="submit">Alterar</button>
                    <button class="botao_limpeza" type="reset">Cancelar</button>
                </div>

            <button type="button" class="voltar-button" onclick="window.location.href='principal.php'">Voltar</button>
            </form>
        <?php endif; ?>
    </fieldset>
</div>

<br><br>

<footer class="l-footer">&copy; 2025 Íris Essence - Beauty Clinic. Todos os direitos reservados.</footer>

</body>
</html>