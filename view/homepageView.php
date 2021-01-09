<?php
$title = 'LDAP';

ob_start();
?>

<header>
    <h1 class="w-100 text-center color">=~= Connexion =~=</h1>
    <br><br>
</header>
<div class="container">
    <form class="w-100" method=get action=index.php>
        <div class="row">
            <div class="col-12 m-2 p-2 text-center border-div">
                <h2 class="color">Connexion Administrateur</h2>
                <br>
                <div class="form-group">
                    <label class="color">Identifiant</label>
                    <input type="txt" class="form-control" value=admin name="id" placeholder="Entrez votre identifiant">
                </div>
                
                <div class="form-group">
                    <label class="color">Mot de passe</label>
                    <input type="password" class="form-control" value=admin name="password" placeholder="Veuillez saisir votre mot de passe.">
                </div>
                <br>
                <a href="index.php?action=admin"><button type="submit" name="send" class="btn btn-primary">Valider</button></a>
                <br><br>
            </div>
                
            <div class="col-12 m-2 p-2 text-center border-div">
                <h2 class="color">Enregistrement</h2>
                <p class="color">Clique ici pour t'enregistrer sur BYOD</p>
                <br>
                
                <a href="index.php?action=register"><button type="button" class="btn btn-primary">S'identitfier</button></a>
                <br><br>
            </div>
        </div>
    </form>
</div>

<div id="background" class="fixed-top"></div>

<?php
$content = ob_get_clean();

require('template.php');

?>