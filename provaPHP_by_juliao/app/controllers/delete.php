<?php
if (!empty($_GET["id"])) {
  $id = $_GET["id"];

  $conexao = new PDO("sqlite:../config/plataforma.db");
  $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = "SELECT * FROM usuarios WHERE id = :id";
  $statement = $conexao->prepare($query);
  $statement->bindParam(":id", $id, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    $query = "DELETE FROM usuarios WHERE id = :id";
    $statement = $conexao->prepare($query);
    $statement->bindParam(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    header("Location: ../../public/pages/system.php");
  } else {
    header("Location: ../../public/pages/system.php");
    exit();
  }
}
?>
