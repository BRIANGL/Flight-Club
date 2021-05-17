<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>FAQ - Flight Club</title>
    <meta name="description" content="TPI GOLAY Brian">
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
        <!-- Start: Contact Us Form -->
        <main class="page login-page">
            <section class="clean-block clean-form dark">
                <div class="container">
                    <div class="block-heading">
                        <h2 class="text-info">Frequently Asked Questions (FAQ)</h2>
                        <p>Les réponses à vos questions</p>
                    </div>
                    <form style="max-width: 100%;">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Réponse</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Comment ajouter un vol à son carnet de vol?</td>
                                        <td>Pour ajouter un vol à son carnet de vol il faut se connecter, ensuite il faut aller sous: Carnet de vol -> Ajouter un vol. Il ne vous reste plus qu'a remplire les données du vol</td>
                                    </tr>
                                    <tr>
                                        <td>Comment télécharger les données de mon carnet de vol?</td>
                                        <td>Pour télécharger son carnet de vol il faut se connecter, ensuite il faut aller sous: "Carnet de vol" -> "Télécharger en PDF" ou "Télécharger en CSV"</td>
                                    </tr>
                                    <tr>
                                        <td>Est-ce que tout le monde a accès à mon carnet de vol?</td>
                                        <td>Non. Les seules personnes à pouvoir consulter votre carnet de vol sont les membres des groupes auxquels vous appartenez et vous-même</td>
                                    </tr>
                                    <tr>
                                        <td>Est-ce que tout le monde peut modifier mon carnet de vol?</td>
                                        <td>Non. Vous êtes la seule personne à pouvoir modifier vos vols</td>
                                    </tr>
                                    <tr>
                                        <td>J'ai une erreur lors de l'importation d'images.</td>
                                        <td>Vérifiez que l'images que vous importez soit a l'un des formats suivants: .gif,.png,.jpeg ou .jpg. Vérifiez aussi que la taille des images est inférieur à 3Mo par image et que le total des images ne dépasse pas 70Mo</td>
                                    </tr>
                                    <tr>
                                        <td>J'ai mis un mot de passe érroné, puis-je le changer?</td>
                                        <td>Oui, pour le changer, il faut aller sous: "Modifier mon profile" et remplire les champs demandés</td>
                                    </tr>
                                    <tr>
                                        <td>J'ai une question technique, qui doit-je contacter?</td>
                                        <td>Si vous avez une questions, contactez nous par mail à l'adresse suivante: admin@flightclub.ch</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </section>
        </main><!-- End: Contact Us Form -->
    </div>
    <!-- Start: Footer Dark -->
    <?php
    require_once("./assets/php/footer.php");
    ?>
    <!-- End: Footer Dark -->
    <script src="assets/bootstrap/js/bootstrap.min.js?h=1eb47230ed13e88113270f63f470e160"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="assets/js/vanilla-zoom.js?h=6a37ea9c996b05f763161c73127d66bc"></script>
    <script src="assets/js/theme.js?h=a083aeb15550c5e1266c666e8a5846d9"></script>
</body>

</html>