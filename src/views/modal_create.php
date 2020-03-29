<div class="modal" id="create" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Création d'un produit</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Veuillez remplir le formulaire suivant :</strong></p>
                <form class="form-group" method="post" id="newProduct" action="index.php?method=createProduct">
                    <label for="nameProduct">Nom du produit</label>
                    <input type="text" name="nameProduct" class="form-control" />
                    <small id="errorName" class="error"></small>
                    <label for="descProduct">Description</label>
                    <input type="text" name="descProduct" class="form-control" />
                    <label for="catProduct">Catégorie du produit</label>
                    <br/>
                    <select id="existentCategory" class="selectpicker mr-3" data-dropup-auto="false">
                        <option value="-1">Catégorie existante...</option>
                        <? foreach ($categories as $category) : ?>
                            <option value="<?php echo $category ?>"><?php echo $category ?></option>
                        <?php endforeach ?>
                        <option value="newCategory">Nouvelle catégorie...</option>
                    </select>
                    <input id="newCat" type="text" class="form-control" placeholder="Libellé de la nouvelle catégorie"/>
                    <label for="IngProduct">Ingrédient / composition du produit</label>
                    <br/>
                    <select id="existentIngredient" class="selectpicker mr-3" data-dropup-auto="false">
                        <option value="-1">Ingrédient existant...</option>
                        <? foreach ($ingredients as $ingredient) : ?>
                            <option value="<?php echo $ingredient ?>"><?php echo $ingredient ?></option>
                        <?php endforeach ?>
                        <option value="newIngredient">Nouvel ingrédient...</option>
                    </select>
                    <input id="newIng" type="text" class="form-control" placeholder="Nom du nouvel ingrédient"/>
                    <input type="hidden" name="IngProduct" id="IngProduct" />
                    <small id="errorIng" class="error"></small>
                    <input type="hidden" name="catProduct" id="catProduct"/>
                    <small id="errorCat" class="error"></small>
                    <label for="priceProduct">Prix du produit en&nbsp;&euro;</label>
                    <input type="number" min="1" step="1.00" name="priceProduct" class="form-control" />
                    <small id="errorPrice" class="error"></small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="confirmCreate" class="btn btn-success">Créer</button>
            </div>
        </div>
    </div>
</div>