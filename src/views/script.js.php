<?php if(isset($_SESSION["utilisateur"]) && !empty($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]->getRole() != "admin") :?>
    //on charge uniquement les événements lorsque l'utilisateur est connecté
    $(function(){
      userOnly();
    })

<?php elseif(isset($_SESSION["utilisateur"]) && $_SESSION["utilisateur"]->getRole() == "admin") : ?>
    $(function()
    {
        //sinon seulement les événements liés à l'utilisateur administrateur
        adminOnly();
    });
<?php else: ?>
    $(function()
    {
        //sinon seulement les événements liés à l'utilisateur anonyme
        anonymousOnly();
    });
<?php endif ?>
function anonymousOnly()
{


    $("nav").on("click","#sub_login",function(){
        window.location.href = "index.php?method=loginPage";
    })
    //on autorise la recherche
    $("#select_critere").change(function(){
        if($(this).val() == "-1")
            $("#searchBtn").prop("disabled",true);
        else
            $("#searchBtn").prop("disabled",false);
        $("input[name='critere_recherche']").val($(this).val());
    })
}

function adminOnly()
{
    $("#confirmChangeUser").prop("disabled",true);
    $("#select_critere").change(function(){
        if($(this).val() == "-1")
            $("#searchBtn").prop("disabled",true);
        else
            $("#searchBtn").prop("disabled",false);
        $("input[name='critere_recherche']").val($(this).val());
    })

    $(".supprimerProduit").click(function(){
        $("#deleteProduct_name").text(JSON.parse(this.value)["nom"])
        $("#deleteProductForm input").val(this.value);
    })

    $("#confirmDelete").click(function(){
        $("#deleteProductForm").submit()
    })

    $("#confirmChange").prop("disabled",true);
    $("#confirmCreate").click(function(){
        let error = 0;
        if($("input[name='nameProduct']").val() == "") {
            $("#errorName").text("Ce champ ne doit pas être vide");
            error++;
        }
        if($("#existentIngredient").val() == "-1") {
            $("#errorIng").text("Choisissez une valeur");
            error++;
        }
        if($("#existentCategory").val() == "-1") {
            $("#errorCat").text("Choisissez une valeur");
            error++;
        }
        if($("input[name='priceProduct']").val() <= 0) {
            $("#errorPrice").text("Valeur incorrecte");
            error++;
        }
        if($("#newCat").val() != "" && $("#newCat").css("display") == "block")
        {
            $("#catProduct").val($("#newCat").val());
        }else if($("#newCat").css("display") == "block"){
            $("#errorCat").text("Ce champ ne doit pas être vide");
            error++;
        }

        if($("#newIng").val() != "" && $("#newIng").css("display") == "block")
        {
            $("#IngProduct").val($("#newIng").val());
        }else if($("#newIng").css("display") == "block"){
            $("#errorIng").text("Ce champ ne doit pas être vide");
            error++;
        }

        if(error == 0)
            $("#newProduct").submit();
    });

    $("#existentCategory").change(function(){
        if(this.value === "newCategory")
        {
            $("#newCat").css("display","block");
        }else{
            $("#catProduct").val(this.value);
            $("#newCat").css("display","none");
            $("#errorCat").text("");
        }
    })

    $("#existentIngredient").change(function(){
        if(this.value === "newIngredient")
        {
            $("#newIng").css("display","block");
        }else{
            $("#IngProduct").val(this.value);
            $("#newIng").css("display","none");
            $("#errorIng").text("");
        }
    })

    let changesFields_users = ["NameUser","FirstnameUser","EmailUser","RoleUser"];
    $("#listeUtilisateurs").on("click",".modifierUtilisateur",function(){
        let data = JSON.parse($(this).val());
        $("#changeUserForm input[name='NameUser']").val(data["name"]);
        $("#changeUserForm input[name='FirstnameUser']").val(data["firstname"]);
        $("#changeUserForm input[name='EmailUser']").val(data["email"]);
        $("#changeUserForm input[name='initialRoleUser']").val(data["role"]);
        $("#changeUserForm input[name='initialNameUser']").val(data["name"]);
        $("#changeUserForm input[name='initialFirstnameUser']").val(data["firstname"]);
        $("#changeUserForm input[name='initialEmailUser']").val(data["email"]);
        $("#changeUserForm input[name='id']").val(data["id"]);
        $("#existentRoleUpdate").selectpicker("val",data["role"]);
    })

    $("#changeUserForm input, #changeUserForm select").change(function(){
        let changes = 0;
        for(field of changesFields_users)
        {
            if(field == "RoleUser"){
                if($("#existentRoleUpdate").val() != $("input[name='initialRoleUser']").val()) {
                    changes++;
                }
            }
            else if($("#changeUserForm input[name='"+field+"']").val().trim() != $("#changeUserForm input[name='initial"+field+"']").val())
                changes++;
        }

        if(changes > 0)
            $("#confirmChangeUser").prop("disabled",false);
        else
            $("#confirmChangeUser").prop("disabled",true);
    })

    $(".supprimerUtilisateur").click(function(){
        let data = JSON.parse(this.value);
        $("#userdata").html("<ul><li>Nom : <strong>"+data.name+"</strong></li>"+
            "<li>Prénom : <strong>"+data.firstname+"</strong></li>"+
            "<li>Adresse email : <strong>"+data.email+"</strong>"+
            "</li><li>Rôle : <strong>"+data.role+"</strong></li></ul>")
        $("#deleteUser input[name='dataUser']").val(this.value);
    })

    $("#confirmCreateUser").click(function(){
        let fields = ["NameUser","FirstnameUser","PasswordUser","EmailUser"];
        let error = 0;
        for(field of fields)
        {
            if($("#newUser input[name='"+field+"']").val() == "")
            {
                $("#error"+field).text("Saisir une valeur");
                error++;
            }

        }
        if(error == 0)
            $("#newUser").submit();
    })


    $("#listeProduits").on("click",".modifierProduit",function(){
        let data = JSON.parse($(this).val());
        $("#changeProductForm input[name='NameProduct']").val(data["nom"]);
        $("#changeProductForm input[name='DescProduct']").val(data["description"]);
        $("#changeProductForm input[name='PriceProduct']").val(data["prix"]);
        $("#newCatUpdate").val("");
        $("#newIngUpdate").val("");
        $("#changeProductForm input[name='initialNameProduct']").val(data["nom"]);
        $("#changeProductForm input[name='initialDescProduct']").val(data["description"]);
        $("#changeProductForm input[name='initialCatProduct']").val(data["categorie"]);
        $("#changeProductForm input[name='CatProductId']").val(data["idc"]);
        $("#changeProductForm input[name='initialIngProduct']").val(data["ingredient"]);
        $("#changeProductForm input[name='IngProductId']").val(data["idi"]);
        $("#changeProductForm input[name='initialPriceProduct']").val(data["prix"]);
        console.log(data["ingredient"],data["categorie"]);
        $("#existentIngredientUpdate").selectpicker("val",data["ingredient"]);
        $("#existentCategoryUpdate").selectpicker("val",data["categorie"]);
        $("#changeProductForm input[name='idp']").val(data["idp"])
    })

    $("#confirmChangeUser").click(function(){
        $("#changeUserForm").submit();
    })

    let changesFields = ["NameProduct","DescProduct","newIngUpdate","newCatUpdate","IngProduct","CatProduct","PriceProduct"];
    $("#changeProductForm input, #changeProductForm select").change(function(){
       // console.log($(this).prop("name"));
        let changes = 0;
        for(field of changesFields)
        {
            if(field == "IngProduct"){
                if($("#existentIngredientUpdate").val() != "-1" && $("#existentIngredientUpdate").val() != "newIngredient" && $("#existentIngredientUpdate").val() != $("input[name='initialIngProduct']").val())
                    changes++;
            }else if(field == "CatProduct"){
                if($("#existentCategoryUpdate").val() != "-1" && $("#existentCategoryUpdate").val() != "newCategory" && $("#existentCategoryUpdate").val() != $("input[name='initialCatProduct']").val())
                    changes++;
            }else if(field == "newIngUpdate" && $("#newIngUpdate").css("display") == "block" && $("#newIngUpdate").val() != "" && $("#newIngUpdate").val() != $("#changeProductForm input[name='initialIngProduct']").val())
                changes++;
            else if(field == "newCatUpdate" && $("#newCatUpdate").css("display") == "block" && $("#newCatUpdate").val() != "" && $("#newCatUpdate").val() != $("#changeProductForm input[name='initialCatProduct']").val())
                changes++;
            else if(field != "newIngUpdate" && field != "newCatUpdate" && $("#changeProductForm input[name='"+field+"']").val().trim() != $("#changeProductForm input[name='initial"+field+"']").val())
                changes++;
        }

        if(changes > 0)
            $("#confirmChange").prop("disabled",false)
        else
            $("#confirmChange").prop("disabled",true);
    })

    $("#existentIngredientUpdate").change(function(){
        if(this.value == "newIngredient")
            $("#newIngUpdate").css("display","block");
        else
            $("#newIngUpdate").css("display","none");

    })

    $("#existentCategoryUpdate").change(function(){
        if(this.value == "newCategory")
            $("#newCatUpdate").css("display","block");
        else
            $("#newCatUpdate").css("display","none");
    })

    $("#confirmChange").click(function(){
        $("#changeProductForm").submit();
    })

    //$("#changeProduct").on("change","input[name='descProduct']",function(){
    //    if($(this).val().trim() != $("#changeProduct input[name='initialDescription']").val())
    //        $("#confirmChange").prop("disabled",false);
    //    else
    //        $("#confirmChange").prop("disabled",true);
    //})
    //
    //$("#changeProduct").on("change","input[name='nameProduct']",function(){
    //    if($(this).val().trim() != $("#changeProduct input[name='initialName']").val())
    //        $("#confirmChange").prop("disabled",false);
    //    else
    //        $("#confirmChange").prop("disabled",true);
    //})
    //
    //$("#changeProduct").on("change","input[name='nameProduct']",function(){
    //    if($(this).val().trim() != $("#changeProduct input[name='initialName']").val())
    //        $("#confirmChange").prop("disabled",false);
    //    else
    //        $("#confirmChange").prop("disabled",true);
    //})


    $("#logout").click(function(){
        window.location.href = "index.php?method=logout";
    })
}

function userOnly()
{
    $("#searchBtn").prop("disabled",false);
    $(".modal-footer #confirmChange").prop("disabled",true);
    getCart().fail(function(data,status){
        if(data.status === 404)
        {
            console.log("404 Votre panier est pour le moment vide");
            $("button[data-target='#cart']").prop("disabled",true);
            $("#validateCart").prop("disabled",true)
        }
    })


    $("#produits").on("click",'.ajout',function(){
        let resource = JSON.parse($(this).val());
        $("#modal #nomProduit").text(resource.nom);
        $("#modal #idProduit").val(resource.id);
    });

    $("#confirmAdd").click(function(){
        if($("#modal input[name='qte']").val() === "")
            $("#modal .error").text("Vous devez préciser une quantité");
        else if($("#modal input[name='qte']").val() === "0")
            $("#modal .error").text("Valeur invalide");
        else {
            $.post("index.php?method=addToCart",{idp: $("#modal #idProduit").val(), qte: $("#modal input[name='qte']").val()},function(res){
                $("#modal").modal("hide");
                $("#modal").modal("dispose");
                $("#modal #idProduit").val("");
                $("#modal input[name='qte']").val("");
                $("#modal #nomProduit").text("");
                $.get("index.php?method=getCart").done(function(data){
                    data = JSON.parse(data);
                    $(this).attr("disabled",true);
                    $("#myProducts").html( // on réinitialise la table pour réimporter les produits
                        "<tr>" +
                        "<th>Nom</th>" +
                        "<th>Prix</th>" +
                        "<th>Quantité</th>" +
                        "<th>Action</th>" +
                        "</tr>")
                    getCart();
                    $("button[data-target='#cart']").prop("disabled",false);
                    $("#validateCart").prop("disabled",false)
                }).fail(function(data,status){
                    if(status == "error")
                    {
                        console.log("Votre panier est pour le moment vide");
                        $("button[data-target='#cart']").prop("disabled",true);
                        $("#validateCart").prop("disabled",true)
                    }
                })
            });
        }
    });

    //Préparer la sauvegarde des modifications quand il le faut (vérifier anciennes valeurs par rapport aux nouvelles)
    $("#myProducts").on("change",".change_qte",function () {
        if($("#initialQte_"+this.id).val() != $(this).val())
            $(".modal-footer #confirmChange").prop("disabled",false);
        else
            $(".modal-footer #confirmChange").prop("disabled",true);

    })

    $("#cart").on("click",".removeProduct",function(){
        $(".modal-footer #confirmChange").prop("disabled",false);
        $("#produit_"+this.value).css("display","none");
        $("#delete_"+this.value).val(1);
    })

    $(".modal-footer").on("click","#confirmChange",function(){
        var data = [];
        $(".change_qte").each(function(){
            var id = this.id.replace(/change_qte/,'')
            data.push({ id_panier: id, qte: this.value, delete: $("#delete_"+id).val() })
        })

        $.post("index.php?method=updateCarts",{ data }).always(function(response){
            $(".modal-footer #confirmChange").prop("disabled",true);

            for(node of data) {
                if (node.delete === "1") {
                    console.log("Le nœud pour l'id " + node.id_panier + " doit être supprimé...")
                    $("#produit_" + node.id_panier).remove();
                }else
                {
                    $("#initialQte_"+node.id_panier).val(node.qte);
                }
            }
            $("#cart").modal("hide");
            $("#cart").modal("dispose");
        }).fail(function(data,status){
            if(data.status === 404)
            {
                console.log("404 Votre panier est pour le moment vide");
                $("button[data-target='#cart']").prop("disabled",true);
                $("#validateCart").prop("disabled",true)
            }
        })

    })
    $("#btnCart").click(function(){
        $(".modal-footer #confirmChange").prop("disabled",true);
        $(".delete").val(0);
        $(".produit").each(function(){
            var id = this.id.replace(/produit_/,'');
            $("#change_qte"+id).val($("#initialQte_"+id).val());
            $(this).css("display","table-row");
        })
    });

    $("#validateCart").click(function(){
        window.location.href = "index.php?method=validateCart";
    })

    //code pour la déconnexion écrite en JS (pas besoin pour référencement)
    $("#logout").click(function(){
        window.location.href = "index.php?method=logout";
    })

    $("#select_critere").change(function(){
        if($(this).val() == "-1")
            $("#searchBtn").prop("disabled",true);
        else
            $("#searchBtn").prop("disabled",false);
        $("input[name='critere_recherche']").val($(this).val());
    })

}
/**
 * Récupérer le panier complet depuis le serveur
 * @returns Promise
 */
function getCart()
{
    return $.get("index.php?method=getCart").done(function(data,status){
        data = JSON.parse(data);
        for(produit of data)
        {
            $("#myProducts").append(
                "<tr class='produit' id='produit_"+produit.id_panier+"'>" +
                "<td>" + produit.nom + "</td>"
                + "<td>" + produit.prix + "€</td>" +
                "<td><input type='number' min='1' style='width:80%' id='change_qte" + produit.id_panier + "' class='change_qte form-control' value='" + produit.qte + "'/>" +
                "<input type='hidden' class='delete' id='delete_"+produit.id_panier+"' value='0'>"+
                "<input type='hidden' name='id' value='" + produit.id_panier + "'/>" +
                "<input type='hidden' name='qte' id='initialQte_" + produit.id_panier + "' value='" + produit.qte + "'/></td>" +
                "<td><button class='btn btn-warning removeProduct' value=" + produit.id_panier + ">Retirer</button></td>"
                + "</tr>")
        }

    });
}