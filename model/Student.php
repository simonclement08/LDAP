<?php
class Student{
    private $_uid;
    private $_cn;
    private $_sn;
    private $_gn;
    private $_email;
    private $_phone;
    private $_password;
    
    public function __construct(array $donnees){
        $this->hydrate($donnees);
    }
    public function hydrate(array $donnees){
        foreach ($donnees as $key => $value){
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set'.ucfirst($key);
            
            // Si le setter correspondant existe.
            if (method_exists($this, $method)){
              // On appelle le setter.
              $this->$method($value);
            }
        }
    }
    
    public function getUid(){
        return  $this->_uid;
    }
    
    public function getCn(){
        return  $this->_cn;
    }
    
    public function getSn(){
        return  $this->_sn;
    }
    
    public function getGn(){
        return  $this->_gn;
    }
    
    public function getEmail(){
        return  $this->_email;
    }
    
    public function getPhone(){
        return  $this->_phone;
    }
    
    public function getPassword(){
        return  $this->_password;
    }
    
    
    public function setUid($uid){
        if ($uid != ""){
            $this->_uid = $uid;
        }
    }
    
    public function setSn($sn){
        if ($sn != ""){
            $this->_sn = $sn;
        }
        else{
             echo 'Veuillez saisir un Nom <br>';
        }
    }
  
    public function setGn($gn){
        if ($gn != ""){
              $this->_gn = $gn;
        }
        else{
               echo 'Veuillez saisir un Prénom <br>';
        }
    }

    public function setCn($cn){
        if ($cn != ""){
            $this->_cn = $cn;
        }
        else{
             echo 'Il y a eu une erreur lors de l\'ajout du Cn<br>';
        }
    }
  
    public function setEmail($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->_email = $email;
        }
        else{
             echo 'L\'adresse mail n\'est pas valide <br>';
        }
    }
    
    public function setPhone($phone){
        if (preg_match('`[0-9]{10}`',$phone) or $phone == ""){
            $this->_phone = $phone;
        }
        else{
             echo 'Le numéro de téléphone n\'est pas conforme <br>';
        }
    }
    
    public function setPassword($password){
        if ($password != ""){
            $this->_password = $password;
        }
    }
}
