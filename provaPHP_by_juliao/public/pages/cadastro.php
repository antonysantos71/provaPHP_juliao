<?php
if (isset($_POST["submit"])) {
  $nome = $_POST["nome"];
  $email = $_POST["email"];
  $senha = $_POST["senha"];

  if ($email === "" || $nome === "" || $senha === "") {
    echo "<p class='warning'>Erro! Preencha todos os campos.</p>";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<p class='warning'>Erro ao adicionar usuário: Email inválido!</p> <br>";
  } else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $database = "../../app/config/plataforma.db";
      try {
        $conn = new PDO("sqlite:$database");
        // Define o modo de erro do PDO para lançar exceções
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar se o e-mail já está cadastrado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailCount = $stmt->fetchColumn();

        if ($emailCount > 0) {
          echo "<h3 class='warning'>Erro ao cadastrar: usuário já cadastrado!</h3>";
        } else {
          // Insere os dados do formulário na tabela "usuarios" com nivel_acesso_id fixo para 'cliente'
          $stmt = $conn->prepare(
            "INSERT INTO usuarios (nome, email, senha, nivel_acesso_id) VALUES (:nome, :email, :senha, :nivel_acesso_id)"
          );
          $nivel_acesso_id = 2; // ID do nível de acesso para 'cliente'
          $stmt->bindParam(":nome", $nome);
          $stmt->bindParam(":email", $email);
          $stmt->bindParam(":senha", $senha);
          $stmt->bindParam(":nivel_acesso_id", $nivel_acesso_id);
          $stmt->execute();
          header("Location: ./login.php");
          exit; // Certifique-se de usar exit após o redirecionamento
        }
      } catch (PDOException $e) {
        echo "<h3 class='warning'>Erro ao cadastrar: " . $e->getMessage() . "</h3>";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/cadastro.css"/>
  <title>Cadastro</title>
</head>
<body>

<div class="signup-container">
  <a href="./index.php">Voltar</a>
  <h2>Cadastro</h2>
  <form action="cadastro.php" method="post">
    <input type="text" placeholder="Nome" name="nome">
    <input type="email" placeholder="Email" name="email">
    <input type="password" placeholder="Senha" name="senha">
    <input type="submit" name="submit" id="submit" value="Cadastrar" />
    <p>Já tem conta? <a href="./login.php">Login</a></p>
  </form>
</div>
</body>
</html>
