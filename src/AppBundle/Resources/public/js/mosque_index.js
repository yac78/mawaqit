$(".btn-refresh").click(function () {
    $.ajax({
        url: $(this).data('url'),
        success: function () {
            showModal("Demande de rafraîchissement de l'écran effectuée avec succès");
        },
        error: function () {
            showModal("Une erreur est survenue !");
        }
    });
});

$("#advanced-search-btn").click(function (e) {
    e.preventDefault();
    $("#advanced").toggleClass("hidden")
});

$("#country").change(function (e) {
    var self = $(this);
    $.ajax({
        url: self.data("remote").replace('-country-', self.val()),
        dataType: "json",
        success: function (data) {
            $("#city option").remove();
            $("#city").append('<option>Ville</option>');
            $.each(data, function (i, city) {
                $("#city").append('<option value="' + city + '">' + city + '</option>');
            })
        }
    });
});
