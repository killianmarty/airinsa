<?php
session_start();

$log = [
    "admin" => '$2y$10$F5WkzHN2ZjwaL2ugMBLOaeJa5kEd7LUJ4fnt5noCFtUIehR5p9rTW',
    "navblue" => '$2y$10$2W.kvFFBaGaCqxOOIafNherExrjhP.GacTj1mbl3eoLLFDX3FQvoC'
];

if(isset($_SESSION["id"])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if(isset($_POST["submit"])) {
    if(isset($_POST["login"]) && isset($_POST["passwd"]) && !empty($_POST["login"]) && !empty($_POST["passwd"])) {
        $login = htmlspecialchars($_POST["login"]);
        $passwd = htmlspecialchars($_POST["passwd"]);
        
        if(array_key_exists($login, $log)) {
            if(password_verify($passwd, $log[$login])) {
                if($login == "admin") {
                    $_SESSION["id"] = 2;
                } else {
                    $_SESSION["id"] = 1;
                }
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Login ou mot de passe invalide";
            }
        } else {
            $error = "Login ou mot de passe invalide";
        }
    } else {
        $error = "Veuillez rentrer vos identifiants";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CV Book Air INSA</title>
  <link rel="icon" href="favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@200&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <main>
        <form method="POST" id="form">
          <div id="top" align="center">
            <span>CONNEXION</span>
          </div>
          <div id="bottom" align="center">
            <input type="text" name="login" placeholder="Identifiant">
            <input type="password" name="passwd" placeholder="Mot de passe">
            <input type="submit" name="submit" class="submit" value="Se connecter">
            <?php if(isset($error)) {echo "<br>".$error; } ?>
          </div>
        </form>
    </main>
</body>
</html>
