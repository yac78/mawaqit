$(document).ready(function () {
    $("." + $("#appbundle_configuration_sourceCalcul").val()).removeClass("hidden");
    $(".calendar-prayer input").each(function (index) {
        if ($(this).val() == "")
        {
            $(this).css("background-color", "#f8d4d4");
        }
    });
});

$("#appbundle_configuration_sourceCalcul").bind("change keyup", function (event) {
    $(".api, .calendar").addClass("hidden");
    $("." + $(this).val()).removeClass("hidden");
});

$(".calendar-prayer input").bind("change keyup", function (event) {
    $(this).css("background-color", $(this).val().match(/\d{2}:\d{2}/g) ? "#ffffff" : "#f8d4d4");
});