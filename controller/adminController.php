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


$server = "localhost";
$ldapServer=ldap_connect($server);

ldap_set_option($ldapServer, LDAP_OPT_PROTOCOL_VERSION, 3);

$rootdn = "cn=admin,dc=ldap,dc=egnom,dc=pro";
$rootpw = "btssio";
$bindServerLDAP = ldap_bind($ldapServer,$rootdn,$rootpw);
$dn = "dc=ldap,dc=egnom,dc=pro";


if(isset($_POST['addgroup'])){
  $type = $_POST['creategroup'];
  $groups = $_POST['groupname'];
  ldap_add_groupe($type,$groups,$ldapServer,$bindServerLDAP);
  header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
}

if(isset($_POST['editgroup'])){
  ldap_edit_groupe($ldapServer,$bindServerLDAP,$dn);
  header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
}

if(isset($_POST['delgroup'])){
  ldap_delete_ou($ldapServer,$bindServerLDAP);
  header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
}

require 'view/adminView.php';


ldap_close($ldapServer);