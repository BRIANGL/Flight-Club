<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
 * 404 page
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Erreur 404 - Flight Club</title>
    <meta name="description" content="Flight Club">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=dce1ced0acba79984dca99bab3de3321">
    <link rel="manifest" href="manifest.json?h=3376faf9af9d01ed3ad3ca66d606c630">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css?h=2cbf12caab31562d03bae9544edcad5f">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css?h=2cbf12caab31562d03bae9544edcad5f">
    <link rel="stylesheet" href="assets/fonts/typicons.min.css?h=2cbf12caab31562d03bae9544edcad5f">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css?h=2528b47b032341ce951bad4eb7bec58f">
</head>

<body style="display: flex;flex-direction: column;height: 100vh;">
    <div style="flex: 1 0 auto;">
        <?php
        require_once("./assets/php/nav.php");
        ?>
        <main class="page login-page">
            <section class="clean-block clean-form dark">
                <div class="block-heading">
                    <div class="container">
                        <h2 class="text-info">Erreur 404</h2>
                        <p>La page n'a pas été trouvée</p>
                    </div>
                </div>
            </section>
        </main><!-- Start: Footer Dark -->
    </div><?php
            require_once("./assets/php/footer.php");
            ?>
    <!-- End: Footer Dark -->
    <script src="assets/bootstrap/js/bootstrap.min.js?h=0de0a05340ecfd010938b6967a030757"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="assets/js/script.min.js?h=d28daa69ae332709e94e8243f638cce6"></script>
</body>

</html>