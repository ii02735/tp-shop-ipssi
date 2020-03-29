<div class="modal" id="changeUser" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Modifier utilisateur</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-group" method="post" id="changeUserForm" action="index.php?method=updateUser" >
                    <label for="NameUser">Nom de l'utilisateur</label>
                    <input type="text" name="NameUser" class="form-control" />
                    <label for="FirstnameUser">Prénom de l'utilisateur</label>
                    <input type="text" name="FirstnameUser" class="form-control" />
                    <label for="EmailUser">Adresse mail de l'utilisateur</label>
                    <input type="text" name="EmailUser" class="form-control" />
                    <label for="RoleUser" style="display: block" class="my-2">Rôle de l'utilisateur</label>
                    <select id="existentRoleUpdate" name="RoleUser" class="selectpicker mr-3" data-dropup-auto="false">
                        <option value="client">client</option>
                        <option value="admin">admin</option>
                    </select>

                    <input type="hidden" name="id" />
                    <input type="hidden" name="initialNameUser" />
                    <input type="hidden" name="initialFirstnameUser" />
                    <input type="hidden" name="initialEmailUser" />
                    <input type="hidden" name="initialRoleUser" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="confirmChangeUser" class="btn btn-success">Confirmer modifications</button>
            </div>
        </div>
    </div>
</div>