<?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../../public/pages/login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ./edit.php");
    exit();
}

// Caminho para o arquivo do banco de dados SQLite3
$caminho_banco_dados = "../../app/config/plataforma.db";

// Verificar se o arquivo do banco de dados existe
if (!file_exists($caminho_banco_dados)) {
    die("Erro: O arquivo do banco de dados não foi encontrado.");
}

$usuario = []; // Inicializa a variável $usuario

try {
    // Conexão com o banco de dados SQLite3
    $conexao = new PDO("sqlite:" . $caminho_banco_dados);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os detalhes do usuário
    $query = "SELECT * FROM usuarios WHERE id = :id";
    $statement = $conexao->prepare($query);
    $statement->bindParam(':id', $_GET['id']);
    $statement->execute();
    $usuario = $statement->fetch(PDO::FETCH_ASSOC);

    // Verificar se o usuário foi encontrado
    if (!$usuario) {
        die("Erro: Usuário não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            margin: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            opacity: .8;
        }
        .msg {
        	position: absolute;
        	top: 0;
        	left: 570px;
        }
    </style>
</head>
<body>
    
    <form action="saveEdit.php" method="POST">
    	<h1>Editar Usuário</h1>
        <input type="hidden" name="id" value="<?php echo isset($usuario['id']) ? $usuario['id'] : ''; ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo isset($usuario['nome']) ? $usuario['nome'] : ''; ?>"><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($usuario['email']) ? $usuario['email'] : ''; ?>"><br>
        <label for="senha">Senha:</label>
        <input type="text" id="senha" name="senha" value="<?php echo isset($usuario['senha']) ? $usuario['senha'] : ''; ?>"><br>
        <label for="nivel_acesso">Nível de Acesso:</label>
        <select id="nivel_acesso" name="nivel_acesso">
            <option value="1" <?php echo ($usuario['nivel_acesso_id'] == 1) ? 'selected' : ''; ?>>Administrador</option>
            <option value="2" <?php echo ($usuario['nivel_acesso_id'] == 2) ? 'selected' : ''; ?>> Cliente </option>
        </select><br>

        <button type="submit" name="submit">Atualizar</button>
    </form>
</body>
</html>
