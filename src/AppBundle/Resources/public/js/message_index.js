$("#timeToDisplayMessageButton").click(function (e) {
    e.preventDefault();
    var self = this;
    $.post({
        type: "POST",
        url: $(self).data("remote"),
        data: {'timeToDisplayMessage': $("#configuration_timeToDisplayMessage").val() },
        success: function (data) {
           $(self).fadeOut(500);
        },
        error: function (data) {

        },
    });
});

$("#configuration_timeToDisplayMessage").on("keyup change", function (e) {
    $("#timeToDisplayMessageButton").fadeIn(500);
})