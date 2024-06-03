<?php 
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["nivel_acesso"])) {
  unset($_SESSION["email"]);
  unset($_SESSION["nivel_acesso"]);
  header("Location: ./login.php");
  exit();
}

$emailUsuario = $_SESSION["email"];

$conexao = new PDO("sqlite:../../app/config/plataforma.db");
$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Consulta para obter o nome do usuário logado
$stmt = $conexao->prepare("SELECT nome FROM usuarios WHERE email = :email");
$stmt->bindParam(':email', $emailUsuario);
$stmt->execute();
$dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
$nomeUsuario = $dadosUsuario['nome']; // Nome do usuário logado

$sql = "SELECT nome, email, nivel_acesso_id FROM usuarios ORDER BY nome"; // Seleciona todos os usuários

$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes</title>
  <link rel="stylesheet" href="../assets/css/cliente.css" />
</head>
<body>
  <header>
    <div class="container">
      <h1>Bem Vindo(a) <?php echo $nomeUsuario; ?> !</h1>
      <a href="../../app/controllers/sair.php">Sair</a>
    </div>
  </header>
  <br/>

  <div class="cards-container">
    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
      <div class="card">
        <img src="<?php echo ($row['nivel_acesso_id'] == 1) ? '../assets/img/imgProfileAdm.png' : '../assets/img/imgProfile.png'; ?>" alt="<?php echo $row['nome']; ?>">
        <h2><?php echo $row['nome']; ?></h2>
        <p>Email: <?php echo $row['email']; ?></p>
        <p>Nível de Acesso: <?php echo ($row['nivel_acesso_id'] == 1) ? 'Admistrador' : 'Cliente'; ?></p>
      </div>
    <?php } ?>
  </div>
</body>
</html>
