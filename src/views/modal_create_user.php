<div class="modal" id="createUser" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Création d'un utilisateur</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Veuillez remplir le formulaire suivant :</strong></p>
                <form class="form-group" method="post" id="newUser" action="index.php?method=createUser">
                    <label for="nameUser">Nom de l'utilisateur</label>
                    <input type="text" name="NameUser" class="form-control" />
                    <small id="errorNameUser" class="error"></small>
                    <label for="descProduct">Prénom de l'utilisateur</label>
                    <input type="text" name="FirstnameUser" class="form-control" />
                    <small id="errorFirstnameUser" class="error"></small>
                    <label for="descProduct">Email de l'utilisateur</label>
                    <input type="email" name="EmailUser" class="form-control" />
                    <small id="errorEmailUser" class="error"></small>
                    <label for="PasswordUser">Mot de passe de l'utilisateur</label>
                    <input type="password" name="PasswordUser" class="form-control" />
                    <small id="errorPasswordUser" class="error"></small>
                    <select id="roleUser" name="roleUser" class="selectpicker mr-3" data-dropup-auto="false">
                        <option value="client">client</option>
                        <option value="admin">admin</option>
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="confirmCreateUser" class="btn btn-success">Créer utilisateur</button>
            </div>
        </div>
    </div>
</div>