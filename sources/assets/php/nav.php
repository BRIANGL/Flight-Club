<nav class="navbar navbar-light navbar-expand-lg fixed-top bg-white clean-navbar">
    <div class="container"><a class="navbar-brand logo" href="?page=homepage">Flight Club</a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="?page=homepage">Accueil</a></li>
                <?php
                if (!$_SESSION['connected']) {
                ?>
                    <li class="nav-item"><a class="nav-link" href="?page=login">Se connecter</a></li>
                    <li class="nav-item"><a class="nav-link" href="?page=register">S'enregistrer</a></li>
                <?php
                } else {
                ?>
                    <li class="nav-item"><a class="nav-link" href="?page=GroupMenu">Groupes</a></li>
                    <li class="nav-item"><a class="nav-link" href="?page=userModify">Modifier mon profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="?page=logout">Se déconnecter</a></li>

                <?php
                }
                ?>


                <li class="nav-item"><a class="nav-link" href="?page=faq">FAQ</a></li>
            </ul>
        </div>
    </div>
</nav>