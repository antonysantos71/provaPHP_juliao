<?php
session_start();

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../../public/pages/login.php");
    exit();
}

// Verifica se o formulário foi submetido
if (isset($_POST["submit"])) {
    // Verifica se todos os campos obrigatórios foram preenchidos
    if (
        !empty($_POST["nome"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["senha"]) &&
        !empty($_POST["nivel_acesso"])
    ) {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $nivel_acesso = $_POST["nivel_acesso"];
        // Verifica se o email é válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<h3 class='msg'>Erro ao adicionar usuário: Email inválido!</h3>";
            exit();
        }
        // Conexão com o banco de dados SQLite
        $caminho_banco_dados = "../config/plataforma.db";
        // Verifica se o arquivo do banco de dados existe
        if (!file_exists($caminho_banco_dados)) {
            die("Erro: O arquivo do banco de dados não foi encontrado.");
        }
        try {
            // Conecta ao banco de dados
            $conexao = new PDO("sqlite:" . $caminho_banco_dados);
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Verifica se o email já está cadastrado
            $stmt_check_email = $conexao->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
            $stmt_check_email->bindParam(":email", $email);
            $stmt_check_email->execute();
            $email_existente = $stmt_check_email->fetchColumn();
            if ($email_existente) {
                echo "<h3 class='msg'>Erro ao adicionar usuário: Email já cadastrado!</h3>";
            } else {
                // Insere o novo usuário no banco de dados
                $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, nivel_acesso_id) VALUES (:nome, :email, :senha, :nivel_acesso_id)");
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":senha", $senha);
                // Defina aqui o valor para nivel_acesso_id de acordo com o nivel_acesso selecionado
                $nivel_acesso_id = ($nivel_acesso === "ADM") ? 1 : 2;
                $stmt->bindParam(":nivel_acesso_id", $nivel_acesso_id);
                $stmt->execute();
                // Redireciona de volta para a página de administração após a adição
                header("Location: ../../public/pages/system.php");
                exit();
            }
        } catch (PDOException $e) {
            // Rollback em caso de erro
            die("Erro ao adicionar usuário: " . $e->getMessage());
        }
    } else {
        echo "<h3 class='msg'>Todos os campos são obrigatórios.</h3>";
    }
} else {
    // Se o formulário não foi submetido, redireciona de volta para a página de administração
    header("Location: ../../public/pages/system.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
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
            background-color: rgba(0,0,0,0.9);
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
    <form action="add.php" method="post">
    <h1>Adicionar Usuário</h1>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome"><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha"><br>
        <label for="nivel_acesso">Nível de Acesso:</label>
        <select id="nivel_acesso" name="nivel_acesso">
            <option value="ADM">ADM</option>
            <option value="cliente">Cliente</option>
        </select><br>
        <button type="submit" name="submit">Adicionar</button>
    </form>
</body>
</html>
