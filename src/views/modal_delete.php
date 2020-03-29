<div class="modal" id="deleteProduct" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: crimson;">
                <h6 class="modal-title" style="color:white;">Suppression produit</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <p>Vous vous apprêtez à supprimer le produit suivant :<br/><strong><span id="deleteProduct_name"></span></strong></p>
               <p>Cela risque d'affecter le panier des clients, avec les ingrédients et les catégories existantes.</p>
               <form id="deleteProductForm" method="post" action="index.php?method=deleteProduct">
                   <input type="hidden" name="dataProduct" />
               </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="confirmDelete" class="btn btn-danger">Confirmer suppression</button>
            </div>
        </div>
    </div>
</div>