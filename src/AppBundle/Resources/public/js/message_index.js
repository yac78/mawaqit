$("#timeToDisplayMessageButton").click(function (e) {
    e.preventDefault();
    var self = this;
    $.post({
        type: "POST",
        url: $(self).data("remote"),
        data: {'timeToDisplayMessage': $("#configuration_timeToDisplayMessage").val()},
        success: function (data) {
            $(self).fadeOut(500);
            $('.timeToDisplayMessageError').addClass('hidden');
        },
        error: function (data) {
            $('.timeToDisplayMessageError').removeClass('hidden').html(data.responseText);
        },
    });
});

$("#configuration_timeToDisplayMessage").on("keyup change", function (e) {
    $("#timeToDisplayMessageButton").fadeIn(500);
});

var url = window.location.href;
var activeTab = url.substring(url.indexOf("#") + 1);
$('a[href="#'+ activeTab +'"]').tab('show')