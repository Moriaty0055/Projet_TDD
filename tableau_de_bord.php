<?php
session_start(); // Démarre la session

// Vérification de la session : si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirection vers la page de connexion
    exit(); // On arrête l'exécution du script ici
}

require_once('BaseDeDonnees.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        body {
            padding: 60px 60px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tableau de bord</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Pseudo</th>
                    <th>Numéro</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $bdd = new BaseDeDonnes();
                    $pdo = $bdd->connexion();
                    $requete = $pdo->query("SELECT nom, prenom, pseudo, numero FROM utilisateurs");

                    while ($row = $requete->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pseudo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['numero']) . "</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "</br><p style='color: red; text-align: center;'>Erreur : " . $e->getMessage() . "</p>";
                }
                ?>
            </tbody>
        </table>
        <!-- Ajout d'un bouton de déconnexion -->
        <form action="logout.php" method="post">
            <button type="submit">Déconnexion</button>
        </form>
    </div>
</body>

</html>
