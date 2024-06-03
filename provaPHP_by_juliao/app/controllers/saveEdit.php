<?php
session_start();

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../../public/pages/login.php");
  exit();
}

// Verificar se o formulário foi submetido
if (isset($_POST["submit"])) {
  // Verificar se todos os campos foram preenchidos
  if (
    isset($_POST["id"], $_POST["nome"], $_POST["email"], $_POST["nivel_acesso"])
  ) {
    // Caminho para o arquivo do banco de dados
    $caminho_banco_dados = "../../app/config/plataforma.db";

    // Verificar se o arquivo do banco de dados existe
    if (!file_exists($caminho_banco_dados)) {
      die("Erro: O arquivo do banco de dados não foi encontrado.");
    }

    try {
      // Conexão com o banco de dados
      $conexao = new PDO("sqlite:" . $caminho_banco_dados);
      $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Atualizar o usuário no banco de dados
      $query =
        "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, nivel_acesso_id = :nivel_acesso WHERE id = :id";
      $statement = $conexao->prepare($query);
      $statement->bindParam(":nome", $_POST["nome"]);
      $statement->bindParam(":email", $_POST["email"]);
      $statement->bindParam(":senha", $_POST["senha"]); // Seu campo de senha
      $statement->bindParam(":nivel_acesso", $_POST["nivel_acesso"]); // Seu campo de nível de acesso
      $statement->bindParam(":id", $_POST["id"]);
      $statement->execute();

      // Redirecionar de volta para a página de administração após a atualização
      header("Location: ../../public/pages/system.php");
      exit();
    } catch (PDOException $e) {
      die("Erro: " . $e->getMessage());
    }
  } else {
    echo "Todos os campos são obrigatórios.";
  }
} else {
  // Se o formulário não foi submetido, redirecionar de volta para a página de administração
  header("Location: ../../public/pages/system.php");
  exit();
}
?>
