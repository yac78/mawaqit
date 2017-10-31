$(".btn-refresh").click(function () {
    $.ajax({
        url: $(this).data('url'),
        success: function () {
            showModal("Écran rafraîchi avec succès");
        },
        error: function () {
            showModal("Une erreur est survenue !");
        }
    });
});
