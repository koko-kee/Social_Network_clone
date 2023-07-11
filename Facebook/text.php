<?php
  session_start();
  if (isset($_POST['name']) && isset($_POST['prenom']) && isset($_POST['tel']) && isset($_POST['email']) && isset($_POST['password'])) {

    $name = htmlspecialchars($_POST['name']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $tel = htmlspecialchars($_POST['tel']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'nom_de_votre_base_de_donnees');
    if ($conn->connect_error) {
      die("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // Préparer la requête SQL pour l'insertion des données
    $stmt = $conn->prepare("INSERT INTO user (name, prenom, tel, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $prenom, $tel, $email, $password);

    if ($stmt->execute()) {
      // Inscription réussie
      header("Location: inscription.php?reg_err=success");
      exit();
    } else {
      // Erreur lors de l'inscription
      header("Location: inscription.php?reg_err=error");
      exit();
    }

    $stmt->close();
    $conn->close();
  }
  ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscription</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
  <style>
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .left-section {
      flex: 1;
      padding: 20px;
      text-align: center;
      background-color: #f0f2f5;
    }

    .right-section {
      flex: 1;
      padding: 20px;
      background-color: white;
    }

    .logo {
      width: 80px;
      height: 80px;
      margin-bottom: 20px;
    }

    .connect-text {
      font-size: 1.5rem;
      margin-bottom: 20px;
    }

    .form-container {
      max-width: 400px;
      margin: 0 auto;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .form-group input[type="submit"] {
      background-color: #4267B2;
      color: white;
      cursor: pointer;
    }

    .policy-text {
      margin-top: 20px;
      font-size: 0.9rem;
      text-align: center;
    }

    .policy-text a {
      color: #4267B2;
      font-weight: bold;
      text-decoration: none;
    }

    .navbar {
      background-color: #4267B2;
      /* Couleur bleue de Facebook */
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      color: white;
    }

    .navbar-brand img {
      width: 40px;
      height: 40px;
      margin-right: 10px;
    }

    .navbar-brand h1 {
      font-size: 1.5rem;
      font-weight: bold;
      margin: 0;
    }

    .user_actions {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-left: auto;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="left-section">
      <img class="logo" src="logo.png" alt="Logo Facebook">
      <p class="connect-text">Connectez-vous avec des amis et le monde qui vous entoure sur Facebook</p>
    </div>
    <div class="right-section">
      <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="name">Prénom</label>
            <input type="text" id="prenom" name="prenom" required>
          </div>
          <div class="form-group">
            <label for="name">Téléphone</label>
            <input type="number" id="tel" name="tel" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
          </div>
          <div class="form-group">
            <input type="submit" value="S'inscrire">
          </div>
        </form>
        <p class="policy-text">En cliquant sur S'inscrire, vous acceptez nos <a href="#">Conditions</a>, <a href="#">Politique de confidentialité des données</a> et <a href="#">Politique relative aux cookies</a>.</p>
        <p class="login-link">J'ai déjà un compte, <a href="connexion.php">me connecter</a>.</p>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>