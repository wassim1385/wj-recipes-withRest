class myRecipes{

    constructor() {
        $ = jQuery.noConflict(true);
        this.events();
    }

    events() {
        $('#my-notes').on('click', '.delete-note', this.deleteRecipe);
        $('#my-notes').on('click', '.edit-note', this.editRecipe.bind(this));
        $('#my-notes').on('click', '.update-note', this.updateRecipe.bind(this));
        $('.submit-note').on('click', this.createRecipe.bind(this));
    }

    createRecipe(e){
        var newPost = {
            "title": $(".new-note-title").val(),
            "content": $(".new-note-body").val(),
            "status": 'publish'
        }
        if($(".new-note-title").val() != '') {
            $.ajax({
                beforeSend: (xhr) =>{
                    xhr.setRequestHeader('X-WP-Nonce', recipesData.nonce)
                },
                url: recipesData.root_url + "/wp-json/wp/v2/wj-recipes/",
                type: "POST",
                data: newPost,
                success: (response) => {
                    $(".new-note-title, .new-note-body").val('');
                    $(`<li data-id="${response.id}">
                    <input readonly class="note-title-field" type="text" value="${response.title.raw}">
                    <span class="edit-note"><i class="fa fa-pencil"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o"></i> Delete</span>
                    <textarea readonly class="note-body-field" name="" id="">${response.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right"></i> Save</span>
                    </li>`).prependTo('#my-notes').hide().fadeIn(1000);
                    $('.note-limit-message').removeClass('active')
                    console.log("Congrats");
                    console.log(response);
                },
                error: (response) => {
                    console.log("Sorry!");
                    console.log(response);
                }
            })
        } else {
            $('.note-limit-message').addClass('active')
        }
        
    }

    updateRecipe(e){
        var thisRecipe = $(e.target).parents("li");
        var ourUpdatedtPost = {
            "title": thisRecipe.find(".note-title-field").val(),
            "content": thisRecipe.find(".note-body-field").val()
        }
        $.ajax({
            beforeSend: (xhr) =>{
                xhr.setRequestHeader('X-WP-Nonce', recipesData.nonce)
            },
            url: recipesData.root_url + "/wp-json/wp/v2/wj-recipes/" + thisRecipe.data("id"),
            type: "POST",
            data: ourUpdatedtPost,
            success: (response) => {
                this.makeRecipeReadOnly(thisRecipe);
                console.log("Congrats");
                console.log(response);
            },
            error: (response) => {
                console.log("Sorry!");
                console.log(response);
            }
        })
    }

    deleteRecipe(e){
        var thisRecipe = $(e.target).parents("li");
        $.ajax({
            beforeSend: (xhr) =>{
                xhr.setRequestHeader('X-WP-Nonce', recipesData.nonce)
            },
            url: recipesData.root_url + "/wp-json/wp/v2/wj-recipes/" + thisRecipe.data("id"),
            type: "DELETE",
            success: (response) => {
                thisRecipe.slideUp();
                console.log("Congrats");
                console.log(response);
            },
            error: (response) => {
                console.log("Sorry!");
                console.log(response);
            }
        })
    }

    editRecipe(e){
        var thisRecipe = $(e.target).parents("li");
        if(thisRecipe.data("state") == "editable" ) {
            this.makeRecipeReadOnly(thisRecipe)
        } else {
            this.makeRecipeEditable(thisRecipe)
        }
    }

    makeRecipeEditable(thisRecipe) {
        thisRecipe.find(".edit-note").html('<i class="fa fa-times"></i> Cancel')
        thisRecipe.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
        thisRecipe.find(".update-note").addClass("update-note--visible");
        thisRecipe.data("state", "editable")
    }

    makeRecipeReadOnly(thisRecipe) {
        thisRecipe.find(".edit-note").html('<i class="fa fa-pencil"></i> Edit')
        thisRecipe.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        thisRecipe.find(".update-note").removeClass("update-note--visible");
        thisRecipe.data("state", "cancel")
    }

}

var recipes = new myRecipes();