$(document).ready(function () {
   $("." + $("#appbundle_configuration_sourceCalcul").val()).removeClass("hidden");
});

$("#appbundle_configuration_sourceCalcul").bind("change keyup", function (event) {
    $(".api, .calendar").addClass("hidden");
    $("." + $(this).val()).removeClass("hidden");
});