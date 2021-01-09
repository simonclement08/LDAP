<?php
$title = 'LDAP - Validation';

ob_start();

?>

<a href="index.php"><button type="button" class="btn btn-secondary">Retour</button></a><br>

<table class="table table-dark table-striped">
    <tr>
        <td colspan="8" class="text-center"><h3>Liste à valider</h1></td>
    </tr>
    <tr>
        <td>uid</td>
        <td>Nom</td>
        <td>Prénom</td>
        <td>Mail</td>
        <td>Téléphone</td>
        <td>Valider</td>
        <td>Editer</td>
        <td>Supprimer</td>
    </tr>
    <?php
    $student = $manager->get();
    if(is_array($student)){
        foreach($student as $element){
            echo "<tr>";
                echo "<form method=post>";

                    echo "<td>";
                        echo $element->getUid();
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $element->getSn() . "' name='sn'";
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $element->getGn() . "' name='givenname'";
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $element->getEmail() . "' name='mail'";
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $element->getPhone() . "' name='mobile'";
                    echo "</td>";
                    
                    echo "<td>";
                        $valid = "valid" . $element->getUid();
                        echo "<button name='$valid' class='image_button' type='submit'>";
                            echo "<img class='image' src='public/images/valide.png'>";
                        echo "</button>";
                    echo "</td>";
                    
                    echo "<td>";
                        $edit = "edit" . $element->getUid();
                        echo "<button name='$edit' class='image_button' type='submit'>";
                            echo "<img class='image' src='public/images/edit.png'>";
                        echo "</button>";
                    echo "</td>";
                    
                    echo "<td>";
                        $supp = "supp" . $element->getUid();
                        echo "<button name='$supp' class='image_button' type='submit'>";
                            echo "<img class='image' src='public/images/supp.png'>";
                        echo "</button>";
                    echo "</td>";
                echo "</form>";
                
            echo "</tr>";
            
            $valid = 'valid' . $element->getUid();
            $edit = 'edit' . $element->getUid();
            $supp = 'supp' . $element->getUid();
            
            if(isset($_POST["$valid"])){
                ldap_add_student($element,$ldapServer,$bindServerLDAP);
                $manager->delete($element);
                header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
            }

            if(isset($_POST["$edit"])){
                $element->setSn($_POST["sn"]);
                $element->setGn($_POST["givenname"]);
                $element->setCn($_POST["givenname"] . " " . $_POST["sn"]);
                $element->setEmail($_POST["mail"]);
                $element->setPhone($_POST["mobile"]);
                $manager->update($element);
                header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
            }
            
            if(isset($_POST["$supp"])){
                $manager->delete($element);
                header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
            }
        }
    }
    ?>
</table>

<table class="table table-dark table-striped">
    <tr>
        <td colspan="9" class="text-center"><h3>Liste LDAP</h3></td>
    </tr>
    <tr>
        <td>uid</td>
        <td>Nom</td>
        <td>Prénom</td>
        <td>Cn</td>
        <td>Groupes</td>
        <td>Mail</td>
        <td>Téléphone</td>
        <td>Editer</td>
        <td>Supprimer</td>
    </tr>
    <?php
    
    
    if ($ldapServer){
        
        $query = "uid=*";
        $result=ldap_search($ldapServer, $dn, $query);
        
        $info = ldap_get_entries($ldapServer, $result);
        
        for ($i=0; $i < $info["count"]; $i++) {
            
            echo "<tr>";
                echo "<form method='post'>";
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $info[$i]["uid"][0] . "' name='uid'";
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $info[$i]["sn"][0] . "' name='sn'";
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $info[$i]["givenname"][0] . "' name='givenname'";
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $info[$i]["cn"][0] . "' name='cn'";
                    echo "</td>";
                    
                    $ou = getou_ldap($info[$i]['uid'][0],$ldapServer,$dn);
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='$ou' name='ou'";
                    echo "</td>";
                    
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $info[$i]["mail"][0] . "' name='mail'";
                    echo "</td>";
                    if(isset($info[$i]["mobile"][0])){
                        $mobile = $info[$i]["mobile"][0];
                    }
                    else{
                        $mobile = "";
                    }
                    echo "<td>";
                        echo "<input class='ldapinput' value='" . $mobile . "' name='mobile'";
                    echo "</td>";
                    
                    echo "<td>";
                        $edit = "edit" . $info[$i]["uid"][0];
                        echo "<button name='$edit' class='image_button' type='submit'>";
                            echo "<img class='image' src='public/images/edit.png'>";
                        echo "</button>";
                    echo "</td>";
                echo "</form>";
                echo "<form method=post>";
                    echo "<td>";
                        $supp = "supp" . $info[$i]["uid"][0];
                        echo "<button name='$supp' class='image_button' type='submit'>";
                            echo "<img class='image' src='public/images/supp.png'>";
                        echo "</button>";
                    echo "</td>";
                echo "</form>";
            echo "</tr>";
            
            if(isset($_POST["$edit"])){
                
                $entry["sn"][0] = $_POST["sn"];
                $entry["givenname"][0] = $_POST["givenname"];
                $entry["cn"][0] = $_POST["cn"];
                $entry["mail"][0] = $_POST["mail"];
                if($ou == ""){
                    $bindServerLDAP=ldap_mod_replace($ldapServer, "uid=" . $info[$i]["uid"][0] . ",dc=ldap,dc=egnom,dc=pro", $entry);
                    ldap_rename($ldapServer, "uid=" . $info[$i]["uid"][0] . ",dc=ldap,dc=egnom,dc=pro", "uid=" . $_POST["uid"], "dc=ldap,dc=egnom,dc=pro", true);
                    if($info[$i]["mobile"][0]){
                        $tel["mobile"][0] = $_POST["mobile"];
                        $bindServerLDAP=ldap_mod_replace($ldapServer, "uid=" . $info[$i]["uid"][0] . ",dc=ldap,dc=egnom,dc=pro", $tel);
                    }
                    else{
                        $tel["mobile"][0] = $_POST["mobile"];
                        $bindServerLDAP=ldap_mod_add($ldapServer, "uid=" . $info[$i]["uid"][0] . ",dc=ldap,dc=egnom,dc=pro", $tel);
                    }
                }
                else{
                    $pos = strpos($ou, "/");
                    $ou1 = substr("$ou", 0, $pos-1);
                    $ou2 = substr("$ou", $pos+1);
                    $newuid = $_POST["uid"];
                    $member["member"] = "uid=$newuid,ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro";
                    ldap_mod_add($ldapServer, "cn=$ou2,ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro", $member);
                    $filter = "(uid=" . $info[$i]["uid"][0] . ")";
                    $result=ldap_search($ldapServer, $dn, $filter);
                    $first = ldap_first_entry($ldapServer, $result);
                    $data = ldap_get_dn($ldapServer, $first);
                    
                    ldap_delete_member_cn($ldapServer,$bindServerLDAP,$ou1,$ou2,$data);
                    
                    
                    $bindServerLDAP=ldap_mod_replace($ldapServer, "uid=" . $info[$i]["uid"][0] . ",ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro", $entry);
                    $pos = strpos($_POST["ou"], "/");
                    $ou3 = substr($_POST['ou'], 0, $pos-1);
                    $ou4 = substr($_POST['ou'], $pos+1);
                    ldap_rename($ldapServer, "uid=" . $info[$i]["uid"][0] . ",ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro", "uid=" . $_POST["uid"], "ou=$ou4,ou=$ou3,dc=ldap,dc=egnom,dc=pro", true);
                    if($ou3 != $ou1 or $ou4 != $ou2){
                        $filter = "(uid=" . $info[$i]["uid"][0] . ")";
                        $result=ldap_search($ldapServer, $dn, $filter);
                        $first = ldap_first_entry($ldapServer, $result);
                        $data = ldap_get_dn($ldapServer, $first);
                        
                        ldap_delete_member_cn($ldapServer,$bindServerLDAP,$ou1,$ou2,$data);
                        
                        $newmember["member"]= "uid=" . $info[$i]["uid"][0] . ",ou=" . $ou4 . ",ou=" . $ou3 . ",dc=ldap,dc=egnom,dc=pro";
                        $bindServerLDAP=ldap_mod_add($ldapServer, "cn=$ou4,ou=$ou4,ou=$ou3,dc=ldap,dc=egnom,dc=pro", $newmember);
                        $admin["member"] = "uid=admin,ou=" . $ou4 . ",ou=" . $ou3 . ",dc=ldap,dc=egnom,dc=pro";
                        ldap_mod_del($ldapServer, "cn=" . $ou4 . ",ou=" . $ou4 . ",ou=" . $ou3 . ",dc=ldap,dc=egnom,dc=pro", $admin);
                    }
                    
                    if($info[$i]["mobile"][0]){
                        $tel["mobile"][0] = $_POST["mobile"];
                        $bindServerLDAP=ldap_mod_replace($ldapServer, "uid=" . $info[$i]["uid"][0] . ",ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro", $tel);
                    }
                    else{
                        $tel["mobile"][0] = $_POST["mobile"];
                        $bindServerLDAP=ldap_mod_add($ldapServer, "uid=" . $info[$i]["uid"][0] . ",ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro", $tel);
                    }
                }
                
                header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
            }
            
            if(isset($_POST["$supp"])){
                if($ou == ""){
                    $bindServerLDAP = ldap_delete($ldapServer, "uid=" . $info[$i]["uid"][0] . ",dc=ldap,dc=egnom,dc=pro");
                }else{
                    $pos = strpos($ou, "/");
                    $ou1 = substr("$ou", 0, $pos-1);
                    $ou2 = substr("$ou", $pos+1);
                    
                    $filter = "(uid=" . $info[$i]["uid"][0] . ")";
                    $result=ldap_search($ldapServer, $dn, $filter);
                    $first = ldap_first_entry($ldapServer, $result);
                    $data = ldap_get_dn($ldapServer, $first);
                    
                    ldap_delete_member_cn($ldapServer,$bindServerLDAP,$ou1,$ou2,$data);
                    $bindServerLDAP = ldap_delete($ldapServer, "uid=" . $info[$i]["uid"][0] . ",ou=$ou2,ou=$ou1,dc=ldap,dc=egnom,dc=pro");
                    
                    /*
                    for ($i=0; $i < $info[0]["member"]["count"]; $i++) {
                        echo $info[0]["member"][$i] . "<br>";
                        if($info[0]["member"][$i] == $data){
                            
                        }
                    }*/
                    
                    
                }
                header("Refresh:0; url=index.php?action=admin&id=" . $_GET['id'] . "&password=" . $_GET['password']);
            }
            
        }
    }
    ?>
</table>

<form method=post>
    <label>Crée un nouveau groupe : </label>
    <select name="creategroup">
    <?php
        list_ou_ldap($ldapServer,$dn,null);
    ?>
    </select>
    <input name="groupname">
    <input type='submit' name="addgroup" value='Ajouter un groupe'>
</form>

<form method="post">
    <label>Ajouter les utilisateurs qui n'ont pas de groupe dans un groupe :</label>
    <select name="selecttype">
        <?php
            list_ou_ldap($ldapServer,$dn,$_POST['selecttype']);
        ?> 
    </select>
    <input type="submit" name="editvalid" value="Valider">
    <?php
    if(isset($_POST['editvalid'])){
        $dn2 = "ou=" . $_POST['selecttype'] . "," . $dn;
        echo '<select name="selectgroup">';
                list_ou_ldap($ldapServer,$dn2,null);
        echo '</select>';
        echo '<input type="submit" name="editgroup" value="Editer">';
    }
    ?>
</form>

<form method=post>
    <label>Supprimer un groupe : </label>
    <select name="groupetype">
        <?php
            list_ou_ldap($ldapServer,$dn,$_POST['groupetype']);
        ?> 
    </select>
    <input type="submit" name="delvalid" value="Valider">
    <?php
    if(isset($_POST['delvalid'])){
        $dn3 = "ou=" . $_POST['groupetype'] . "," . $dn;
        echo '<select name="groupselect">';
                list_ou_ldap($ldapServer,$dn3,null);
        echo '</select>';
        echo '<input type="submit" name="delgroup" value="Supprimer un groupe">';
    }
    ?>
</form>

<?php

$content = ob_get_clean();

require('template.php');

?>