
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Register - MindGrow</title>

    <link rel="stylesheet" href="/mindgrow/assets/css/style.css">
</head>

<body class="auth-page">

    <div class="form-container">

        <h2>créer un compte</h2>

        <form method="POST" action="/mindgrow/controller/AuthController.php">

            <input type="text" name="name" placeholder="Nom" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Mot de passe" required>

            <button type="submit" name="register">
                S'inscrire
            </button>


        </form>
        

        <p>
            Déjà un compte ?
            <a href="/mindgrow/view/login.php">
                Se connecter
            </a>
        </p>
        <div class="back-home">
    <a href="../public/index.php?page=landing">← Retour à l’accueil</a>
</div>

    </div>

</body>

</html>

