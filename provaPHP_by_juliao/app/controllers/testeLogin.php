<?php 
session_start();

if (
  isset($_POST["submit"]) &&
  !empty($_POST["email"]) &&
  !empty($_POST["senha"])
) {
  // Conexão com o banco de dados
  $conexao = new PDO("sqlite:../config/plataforma.db");
  $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Dados do formulário
  $email = $_POST["email"];
  $senha = $_POST["senha"];

  if ($email === '' || $senha === '') {
    echo "<p class='warning'>Erro! Preencha todos os campos.</p>";
  } else {
    // Consulta para verificar se há algum registro com o email e senha fornecidos
    $query = "SELECT u.id, u.nome, u.email, na.nome AS nivel_acesso 
              FROM usuarios u
              INNER JOIN niveis_acesso na ON u.nivel_acesso_id = na.id
              WHERE u.email = :email AND u.senha = :senha";
    
    $statement = $conexao->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':senha', $senha);
    $statement->execute();
    $usuario = $statement->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
      // Definir a sessão com o ID do usuário e nível de acesso
      $_SESSION["usuario_id"] = $usuario['id'];
      $_SESSION["email"] = $usuario['email'];
      $_SESSION["nivel_acesso"] = $usuario['nivel_acesso'];

      // Redirecionamento baseado no nível de acesso do usuário
      if ($usuario['nivel_acesso'] === 'ADM') {
          header("Location: ../../public/pages/system.php");
      } else if ($usuario['nivel_acesso'] === 'cliente') {
          header("Location: ../../public/pages/cliente.php");
      } else {
          // Nível de acesso não reconhecido, redirecionar para a página de login
          header("Location: ../../public/pages/login.php");
      }
      exit();
    } else {
      // Dados de login inválidos, redirecionar para a página de login
      header("Location: ../../public/pages/login.php");
      exit();
    }
  }
} else {
  // Se o formulário não foi enviado corretamente, redirecionar para a página de login
  header("Location: ../../public/pages/login.php");
  exit();
}
?>     
