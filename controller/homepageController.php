<?php
require 'controller/function.php';
require 'model/LdapManager.php';
// On enregistre notre autoload.
function chargerClasse($classname){
  require 'model/' . $classname.'.php';
}

spl_autoload_register('chargerClasse');

$db = connexion_bdd();

$manager = new StudentManager($db);

require 'view/homepageView.php';

if(isset($_GET['send'])){
    $db = connexion_bdd();
    $requete = $db->query("SELECT * FROM admin");
    while ($donnees = $requete->fetch()){
        if($_GET['id'] == $donnees['id'] and $_GET['password'] == $donnees['password']){
            header("Location: index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
        }
    }
}

