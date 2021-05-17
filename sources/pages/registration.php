<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * registration page
 */
require_once("./controllers/registration_controller.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>S'enregistrer - Flight Club</title>
    <meta name="description" content="Flight Club">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=2fc55c83fda89d85c10ec2813155ab55">
    <link rel="manifest" href="manifest.json?h=44264fee9c6d05944711595970c43d33">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css?h=320bd0471c3e8d6b9dd55c98e185506c">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css?h=320bd0471c3e8d6b9dd55c98e185506c">
    <link rel="stylesheet" href="assets/fonts/typicons.min.css?h=320bd0471c3e8d6b9dd55c98e185506c">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="assets/css/vanilla-zoom.min.css?h=e737f76df021b46fa7180f48799a5d20">
</head>

<body style="display: flex;flex-direction: column;height: 100vh;">
    <div style="flex: 1 0 auto;">
        <?php
        require_once("./assets/php/nav.php");
        ?>

        <main class="page registration-page">
            <section class="clean-block clean-form dark">
                <div class="container">
                    <div class="block-heading">
                        <h2 class="text-info">S'enregistrer</h2>
                        <p>Vous n'avez pas de comptes et souhaitez enregistrer un vol? Vous êtes sur la bonne page</p>
                    </div>
                    <form method="POST" action="">
                        <?php if ($message != "") { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erreur !</strong> <?= $message ?>
                            </div>
                        <?php } ?>
                        <div class="mb-3"><label class="form-label" for="email">Email</label><input class="form-control item" type="email" id="email" name="email" value="<?=$email?>"></div>
                        <div class="mb-3"><label class="form-label" for="password">Mot de passe</label><input class="form-control item" type="password" id="password" name="password"></div>
                        <div class="mb-3"><label class="form-label" for="password2">Répétez votre mot de passe</label><input class="form-control item" type="password" id="password2" name="password2"></div>
                        <button class="btn btn-primary" type="submit">S'enregistrer</button>
                    </form>
                </div>
            </section>
        </main><!-- Start: Footer Dark -->
    </div><?php
        require_once("./assets/php/footer.php");
        ?>
        <!-- End: Footer Dark -->
        <script src="assets/bootstrap/js/bootstrap.min.js?h=1eb47230ed13e88113270f63f470e160"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
        <script src="assets/js/vanilla-zoom.js?h=6a37ea9c996b05f763161c73127d66bc"></script>
        <script src="assets/js/theme.js?h=a083aeb15550c5e1266c666e8a5846d9"></script>
</body>

</html>