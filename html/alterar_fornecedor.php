<?php
    // Conexão com o banco de dados
    // Inicia a sessão do usuário
    session_start();
    require 'conexao.php';
    
    // Verifica se o usuário tem permissão de ADM ou Gerente
    if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2){
        echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
        exit();
    }
    
    $fornecedor = null;
    
    // Processa alteração de dados se o formulário for enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset(// Dados enviados via formulário
    $_POST['id_fornecedor']) && isset(// Dados enviados via formulário
    $_POST['acao']) && // Dados enviados via formulário
    $_POST['acao'] === 'alterar') {
        $id_fornecedor = // Dados enviados via formulário
        $_POST['id_fornecedor'];
        $nome = trim(// Dados enviados via formulário
        $_POST['nome']);
        $endereco = trim(// Dados enviados via formulário
        $_POST['endereco']);
        $telefone = trim(// Dados enviados via formulário
        $_POST['telefone']);
        $produto = trim(// Dados enviados via formulário
        $_POST['produto']);
        
        
        $sql = "UPDATE fornecedor SET nome = :nome, endereco = :endereco, telefone = :telefone, produto = :produto WHERE id_fornecedor = :id_fornecedor";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':produto', $produto);
        $stmt->bindParam(':id_fornecedor', $id_fornecedor, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "<script>alert('Fornecedor alterado com sucesso!'); window.location.href='alterar_fornecedor.php';</script>";
            exit();
        } else {
            echo "<script>alert('Erro ao alterar fornecedor!'); window.location.href='alterar_fornecedor.php';</script>";
            exit();
        }
    }
    
    
    // Processa busca de usuário
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset(// Dados enviados via formulário
    $_POST['busca_fornecedor']) && (!isset(// Dados enviados via formulário
    $_POST['acao']) || // Dados enviados via formulário
    $_POST['acao'] !== 'alterar')) {
        $busca = trim(// Dados enviados via formulário
        $_POST['busca_fornecedor']);
        
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM fornecedor WHERE nome LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$fornecedor) {
            echo "<script>alert('Fornecedor não encontrado!');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Íris Essence - Alterar Usuário</title>
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
<form action="alterar_fornecedor.php" method="POST">
<legend>Alterar Fornecedor</legend>
<label for="busca_fornecedor">Digite o ID ou Nome do fornecedor:</label>
<input type="text" id="busca_fornecedor" name="busca_fornecedor" required>
<div id="sugestoes"></div>
<button class="botao_cadastro" type="submit">Buscar</button>
</form>

<?php if ($fornecedor): ?>
<!-- Formulário para alterar usuário -->
<form action="alterar_fornecedor.php" method="POST">
<input type="hidden" name="id_fornecedor" value="<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>">
<input type="hidden" name="acao" value="alterar">

<label for="nome">Nome:</label>
<input type="text" id="nome" name="nome" value="<?= htmlspecialchars($fornecedor['nome']) ?>" required>

<label for="endereco">Endereço:</label>
<input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($fornecedor['endereco']) ?>" required>

<label for="telefone">Telefone:</label>
<input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($fornecedor['telefone']) ?>" required>

<label for="produto">Produto:</label>
<input type="text" id="produto" name="produto" value="<?= htmlspecialchars($fornecedor['produto']) ?>" required>

<div class="botoes">
<button class="botao_cadastro" type="submit">Alterar</button>
<button class="botao_limpeza" type="reset">Cancelar</button>
</div>

<a href="principal.php">Voltar</a>
</form>
<?php endif; ?>

<br>
<button type="button" class="voltar-button" onclick="window.location.href='principal.php'">Voltar</button>
  </fieldset>
</div>

<br><br>

<footer class="l-footer">&copy; 2025 Íris Essence - Beauty Clinic. Todos os direitos reservados.</footer>

</body>
</html>
