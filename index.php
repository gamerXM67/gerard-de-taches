<?php 
require_once 'config/connexion.php';

// SUPPRIMER
if (isset($_GET['suppr'])) {
    $pdo->prepare("DELETE FROM taches WHERE id = ?")->execute([$_GET['suppr']]);
    header("Location: index.php");
    exit();
}

// AJOUTER
if (isset($_POST['ajouter'])) {
    $t = $_POST['titre']; $d = $_POST['description'];
    $p = $_POST['priorite']; $s = $_POST['statut'];
    $dt = $_POST['date_limite'];

    if ($t != "") {
        $sql = "INSERT INTO taches (titre, description, priorite, statut, date_limite) VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$t, $d, $p, $s, $dt]);
        header("Location: index.php");
        exit();
    }
}

// RECUPERER
$taches = $pdo->query("SELECT * FROM taches ORDER BY priorite DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Ma Todo List</title>
    <link rel="stylesheet" href="css/style.css">
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

    <table>
        <thead>
            <tr>
                <th>Quoi ?</th>
                <th>C'est urgent ?</th>
                <th>Quand ?</th>
                <th>C'est fait ?</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($taches as $l): ?>
            <tr class="tache-ligne" data-statut="<?= $l['statut'] ?>">
                <td class="<?= ($l['priorite'] == 'haute') ? 'urgent' : '' ?>">
                    <strong><?= htmlspecialchars($l['titre']) ?></strong><br>
                    <small><?= htmlspecialchars($l['description']) ?></small>
                </td>
                <td><?= $l['priorite'] ?></td>
                <td><?= $l['date_limite'] ?></td>
                <td><?= $l['statut'] ?></td>
                <td><a href="index.php?suppr=<?= $l['id'] ?>" onclick="return confirm('Sûr ?')">X</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="js/script.js"></script>
</body>
</html>