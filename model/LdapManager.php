<?php
//CRUD LDAP


//Create ou
function ldap_add_groupe($type,$groups,$ldapServer,$bindServerLDAP){
    $ou["objectClass"][0] = "top";
    $ou["objectClass"][1] = "organizationalUnit";
    $ou["ou"] = $groups;
    
    $bindServerLDAP = ldap_add($ldapServer, "ou=" . $groups . ",ou=" . $type . ",dc=ldap,dc=egnom,dc=pro", $ou);
    
    $cn["objectClass"][0] = "top";
    $cn["objectClass"][1] = "groupOfNames";
    $cn["cn"] = $groups;
    $cn["member"] = "uid=admin,ou=$groups,ou=$type,dc=ldap,dc=egnom,dc=pro";
    $bindServerLDAP = ldap_add($ldapServer, "cn=" . $groups . ",ou=" . $groups . ",ou=" . $type . ",dc=ldap,dc=egnom,dc=pro", $cn);
}

//Create student
function ldap_add_student($element,$ldapServer,$bindServerLDAP){
    $entry["objectClass"][0] = "top";
    $entry["objectClass"][1] = "person";
    $entry["objectClass"][2] = "organizationalPerson";
    $entry["objectClass"][3] = "inetOrgPerson";
    $entry["cn"] = $element->getGn() . " " . $element->getSn();
    $entry["sn"] = $element->getSn();
    $entry["givenName"] = $element->getGn();
    $entry["mail"] = $element->getEmail();
    if($element->getPhone()!=""){
        $entry["mobile"] = $element->getPhone();
    }
    $entry["uid"] = $element->getUid();
    $entry["userPassword"] = $element->getPassword();
    
    $bindServerLDAP=ldap_add($ldapServer, "uid=" . $element->getUid() . ",dc=ldap,dc=egnom,dc=pro", $entry);
}

//Read ou (liste déroulante adminView)
function list_ou_ldap($ldapServer,$dn,$selected){
    if ($ldapServer){
        $query = "ou=*";
        //$result=ldap_search($ldapServer, $dn, $query); //tous les ou
        
        //$result=ldap_list($ldapServer, $dn, $query); pour voir les ou qui sont "visible" directement
        
        $result = ldap_list($ldapServer, $dn, $query);
        
        $info = ldap_get_entries($ldapServer, $result);
        for ($i=0; $i < $info["count"]; $i++) {
            if(isset($selected)){
                if($selected === $info[$i]['ou'][0]){
                    echo "<option selected value='" . $info[$i]['ou'][0] . "'>" . $info[$i]['ou'][0] . "</option>";
                }
                else{
                    echo "<option value='" . $info[$i]['ou'][0] . "'>" . $info[$i]['ou'][0] . "</option>";
                }
            }
            else{
                echo "<option value='" . $info[$i]['ou'][0] . "'>" . $info[$i]['ou'][0] . "</option>";
            }
        }
       
    }
}

function getou_ldap($uid,$ldapServer,$dn){
    $filter = "(uid=$uid)";
    $result=ldap_search($ldapServer, $dn, $filter);
    $first = ldap_first_entry($ldapServer, $result);
    $data = ldap_get_dn($ldapServer, $first);
    $posou = strpos($data, "ou=");
    $ou = "";
    
    while ($posou){
        $posvir = strpos($data, ",",$posou);
        if($ou == ""){
            $ou = $ou . substr("$data", $posou + 3, $posvir-$posou-3);
        }
        else{
            $ou = substr("$data", $posou + 3, $posvir-$posou-3) . " / " . $ou;
        }
        $posou = strpos($data, "ou=",$posvir);
    }

    return $ou;
}

//Read student (voir tableau ldap dans adminView)

//Update ou
function ldap_edit_groupe($ldapServer,$bindServerLDAP,$dn){
    $query = "uid=*";
    $result=ldap_search($ldapServer, $dn, $query);
    
    $info = ldap_get_entries($ldapServer, $result);
    for ($i=0; $i < $info["count"]; $i++) {
        $uid = $info[$i]['uid'][0];
        $ou = getou_ldap($uid,$ldapServer,$dn);
        $groups = $_POST['selectgroup'];
        $type = $_POST['selecttype'];
        if($ou == ""){
            ldap_rename($ldapServer, "uid=" . $uid . ",dc=ldap,dc=egnom,dc=pro", "uid=" . $uid, "ou=" . $groups . ",ou=" . $type . ",dc=ldap,dc=egnom,dc=pro", true);
            
            $member["member"]= "uid=" . $uid . ",ou=" . $groups . ",ou=" . $type . ",dc=ldap,dc=egnom,dc=pro";
            ldap_mod_add($ldapServer, "cn=" . $groups . ",ou=" . $groups . ",ou=" . $type . ",dc=ldap,dc=egnom,dc=pro", $member);
            
            $admin["member"] = "uid=admin,ou=" . $groups . ",ou=" . $type . ",dc=ldap,dc=egnom,dc=pro";
            ldap_mod_del($ldapServer, "cn=" . $groups . ",ou=" . $groups . ",ou=" . $type . ",dc=ldap,dc=egnom,dc=pro", $admin);
        }
    }
}

//Update student (voir tableau ldap dans adminView)

//Delete ou

function ldap_delete_ou($ldapServer,$bindServerLDAP){
    $ou1= $_POST['groupetype'];
    $ou2 = $_POST['groupselect'];
    
    $query = "uid=*";
    $dngroupe = "ou=" . $ou2 . ",ou=" . $ou1 . ",dc=ldap,dc=egnom,dc=pro";
    $result = ldap_search($ldapServer, $dngroupe, $query);
    
    $info = ldap_get_entries($ldapServer, $result);
    for ($i=0; $i < $info["count"]; $i++) {
        $uid = $info[$i]['uid'][0];
        $bindServerLDAP = ldap_delete($ldapServer, "uid=" . $uid .",ou=" . $ou2 . ",ou=" . $ou1 . ",dc=ldap,dc=egnom,dc=pro");
    }
    
    $bindServerLDAP = ldap_delete($ldapServer, "cn=" . $ou2 .",ou=" . $ou2 . ",ou=" . $ou1 . ",dc=ldap,dc=egnom,dc=pro");
    $bindServerLDAP = ldap_delete($ldapServer, "ou=" . $ou2 . ",ou=" . $ou1 . ",dc=ldap,dc=egnom,dc=pro");
}

//Delete ou (voir tableau ldap dans adminView)


function ldap_delete_member_cn($ldapServer,$bindServerLDAP,$ou1,$ou2,$data){
    
    $dnl= "cn=$ou2,ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro";
    $query = "member=*";
    $result=ldap_search($ldapServer, $dnl, $query);
    
    $search = ldap_get_entries($ldapServer, $result);
    
    if($search[0]["member"]["count"] == 1 ){
        $admin["member"] = "uid=admin,ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro";
        ldap_mod_add($ldapServer, "cn=$ou2,ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro", $admin);
    }
    $member["member"] = $data;
    $bindServerLDAP = ldap_mod_del($ldapServer, "cn=$ou2,ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro", $member);
}