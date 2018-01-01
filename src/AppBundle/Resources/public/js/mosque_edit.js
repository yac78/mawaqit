$("#mosque_user_complete").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: $("#mosque_user_complete").data("remote"),
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
        $("#mosque_user").val(ui.item.id);
    }
});