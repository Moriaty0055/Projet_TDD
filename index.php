<?php
require_once('inscription.php');
require_once('connexion.php');
session_start(); // Démarre la session

// Générer un token CSRF si nécessaire
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Crée un token sécurisé
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['typeFormu']) && $_POST['typeFormu'] === 'inscription') {
            if (isset($_POST['nom'], $_POST['prenom'], $_POST['numero'], $_POST['pseudo'], $_POST['mdpFirst'], $_POST['mdp'])) {
                $nom = htmlspecialchars($_POST['nom']);
                $prenom = htmlspecialchars($_POST['prenom']);
                $pseudo = htmlspecialchars($_POST['pseudo']);
                $numero = htmlspecialchars($_POST['numero']);
                $mdpFirst = $_POST['mdpFirst'];
                $mdp = $_POST['mdp'];

                if ($mdpFirst === $mdp) {
                    $inscrire = new Inscription();
                    $inscrire->inscriptionUtilisateur($nom, $prenom, $pseudo, $numero, $mdp);
                } else {
                    echo 'Les mots de passe ne correspondent pas.';
                }
            }
        } elseif (isset($_POST['typeFormu']) && $_POST['typeFormu'] === 'connexion') {
            if (isset($_POST['pseudo'], $_POST['mdp'])) {
                $pseudo = $_POST['pseudo'];
                $mdp = $_POST['mdp'];

                $connecter = new Session();
                $connecter->sessionUtilisateur($pseudo, $mdp);
            }
        } else {
            echo "Une erreur s'est produite.";
        }
    } else {
        die("Erreur CSRF, demande invalide.");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Page d'inscription</title>
    <style>
        h1 {
            text-align: center;
            font-weight: 800;
        }
    </style>
</head>

<body>
    <main class="container">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'login';
        ?>
        <?php if ($page === 'register') { ?>
            <fieldset>
                <form action="" method="post">
                    <h1>Page d'inscription</h1>
                    <input type="hidden" name="typeFormu" value="inscription">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="item">
                        <label for="nom">Entrez votre nom</label>
                        <input type="text" name="nom" required>
                    </div>

                    <div class="item">
                        <label for="prenom">Entrez votre prénom</label>
                        <input type="text" name="prenom" required>
                    </div>
                    <div>
                        <label for="pseudo">Définissez votre pseudo</label>
                        <input type="text" name="pseudo">
                    </div>
                    <div class="item">
                        <label for="numero">Quel est votre numéro de téléphone ?</label>
                        <input type="tel" name="numero" placeholder="Tel" aria-label="Tel" autocomplete="tel" required>
                    </div>

                    <div class="item">
                        <label for="mdp">Définissez un mot de passe </label>
                        <input type="password" name="mdpFirst" required>
                    </div>
                    <div class="item">
                        <label for="">Confirmez votre mot de passe</label>
                        <input type="password" name="mdp" required>
                    </div>
                    <input type="submit" value="S'inscrire" role="button" class="contrast">
                </form>
            </fieldset>

            <a href="?page=login">Déjà inscrit ? Connectez-vous ici</a>
        <?php } elseif ($page === 'login') { ?>
            <form action="" method="post">
                <fieldset>
                    <input type="hidden" name="typeFormu" value="connexion">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div>
                        <label for="pseudo">Entrez votre pseudo</label>
                        <input type="text" name="pseudo" required>
                    </div>

                    <div>
                        <label for="mot_de_passe">Entrez votre mot de passe</label>
                        <input type="password" name="mdp" required>
                    </div>
                </fieldset>

                <input type="submit" value="Se connecter" role="button" class="contrast">
            </form>
            <div scope="row">
                <a href="?page=register">Pas encore inscrit ?</a>
            <?php } else { ?>
                <!-- Page par défaut ou erreur -->
                <h2>Erreur</h2>
                <p>La page demandée est introuvable.</p>
                <a href="?page=login">Retour à la connexion</a>
            <?php } ?>
        </div>
    </main>
</body>

</html>

<?php
// Nettoyer le token CSRF après la soumission
unset($_SESSION['csrf_token']);
?>
