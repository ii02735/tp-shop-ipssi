<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Ajout panier</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Vous avez souhaité ajouter l'article suivant :</p>
                <p><strong><span id="nomProduit"></span></strong></p>
                <p>Précisez la quantité :</p>
                <input type="number" min="1" name="qte">
                <input type="hidden" value="" id="idProduit">
                <p><small class="error"></small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="confirmAdd" class="btn btn-success">Confirmer ajout</button>
            </div>
        </div>
    </div>
</div>