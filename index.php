<?php 
// faut pas oublier d'inclure la bdd sinon rien marche
require 'db.php';

// PARTIE AJOUT
if (isset($_POST['ajouter'])) {
    $le_titre = $_POST['titre'];
    $la_description = $_POST['description'];
    $la_priorite = $_POST['priorite'];
    $le_statut = $_POST['statut'];
    $la_date = $_POST['date_limite'];

    // si le titre est pas vide on envoie
    if ($le_titre != "") {
        $sql = "INSERT INTO taches (titre, description, priorite, statut, date_limite) VALUES (?, ?, ?, ?, ?)";
        $requete = $pdo->prepare($sql);
        $requete->execute([$le_titre, $la_description, $la_priorite, $le_statut, $la_date]);
        
        // pour eviter que ca recharge en boucle
        header("Location: index.php");
    }
}

// PARTIE AFFICHAGE
// j'ai trié par priorité mais c'est un peu galere
$req = $pdo->query("SELECT * FROM taches ORDER BY priorite DESC");
$toutes_les_taches = $req->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mon Application de Taches</title>
    <style>
        /* CSS un peu basique */
        body { font-family: Arial; margin: 20px; background-color: #f0f0f0; }
        .mon-formulaire { background: white; padding: 15px; border: 1px solid #ccc; margin-bottom: 20px; }
        .tache-ligne { background: white; border-bottom: 1px solid #999; }
        .urgent { color: red; font-weight: bold; }
        table { width: 100%; border: 1px solid black; background: white; }
        th { background: #eee; }
        td { padding: 8px; }
        .filtre-box { margin-bottom: 10px; }
    </style>
</head>
<body>

    <h1>Ma liste de trucs à faire</h1>

    <div class="mon-formulaire">
        <form method="POST" id="form_ajout">
            <input type="text" name="titre" id="id_titre" placeholder="Nom de la tache">
            
            <select name="statut">
                <option value="a_faire">A faire</option>
                <option value="en_cours">En cours</option>
                <option value="termine">Terminé</option>
            </select>

            <select name="priorite">
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute !!</option>
            </select>

            <input type="datetime-local" name="date_limite">
            <br><br>
            <textarea name="description" placeholder="Mettre une description ici..."></textarea>
            <br>
            <button type="submit" name="ajouter">Valider l'ajout</button>
        </form>
    </div>

    <div class="filtre-box">
        <span>Filtrer par :</span>
        <select id="mon_filtre">
            <option value="toutes">Tout</option>
            <option value="a_faire">A faire</option>
            <option value="en_cours">En cours</option>
            <option value="termine">Fini</option>
        </select>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>Quoi ?</th>
                <th>C'est urgent ?</th>
                <th>Quand ?</th>
                <th>C'est fait ?</th>
            </tr>
        </thead>
        <tbody id="le_tableau">
            <?php foreach ($toutes_les_taches as $ligne): ?>
            <tr class="tache-ligne" data-statut="<?= $ligne['statut'] ?>">
                <td class="<?= ($ligne['priorite'] == 'haute') ? 'urgent' : '' ?>">
                    <?= $ligne['titre'] ?> <br>
                    <small><?= $ligne['description'] ?></small>
                </td>
                <td><?= $ligne['priorite'] ?></td>
                <td><?= $ligne['date_limite'] ?></td>
                <td><?= $ligne['statut'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // verif si le titre est vide
        const f = document.getElementById('form_ajout');
        f.onsubmit = function(event) {
            const t = document.getElementById('id_titre').value;
            if (t == "") {
                alert("Mec, met un titre stp");
                event.preventDefault();
            }
        };

        // pour le filtre
        const select = document.getElementById('mon_filtre');
        select.onchange = function() {
            const val = this.value;
            const trs = document.querySelectorAll('.tache-ligne');
            
            for (let i = 0; i < trs.length; i++) {
                const s = trs[i].getAttribute('data-statut');
                if (val == "toutes" || s == val) {
                    trs[i].style.display = "";
                } else {
                    trs[i].style.display = "none";
                }
            }
        };
    </script>

</body>
</html>