<?php
$title = 'LDAP - Identification';

ob_start();
?>

<header>
    <h1>Identification</h1>
</header>

<a href="index.php"><button type="button" class="btn btn-secondary">Retour</button></a>

<form method=post>
    <div class="form-group">
        <label for="InputLastName">NOM*</label>
        <input type="text" class="form-control" id="InputLastName" name="sn" value="simon">
    </div>
    
    <div class="form-group">
        <label for="InputFirstName">Prénom*</label>
        <input type="text" class="form-control" id="InputFirstName" name="gn" value="simon">
    </div>
    
    <div class="form-group">
        <label for="InputEmail">Mail Professionnel*</label>
        <input type="email" class="form-control" id="InputEmail1" name="email" value="simon@simon.simon">
    </div>
    
    <div class="form-group">
        <label for="InputEmail">Confirmation Mail Professionnel*</label>
        <input type="email" class="form-control" id="InputEmail1" name="email2" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete="off" value="simon@simon.simon">
    </div>
    
    <div class="form-group">
        <label for="InputPhone">Téléphone</label>
        <input type="tel" class="form-control" id="InputLastName" name="phone">
    </div>
    
    <div class="form-group">
        <label for="InputPassword">Mot de passe (8 caractères minimum dont une majuscule et un caractère spécial et un chiffre)*</label>
        <input type="password" class="form-control" id="InputPassword1" name="password" value="Azerty1998+-">
    </div>
    
    <div class="form-group">
        <label for="InputPassword">Confirmation Mot de passe (8 caractères minimum dont une majuscule et un caractère spécial et un chiffre)*</label>
        <input type="password" class="form-control" id="InputPassword1" name="password2" value="Azerty1998+-">
    </div>
    
    <input type="checkbox" name="rgpd">J'ai lu et pris connaissance des <a href="index.php?action=condition" onclick="window.open(this.href); return false;">Conditions générales d'utilisation de ce site</a><br>
    <br>
    <p>*Champs obligatoire</p>
    <input type="submit" class="btn btn-primary" name="envoyer" value="Valider">
</form>

<?php
$content = ob_get_clean();

require('template.php');

?>