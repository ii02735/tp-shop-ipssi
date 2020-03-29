<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Ma boutique en ligne</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php if(isset($_SESSION["utilisateur"]) && !empty($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]->getRole() == "admin"): ?>
        <ul class="navbar-nav mr-auto">
            <?php if(!isset($_GET["method"]) && empty($_GET["method"])): ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?method=users">Utilisateurs</a>
            </li>
            <?php elseif (isset($_GET["method"]) && !empty($_GET["method"]) && $_GET["method"] == "users"): ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Produits</a>
            </li>
            <?php endif ?>
        </ul>
        <?php endif ?>
       <?php if(!isset($_GET["method"]) && empty($_GET["method"])): ?>
        <ul class="navbar-nav ml-auto" id="actionsUtilisateurStandard">
            <select id="select_critere" class="selectpicker mr-3">
                <option value="-1">Critère de recherche</option>
                <option value="produit">Produit</option>
                <option value="categorie">Catégorie</option>
                <option value="ingredient">Ingrédient</option>
            </select>
            <form class="form-inline my-2 my-lg-0" method="get" action="index.php">
                <input type="hidden" name="critere_recherche" value="-1" />
                <input id="recherche" name="text_recherche" class="form-control mr-sm-2" type="search" placeholder="Rechercher un produit..." aria-label="Rechercher">
                <button class="btn btn-outline-success my-2 my-sm-0" id="searchBtn" type="submit" disabled>Rechercher</button>
            </form>
        <?php endif ?>
            <li class="nav-item active mx-1" style="list-style: none">
                <?php if(isset($_SESSION["utilisateur"]) && !empty($_SESSION["utilisateur"])): ?>
                    <button class="btn btn-outline-danger my-2 my-sm-0" id="logout">Se déconnecter</button>
                <?php else : ?>
                    <button class="btn btn-outline-primary my-2 my-sm-0" id="sub_login">Se connecter</button>
                <?php endif ?>
            </li>
        </ul>
    </div>
</nav>