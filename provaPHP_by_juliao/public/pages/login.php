<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="stylesheet" href="../assets/css/login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="login-container">
	<a href="./index.php">volta</a>
  <h2>Login</h2>
  <form action="../../app/controllers/testeLogin.php" method="post">
    <input type="text" placeholder="email" name="email">
    <input type="password" placeholder="Password" name="senha">
    <input type="submit" name="submit" id="submit" value="Login" />
    <p>Não tem conta? <a href="./cadastro.php">sing-up</a></p>
  </form>
</div>
</body>
</html>
