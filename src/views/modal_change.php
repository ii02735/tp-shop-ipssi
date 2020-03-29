<div class="modal" id="changeProduct" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Modifier produit</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-group" method="post" id="changeProductForm" action="index.php?method=updateProduct" >
                    <label for="NameProduct">Nom du produit</label>
                    <input type="text" name="NameProduct" class="form-control" />
                    <label for="DescProduct">Description du produit</label>
                    <input type="text" name="DescProduct" class="form-control" />
                    <label for="CatProduct" style="display: block" class="my-2">Catégorie du produit</label>
                    <select id="existentCategoryUpdate" name="CatProduct" class="selectpicker mr-3" data-dropup-auto="false">
                        <option value="-1">Catégorie existante...</option>
                        <? foreach ($categories as $category) : ?>
                            <option value="<?php echo $category ?>"><?php echo $category ?></option>
                        <?php endforeach ?>
                        <option value="newCategory">Nouvelle catégorie...</option>
                    </select>
                    <input id="newCatUpdate" type="text" name="newCatProduct" class="form-control" placeholder="Libellé de la nouvelle catégorie"/>
                    <label for="IngProduct" style="display: block" class="my-2">Ingrédient du produit</label>
                    <select id="existentIngredientUpdate" name="IngProduct"  class="selectpicker mr-3" data-dropup-auto="false">
                        <option value="-1">Ingrédient existant...</option>
                        <? foreach ($ingredients as $ingredient) : ?>
                            <option value="<?php echo $ingredient ?>"><?php echo $ingredient ?></option>
                        <?php endforeach ?>
                        <option value="newIngredient">Nouvel ingrédient...</option>
                    </select>
                    <input id="newIngUpdate" type="text" name="newIngProduct" class="form-control" placeholder="Nom du nouvel ingrédient"/>
                    <label for="PriceProduct" style="display: block">Prix du produit</label>
                    <input type="number" min="0.01" step="0.01" name="PriceProduct" class="form-control" />

                    <input type="hidden" name="idp" />
                    <input type="hidden" name="initialNameProduct" />
                    <input type="hidden" name="initialDescProduct" />
                    <input type="hidden" name="initialCatProduct" />
                    <input type="hidden" name="CatProductId" />
                    <input type="hidden" name="initialIngProduct" />
                    <input type="hidden" name="IngProductId" />
                    <input type="hidden" name="initialPriceProduct" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="confirmChange" class="btn btn-success">Confirmer modifications</button>
            </div>
        </div>
    </div>
</div>