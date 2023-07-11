<?php
session_start();
require "../Facebook/function/function.php";
require "../Facebook/database/database.php";
if (isset($_SESSION['users'])) {
  $users = getById($_SESSION['users']->id, $pdo, "users");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://unpkg.com/@webcreate/infinite-ajax-scroll/dist/infinite-ajax-scroll.min.js"></script>
  <link  rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link  rel="stylesheet" href="../style.css">
  <title>Mon Réseau Social</title>
</head>

<body>
  <!-- Barre de navigation -->
  <nav style="background-color: #FFFFFF;" class="navbar navbar-expand-lg navbar-dark  fixed-top ">
    <a style="font-size: 30px;color:#1877F2" class="navbar-brand font-weight-bolder " href="index.php">Facebook</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link text-dark " href="#"><i  class="fas fa-user-friends"></i> <span class="badge badge-pill badge-primary">3</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#"><i  class="fas fa-comments"></i> </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  text-dark" href="#"><i class="fas fa-bell"></i><span class="badge badge-pill badge-primary">5</span></a>
        </li>
      </ul>
      <form class="form-inline mr-4">
        <div class="input-group rounded">
          <input style="width: 300px;" type="search" class="form-control rounded" placeholder="Rechercher" aria-label="Rechercher" aria-describedby="search-addon" />
          <span class="input-group-text border-0" id="search-addon">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </form>
      <div class="dropdown">
        <a href="#" role="button" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/images/<?php echo $users->profil   ?>" class="rounded-circle" alt="Image de profil" style="width: 50px;">
        </a>
        <div style="min-width: 300px; width: auto !important; max-width: 800px;" class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <div class="dropdown-header d-flex">
           <a class="d-blocl" href="profil.php?user_id=<?=$users->id?>">
           <img src="/images/<?php echo $users->profil ?>" alt="Photo de profil" style="width: 50px;" class="rounded-circle">
           </a>
            <div style="margin-left: 5px; display: flex; flex-direction: column;">
              <span style="font-size: large;" class="username text-dark font-weight-bold"><?php echo $users->username ?></span>
              <a class="text-justify text-dark text-decoration-none font-weight-normal"  href="profil.php?user_id=<?=$users->id?>">Voir ton profil</a>
            </div>
          </div>
          <a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Paramètres</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
      </div>

    </div>
  </nav>