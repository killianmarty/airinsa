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
    <?php
        session_start();

        if(!isset($_SESSION["id"])) {
            header("Location: index.php");
            exit();
        }
    ?>
    <header>
      <span>
        <a href="../">
          <img id="headerLogo" src="assets/logo.png" alt="Air INSA">
        </a>
      </span>
      
      <h1>CV Book des membres du club</h1>
      <span class="disconnectBtnCtn">
        <?php
        // Vérifier si l'utilisateur est connecté avant d'afficher le bouton de déconnexion
        if(isset($_SESSION["id"])) {
            echo '<a id="disconnectBtn" href="logout.php">Déconnexion</a>';
        }
        ?>
      </span>
    </header>
    <main>
        <?php

        if($_SESSION["id"] == 2) {
          if(isset($_GET["delete"])) {
            $del = htmlspecialchars($_GET["delete"]);
            $delete = unlink("cv/".$del);
            if($delete) {
              header("Location: dashboard.php");
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
                        touch($image);
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

        function getCreationDate($file) {
            return filemtime($file); // Renvoie la date de création du fichier
        }

        function compareByModificationDate($a, $b) {
            return $a[4] - $b[4]; // Compare les dates de création
        }

        $cv = scandir("cv");
        $pdf = [];
        foreach ($cv as $e) {
            if (substr($e, -4) === ".pdf") {
                $pdf[] = array_merge(explode("_", explode(".", $e)[0]), [$e]);
            }
        }

        usort($pdf, "compareByModificationDate");

        foreach ($pdf as $e) {
          echo '<a href="cv/'.$e[3].'" target="cv"><div>';
          echo "<h2>".$e[0]." ".$e[1];
          echo "</h2>";
          if($_SESSION["id"] == 2) {echo " <a href='?delete=".$e[3]."'>X</a>";}
          echo "<span class='h5'><h5>".$e[2]."</h5></span>";
          echo '<embed src="cv/'.$e[3].'#toolbar=0&navpanes=0&scrollbar=0" width="300" height="450" defer type="application/pdf"/>';
          echo "</div></a>";
        }
        ?>
    </main>
</body>
</html>
