$(function(){
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
                        for(produit of data) {
                            $("#myProducts").append(
                                "<tr class='produit' id='produit_"+produit.id_panier+"'>" +
                                "<td>" + produit.nom + "</td>"
                                + "<td>" + produit.prix + "€</td>" +
                                "<td><input type='number' min='1' style='width:80%' id='change_qte" + produit.id_panier + "' class='change_qte' value='" + produit.qte + "'/>" +
                                "<input type='hidden' class='delete' id='delete_"+produit.id_panier+"' value='0'>"+
                                "<input type='hidden' name='id' value='" + produit.id_panier + "'/>" +
                                "<input type='hidden' name='qte' id='initialQte_" + produit.id_panier + "' value='" + produit.qte + "'/></td>" +
                                "<td><button class='btn btn-warning removeProduct' value=" + produit.id_panier + ">Retirer</button></td>"
                                + "</tr>")
                        }
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

})

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
                "<td><input type='number' min='1' style='width:80%' id='change_qte" + produit.id_panier + "' class='change_qte' value='" + produit.qte + "'/>" +
                "<input type='hidden' class='delete' id='delete_"+produit.id_panier+"' value='0'>"+
                "<input type='hidden' name='id' value='" + produit.id_panier + "'/>" +
                "<input type='hidden' name='qte' id='initialQte_" + produit.id_panier + "' value='" + produit.qte + "'/></td>" +
                "<td><button class='btn btn-warning removeProduct' value=" + produit.id_panier + ">Retirer</button></td>"
                + "</tr>")
        }

    });
}