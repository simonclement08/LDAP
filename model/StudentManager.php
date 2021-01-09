<?php
class StudentManager{
    
    private $_db;
  
    public function __construct($db){
        $this->setDb($db);
    }
    
    public function setDb(PDO $db){
        $this->_db = $db;
    }
    
    public function add(Student $objet){
        $uid = $objet->getUid();
        $cn = $objet->getCn();
        $gn = $objet->getGn();
        $sn = $objet->getSn();
        $email = $objet->getEmail();
        $phone = $objet->getPhone();
        $password = $objet->getPassword();
        $this->_db->exec("INSERT INTO student(uid,cn,gn,sn,email,phone,password) VALUES ('$uid', '$cn', '$gn', '$sn', '$email', '$phone', '$password')");
        
        header("Location: index.php");
    }
    
    public function delete(Student $objet){
        // Exécute une requête de type DELETE.
        $this->_db->exec('DELETE FROM student WHERE uid = "'.$objet->getUid().'"');
    }
    
    public function get(){
        $objets = [];
        $q = $this->_db->query("SELECT * FROM student");
        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
            $objets[] = new Student($donnees);
        }
        
        return $objets;
    }
    
    public function update(Student $objet){
        $uid = $objet->getUid();
        $cn = $objet->getCn();
        $sn = $objet->getSn();
        $gn = $objet->getGn();
        $email = $objet->getEmail();
        $phone = $objet->getPhone();
        $password = $objet->getPassword();
        // Prépare une requête de type UPDATE.
        $this->_db->exec("UPDATE student SET cn = '$cn' , sn = '$sn', gn = '$gn', email = '$email', phone = '$phone', password = '$password' WHERE uid = '$uid'");
    }
}
