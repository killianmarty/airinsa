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
  <style>
    * {
      font-family: 'Raleway', sans-serif;
    }

    body {
      margin: 0;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
      background-image: url("./assets/background_plane.jpg");
      background-size: cover;
      -webkit-backdrop-filter: blur(7px); /* assure la compatibilité avec safari */
      backdrop-filter: blur(7px);
    }

    main {
      text-align: center;
      margin: 0;
      height: 100vh;
      overflow-y: scroll;
      /*background-color: rgb(60, 60, 60);*/
    }

    div {
      display: inline-block;
      background-color: #2a2a2e;
      margin: 30px;
      padding: 5px;
      width: 320px;
      border-radius: 10px;
      color: rgb(240, 240, 240);
    }

    span.h5 {
      display: block;
      height: 30px;
    }

    embed {
      height: 450px;
      width: 300px;
    }

    #form {
      position: absolute;
      display: flex;
      height: 280px;
      width: 300px;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      background-color: transparent;
      margin: 0;
      padding: 0;
    }

    #top {
      position: absolute;
      margin-top: 0;
      margin-left: 0;
      width: 300px;
      height: 40px;
      background-color: #327674;
      color: 53, 79, 112;
      border-radius: 10px 10px 0 0;
    }

    #bottom {
      position: absolute;
      margin-left: 0;
      bottom: 0;
      height: 200px;
      width: 300px;
      background-color: white;
      border-radius: 0 0 10px 10px;
      color: black;
    }

    #top span {
      color: white;
      font-size: 30px;
    }

    #title {
      font-size: 30px;
      color: white;
      margin-top: 40px;
    }

    input {
      width: 200px;
      height: 30px;
      margin: 10px;
      background-color: transparent;
      border: none;
      border-bottom: 2px solid #327674;
      outline: none;
      font-family: sans-serif !important;
    }

    .submit {
      width: 200px;
      height: 30px;
      margin: 10px;
      background-color: transparent;
      border: 2px solid #327674;
      border-radius: 5px;
      margin-top: 20px;
      color: #327674;
      cursor: pointer;
    }

    .submit:hover {
      background-color: #327674;
      color: white;
      transition: 0.3s ease;
    }

    a {
      text-decoration: none;
      color: white;
    }
    a:hover {
      color: grey;
    }

    h2{
      margin: 10px;
    }

    header{
      display: flex;
      flex-direction: row;
      font-family: 'Raleway', sans-serif;
      color: white;
      justify-content: space-between;
      padding: 10px;
      background-color: rgb(39, 39, 39);
      border-bottom: 1px solid black;
    }

    header img{
      height: 40px;
    }

    header h1{
      height: 40px;
      margin: 0px;
      align-self: center;
    }

    header span{
      flex: 1;
    }
  </style>
</head>
  <body>
    <header>
      <span>
        <a href="../">
          <img id="headerLogo" src="assets/logo.png" alt="Air INSA">
        </a>
      </span>
      
      <h1>CV Book des membres du club</h1>
      <span></span>
    </header>
    <main>
    <?php
      session_start();
      if(isset($_SESSION["id"])) {

        if($_SESSION["id"] == 2) {
          if(isset($_GET["delete"])) {
            $del = htmlspecialchars($_GET["delete"]);
            $delete = unlink("cv/".$del);
            if($delete) {
              header("Location: index.php");
            } else {
              $error = "Erreur de suppression pour ce document";
            }
          }

          if(isset($_POST["submit"])) {
            if(isset($_POST["firstname"]) AND !empty($_POST["firstname"])
              AND isset($_POST["lastname"]) AND !empty($_POST["lastname"])
              AND isset($_POST["fonction"]) AND !empty($_POST["fonction"])) {
              $firstname = htmlspecialchars($_POST["firstname"]);
              $lastname = htmlspecialchars($_POST["lastname"]);
              $fonction = htmlspecialchars($_POST["fonction"]);
              $error = "";
              if(isset($_FILES['file']) AND !empty($_FILES['file']['name'])) {
                if(mime_content_type($_FILES['file']['tmp_name']) == "application/pdf") {
                  $ext = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
                  if(in_array($ext, ["pdf"]))
                  {
                      $image = "./cv/".$firstname."_".$lastname."_".$fonction.".pdf";
                      $resultat = move_uploaded_file($_FILES['file']['tmp_name'], $image);
                      if($resultat)
                      {
                        header("Refresh: 0");
                      } else {
                        $error = "Erreur lors du chargement de votre pdf !";
                      }
                  } else {
                    $error = "Vous ne pouvez upload que des pdf !";
                  }
                } else {
                  $error = "Pas d'injection mon grand !";
                }
              } else {
                $error = "Vous devez upload un fichier !";
              }
            }
          }
          ?>
          <form method="POST" enctype="multipart/form-data" style="background-color: white">
            <input type="text" placeholder="Prénom" name="firstname">
            <input type="text" placeholder="Nom" name="lastname">
            <input type="text" placeholder="Fonction" name="fonction">
            <label for="file">CV</label>
            <input type="file" id="file" name="file">
            <input type="submit" name="submit" value="uploader">
            <?php if(!empty($error)) { echo $error; } ?>
          </form>
          <?php
        }
        $cv = scandir("cv");
        $pdf = [];
        foreach ($cv as $e) {
            if (substr($e, -4) === ".pdf") {
                $pdf[] = array_merge(explode("_", explode(".", $e)[0]), [$e]);
            }
        }

        foreach ($pdf as $e) {
          echo '<a href="cv/'.$e[3].'" target="cv"><div>';
          echo "<h2>".$e[0]." ".$e[1];
          if($_SESSION["id"] == 2) {echo " <a href='?delete=".$e[3]."'>X</a>";}
          echo "</h2>";
          echo "<span class='h5'><h5>".$e[2]."</h5></span>";
          echo '<embed src="cv/'.$e[3].'#toolbar=0&navpanes=0&scrollbar=0" width="300" height="450" defer type="application/pdf"/>';
          echo "</div></a>";
        }
      } else {

        $log = [
          "admin" => '$2y$10$F5WkzHN2ZjwaL2ugMBLOaeJa5kEd7LUJ4fnt5noCFtUIehR5p9rTW',
          "navblue" => '$2y$10$2W.kvFFBaGaCqxOOIafNherExrjhP.GacTj1mbl3eoLLFDX3FQvoC' //nvbl35A7
        ];

        //var_dump(password_hash("", PASSWORD_DEFAULT));
        if(isset($_POST["submit"])) {
          if(isset($_POST["login"]) AND isset($_POST["passwd"]) AND !empty($_POST["login"]) AND !empty($_POST["passwd"])) {
            $login = htmlspecialchars($_POST["login"]);
            $passwd = htmlspecialchars($_POST["passwd"]);
            if(array_key_exists($login, $log)) {
              if(password_verify($passwd, $log[$login])) {
                if($login == "admin") {
                  $_SESSION["id"] = 2;
                } else {
                  $_SESSION["id"] = 1;
                }
                header("Refresh: 0");
              } else {
                $error = "login ou mot de passe invalide";
              }
            } else {
              $error = "login ou mot de passe invalide";
            }
          } else {
            $error = "Veuillez rentrer vos identifiants";
          }
        }
        ?>
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
        <?php
      }
    ?>
    </main>
  </body>
</html>