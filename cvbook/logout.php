<?php
// Initialisation de la session
session_start();

// Destruction de toutes les données de la session
session_destroy();

// Redirection vers la page d'accueil (index.php)
header("Location: index.php");
exit();
?>
