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

session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.

if (isset($_SESSION['student'])){ // Si la session perso existe, on restaure l'objet.
  $student = $_SESSION['student'];
}

require('view/registerView.php');

if(isset($_POST['envoyer'])){
    
    
    $sn = strtoupper($_POST['sn']);
    $gn = ucfirst(strtolower($_POST['gn']));
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    
    $email2 = $_POST['email2'];
    if($email != $email2){
        echo "Les adresses mails ne sont pas identiques<br>";
    }
    
    $regex = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#";
    
    if(!preg_match($regex,$password)){
        echo 'Le mot de passe n\'est pas conforme <br>';
    }
    
    $password2 = $_POST['password2'];
    if($password != $password2){
        echo "Les mots de passes ne sont pas identiques<br>";
    }
    
    if (!isset($_POST['rgpd'])){
        echo "Vous n'avez pas pris connaissance des Conditions générales d'utilisation du site<br>";
    }
    
    $data = [
        'uid' => str_replace(' ', '.', date('y') . strtolower($sn)),
        'sn' => $sn,
        'gn' => $gn,
        'cn' => $gn . " ". $sn,
        'email' => $email,
        'phone' => $phone
    ];
    $student = new Student($data);
    
    if($_POST['sn']!="" and $_POST['gn']!="" and $_POST['email']!="" and $_POST['password']!="" and isset($_POST['rgpd'])){
        if(filter_var($email, FILTER_VALIDATE_EMAIL) and $email == $email2 and (preg_match('`[0-9]{10}`',$phone) or $phone == "") and preg_match($regex,$password) and $password == $password2){
            $salt = getRandomHex(8);
            $password = '{SSHA}' . base64_encode(sha1($password . hex2bin($salt), true) . hex2bin($salt));
            $student->setPassword($password);
            $manager->add($student);
            header("Location: index.php");
        }
    }
}

if (isset($student)){ // Si on a créé un objet, on le stocke dans une variable session afin d'économiser une requête SQL. : depuis un form
    $_SESSION['student'] = $student;
}
