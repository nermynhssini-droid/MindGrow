
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Login - MindGrow</title>

    <link rel="stylesheet" href="/mindgrow/assets/css/style.css?v=<?= time() ?>">
</head>

<body class="auth-page">

    <div class="form-container">

        <h2>se connecter</h2>

        <form method="POST" action="/mindgrow/controller/AuthController.php">

            <input type="email" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Password" required>
            
    <div class="remember-box">
   <div> <input type="checkbox" name="remember"></div>
  <div>  <span>Remember me</span></div>
</div>

            <button type="submit" name="login">
                Se connecter
            </button>


        </form>


        <p>
            Vous n'avez pas de compte ?
            <a href="/mindgrow/view/register.php">
                Créer un compte
            </a>
        </p>
        <div class="back-home">
    <a href="../public/index.php?page=landing">← Retour à l’accueil</a>
</div>

    </div>

</body>

</html>

