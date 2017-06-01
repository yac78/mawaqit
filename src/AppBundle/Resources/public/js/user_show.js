$("#user_enabled").change(function (event) {
    $.ajax({
        url: "../enable/" + $(this).data('id') + '/' + $(this).is(':checked'),
        success: function (resp) {
             console.log($(this).is(':checked'));
        }
    });
});
