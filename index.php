<?php 
// 1. CONNEXION À LA BASE
// On appelle le fichier db.php qui contient le PDO
require 'db.php';

// 4. SUPPRIMER UNE TÂCHE
// On regarde si "suppr" est dans l'adresse URL (ex: index.php?suppr=5)
if (isset($_GET['suppr'])) {
    $id_a_supprimer = $_GET['suppr'];
    
    // REQUÊTE PRÉPARÉE pour la sécurité
    $delete = $pdo->prepare("DELETE FROM taches WHERE id = ?");
    $delete->execute([$id_a_supprimer]);
    
    // On recharge la page pour que la tâche disparaisse de la liste
    header("Location: index.php");
    exit();
}

// 2. AJOUTER UNE TÂCHE
if (isset($_POST['ajouter'])) {
    $t = $_POST['titre'];
    $d = $_POST['description'];
    $p = $_POST['priorite'];
    $s = $_POST['statut'];
    $dt = $_POST['date_limite'];

    if ($t != "") {
        // REQUÊTE PRÉPARÉE (Obligatoire pour la prof)
        // Les "?" évitent que quelqu'un pirate la BDD via le formulaire
        $sql = "INSERT INTO taches (titre, description, priorite, statut, date_limite) VALUES (?, ?, ?, ?, ?)";
        $req = $pdo->prepare($sql);
        $req->execute([$t, $d, $p, $s, $dt]);
        
        header("Location: index.php");
        exit();
    }
}

// 3. AFFICHER LES TÂCHES (Le SELECT)
$req_affichage = $pdo->query("SELECT * FROM taches ORDER BY priorite DESC");
$taches = $req_affichage->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mon App de Taches</title>
    <style>
        /* CSS IDENTIQUE - ON NE TOUCHE À RIEN */
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
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody id="le_tableau">
            <?php foreach ($taches as $ligne): ?>
            <tr class="tache-ligne" data-statut="<?= $ligne['statut'] ?>">
                <td class="<?= ($ligne['priorite'] == 'haute') ? 'urgent' : '' ?>">
                    <strong><?= htmlspecialchars($ligne['titre']) ?></strong> <br>
                    <small><?= htmlspecialchars($ligne['description']) ?></small>
                </td>
                <td><?= $ligne['priorite'] ?></td>
                <td><?= $ligne['date_limite'] ?></td>
                <td><?= $ligne['statut'] ?></td>
                <td>
                    <a href="index.php?suppr=<?= $ligne['id'] ?>" onclick="return confirm('Sûr ?')">X</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // JS pour le titre vide et le filtre (identique au précédent)
        const f = document.getElementById('form_ajout');
        f.onsubmit = function(event) {
            const t = document.getElementById('id_titre').value;
            if (t == "") {
                alert("Mec, met un titre stp");
                event.preventDefault();
            }
        };

        const select = document.getElementById('mon_filtre');
        select.onchange = function() {
            const val = this.value;
            const trs = document.querySelectorAll('.tache-ligne');
            for (let i = 0; i < trs.length; i++) {
                const s = trs[i].getAttribute('data-statut');
                trs[i].style.display = (val == "toutes" || s == val) ? "" : "none";
            }
        };
    </script>
</body>
</html>