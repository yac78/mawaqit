$(".copyright-year").text((new Date()).getFullYear());

$("#whatsapp-select").bind("change keyup", function (event) {
    $("#whatsapp-phone").text($(this).val());
    $("#whatsapp-phone").attr("href", "tel:" + $(this).val());
});