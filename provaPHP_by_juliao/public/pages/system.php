<?php 
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["nivel_acesso"])) {
  unset($_SESSION["email"]);
  unset($_SESSION["nivel_acesso"]);
  header("Location: ./login.php");
  exit();
}

if ($_SESSION["nivel_acesso"] !== 'ADM') {
  header("Location: ./login.php");
  exit();
}

$userLogado = $_SESSION["email"];

$conexao = new PDO("sqlite:../../app/config/plataforma.db");
$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Consulta para obter o nome do usuário logado
$stmt = $conexao->prepare("SELECT nome FROM usuarios WHERE email = :email");
$stmt->bindParam(':email', $userLogado);
$stmt->execute();
$dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
$nomeUsuario = $dadosUsuario['nome']; // Nome do usuário logado

if (!empty($_GET["search"])) {
  $data = $_GET["search"];
  $sql = "SELECT u.id, u.nome, u.email, u.senha, na.nome AS nivel_acesso 
          FROM usuarios u
          INNER JOIN niveis_acesso na ON u.nivel_acesso_id = na.id
          WHERE u.id LIKE '%$data%' OR u.nome LIKE '%$data%' OR u.email LIKE '%$data%' 
          ORDER BY u.id DESC";
} else {
  $sql = "SELECT u.id, u.nome, u.email, u.senha, na.nome AS nivel_acesso 
          FROM usuarios u
          INNER JOIN niveis_acesso na ON u.nivel_acesso_id = na.id
          ORDER BY u.id DESC";
}

$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema</title>
  <link rel="stylesheet" href="../assets/css/system.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <div>
    <header>
      <div class="container">
        <h1>System data users</h1>
         <!-- Exibe o nome do usuário logado -->
        <a href="../../app/controllers/sair.php">Sair</a>
      </div>
    </header>
    <br/>
    <h1>Bem vindo, <?php echo $nomeUsuario; ?>!!</h1>

    <div class="container-inputs">
      <!-- Campo de pesquisa -->
      <input type="search" placeholder="Pesquisar..." id="search">
      <!-- Botão de pesquisa -->
      <button type="submit" onclick="searchData()" id="searchButton">
        <i class='fas fa-search'></i>
      </button>
      <!-- Formulário de adição -->
      <form action="../../app/controllers/add.php" method="post">
        <button type="submit" name="submit" id="addButton">
          <i class="fas fa-plus"></i>
        </button>
      </form>
    </div>
    <div class="table-container">
      <table>
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Senha</th>
                  <th>Nível de Acesso</th>
                  <th>Opções</th>
              </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . $row["id"] . "</td>";
              echo "<td>" . $row["nome"] . "</td>";
              echo "<td>" . $row["email"] . "</td>";
              echo "<td>" . $row["senha"] . "</td>";
              echo "<td>" . $row["nivel_acesso"] . "</td>"; // Mostra o nome do nível de acesso
              echo "<td>";
              echo "<a href='../../app/controllers/edit.php?id=" . $row['id'] . "' class='edit'>
                      <i class='fas fa-edit'></i>
                    </a>";
              echo "<a href='../../app/controllers/delete.php?id=" . $row['id'] . "' class='delete'>
                      <i class='fas fa-trash-alt'></i>
                    </a>";
              echo "</td>";
              echo "</tr>";
            } ?>
          </tbody>
      </table>
    </div>
  </div>

  <script src="../assets/js/search.js"></script>
</body>
</html>
