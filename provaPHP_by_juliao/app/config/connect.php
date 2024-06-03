<?php
try {
  // Conexão com o banco de dados
  $conexao = new PDO("sqlite:plataforma.db");
  $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $query = "SELECT * FROM usuarios";
  $resultado = $conexao->query($query);
  
  $jsonArray = [];

  foreach ($resultado as $linha) {
    $jsonArray[] = [
      "ID" => $linha["id"],
      "Nome" => $linha["nome"],
      "Email" => $linha["email"],
      "Senha" => $linha["senha"],
      "Nivel_Acesso" => $linha["nivel_acesso_id"] === 1 ? "ADM" : "Cliente",
    ];
  }
  header("Content-Type: application/json");
  echo json_encode($jsonArray, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
  echo "Erro de conexão: " . $e->getMessage();
}
?>

