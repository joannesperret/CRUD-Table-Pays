<!DOCTYPE html>
<head>
    <meta charset ="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Holtwood+One+SC&display=swap" rel="stylesheet">
 </head> 

<?php
// Inclusion de la "bibliothèque" du DAO
require_once 'daos/CRUDTablePaysDAOProcedural.php';
// Inclusion de la class de connexion DATABASE
require_once 'Connexion/Connexion.php';
$contenu = "";
//btn ajouter nouvelle valeur
$btnajouter = filter_input(INPUT_POST, "btnajouter");
//btn modifier valeur récupérée du formulaire
$btnmodifier = filter_input(INPUT_POST, "btnmodifier");
//btn Modifier du tableau
$btnmodif = filter_input(INPUT_POST, "btnmodif");
//btn Supprimer du tableau
$btnsupp = filter_input(INPUT_POST, "btnsupp");

//FONCTION ENVOI DES DONNEES A MODIFIER DANS CHAMPS INPUT
if ($btnmodif != null) {
    //Tableau récupérant valeurs du SelectOne
    //$connexion = $connexion = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cours;", "root", "");
    $connexion= Database::connect();
    // --- Attributs de connexion : erreur --> exception
    //$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // --- Communication UTF-8 entre BD et script
    //$connexion->exec("SET NAMES 'UTF8'");
    $tSelectOne = selectOnePays($connexion, $btnmodif);
    //Id Pays    
    $Id_Pays = $tSelectOne['id_pays'];
    //Ville
    $Pays = $tSelectOne['nom_pays'];
}

//FONCTION DELETE SUR btnsupp
if ($btnsupp != null) {
    $connexion = Database::connect();
    // --- Attributs de connexion : erreur --> exception
    //$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // --- Communication UTF-8 entre BD et script
    //$connexion->exec("SET NAMES 'UTF8'");
    // PAYS   
    $affected = delete($connexion, $btnsupp);
    if ($affected === 1) {
        $message = "Le Pays $btnsupp a été supprimé de la base<br>";
    } elseif ($affected === 0) {
        $message = "Le Pays $btnsupp n'a pas été supprimé de la base<br>";
    } else {
        $message = "Le Pays $btnsupp n'a pas été trouvé contactez votre administrateur<br>";
    }
}

//FONCTION MODIFIER SUR CHAMPS INPUT
if ($btnmodifier != null) {
    // Récupération des valeurs des champs INPUT
    $IdPaysSaisi = filter_input(INPUT_POST, "idpays");
    $PaysSaisi = filter_input(INPUT_POST, "nompays");
    $IdPaysCache = filter_input(INPUT_POST, "idpayscache");
    if ($IdPaysSaisi != null && $IdPaysCache != null && $PaysSaisi != null) {
        $connexion= Database::connect();
        //$connexion = $connexion = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cours;", "root", "");
        // --- Attributs de connexion : erreur --> exception
        //$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // --- Communication UTF-8 entre BD et script
        //$connexion->exec("SET NAMES 'UTF8'");
        $tBtnSaisi = array();
        $tBtnSaisi = [$PaysSaisi, $IdPaysSaisi, $IdPaysCache];
        // Mise à jour du pays dans la BD
        $affected = update($connexion, $tBtnSaisi);
        if ($affected === 1) {
            $message = "Le Pays $IdPaysCache a été modifié dans la base pays<br>";
        } elseif ($affected === 0) {
            $message = "Le Pays $IdPaysCache n'a pas été modifié dans la base<br>";
        } else {
            $message = "Le Pays $IdPaysCache n'a pas été trouvé contactez votre administrateur<br>";
        }
    } else {
        $message = "Tous les champs doivent être saisis";
    }
}

//FONCTION AJOUTER UN PAYS
if ($btnajouter != null) {
    $IdPaysSaisi = filter_input(INPUT_POST, "idpays");
    $PaysSaisi = filter_input(INPUT_POST, "nompays");
    if ($IdPaysSaisi != null || $PaysSaisi != null) {
        $connexion= Database::connect();
        //$connexion = $connexion = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cours;", "root", "");
        // --- Attributs de connexion : erreur --> exception
        //$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // --- Communication UTF-8 entre BD et script
        //$connexion->exec("SET NAMES 'UTF8'");
        // Récupération des valeurs des champs INPUT
        $tBtnSaisi = array();
        $tBtnSaisi = [$IdPaysSaisi, $PaysSaisi];
        // Mise à jour du pays dans la BD
        $affected = insert($connexion, $tBtnSaisi);
        if ($affected === 1) {
            $message = "Le Pays $PaysSaisi a été inséré dans la base pays<br>";
        } elseif ($affected === 0) {
            $message = "Le Pays $PaysSaisi n'a pas été inséré dans la base<br>";
        } else {
            $message = "Le Pays $PaysSaisi existe déjà, passez par le formulaire pour le modifier<br>";
        }
    } else {
        $message = "Tous les champs doivent être saisis";
    }
}

try {
    // --- Tentative de connexion
    // pdo(dsn,ut,pwd)
    //$connexion = new PDO("mysql:host=127.0.0.1;port=3306;dbname=cours;", "root", "");
    // --- Attributs de connexion : erreur --> exception
    //$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // --- Communication UTF-8 entre BD et script
    $connexion= Database::connect();
    $connexion->exec("SET NAMES 'UTF8'");
    // SELECT = concaténation SELECT + nom de table saisie
    $curseur = $connexion->query("SELECT * FROM pays WHERE nom_pays <> 'France'ORDER BY nom_pays");
    $curseur->setFetchMode(PDO::FETCH_ASSOC);

    // On boucle sur les lignes du curseur
    foreach ($curseur as $enregistrement) {
        $contenu .= "<tr>";
        // On boucle sur les colonnes de l'enregistrement courant
        foreach ($enregistrement as $valeur) {
            $contenu .= "<td>$valeur</td>";
        }
        $contenu .= "<td><button type='submit' value='$valeur'class='btn btn-primary' name='btnmodif'><span class='glyphicon glyphicon-pencil'></span> Modifier</button></td><td>"
                . "<button type='submit' value='$valeur' class='btn btn-danger' name='btnsupp'><span class='glyphicon glyphicon-remove'></span> Supprimer</button></td>";
        $contenu .= "</tr>";
    }


    $curseur->closeCursor();
} /// try
// --- Récupération d'une exception
catch (PDOException $e) {
    $contenu = "Echec de l'exécution : " . $e->getMessage();
} /// catch
// --- Deconnexion
// Déclaration des valeurs
//$connexion = null;
Database::disconnect();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CRUDTablePays1Page</title>
    </head>

    <body>    
        <div class="border">
            <h2>CRUD table Pays sur une page</h2>
            <form method="POST" action ="">
                <table class='table table-striped table-bordered' border="1">

                    <tbody>
                    <th>Id Pays</th>
                    <th>Nom Pays</th>
                    <th>Modification</th>
                    <th>Suppression</th>
<?php
echo $contenu;
?>
                    </tbody>
                </table> 
            </form>
            <div id="sectionBasse">
                <form method="POST" action="">
                    <label>Id Pays</label>
                    <input type="text" name="idpays" value="<?php
                    if (isSet($tSelectOne)) {
                        echo$tSelectOne['id_pays'];
                    }
?>">
                    <input hidden type="text" name="idpayscache" value="<?php
                           if (isSet($tSelectOne)) {
                               echo$tSelectOne['id_pays'];
                           }
                           ?>">
                    <label>Nom du Pays</label>
                    <input type="text" name="nompays" value="<?php
                    if (isSet($tSelectOne)) {
                        echo$tSelectOne['nom_pays'];
                    }
                           ?>"> 
                    <button type='submit' value='ajouter' class='btn btn-success' name="btnajouter"><span class='glyphicon glyphicon-plus-sign'></span> Ajouter</button>
                    <button type='submit' value='modifier'class='btn btn-primary' name="btnmodifier"><span class='glyphicon glyphicon-pencil'></span> Modifier</button>
                </form>  
            </div>
        </div>
        <div class="alert alert-success" role="alert">
<?php
if (isSet($message)) {
    echo $message . "<br>";
}
?>
        </div>                          
    </body>
</html>