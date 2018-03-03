$("#user_complete").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: $("#user_complete").data("remote"),
            dataType: "json",
            data: {
                term: request.term
            },
            success: function (data) {
                response(data);
            }
        });
    },
    minLength: 2,
    select: function (event, ui) {
        $("#user").val(ui.item.id);
    }
});


function typeDisplayHandler() {
    $type = $("#type");
    $(".form-block").addClass("hidden");

    if($type.val()){
        $(".form-block").removeClass("hidden");
        $(".mosque-block").addClass("hidden");
        $("."+$type.val()+"-block").removeClass("hidden");
    }
}

$("#type").bind("change", function (event) {
    typeDisplayHandler();
});

typeDisplayHandler();