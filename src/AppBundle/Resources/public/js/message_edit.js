function handleEnablingBloc() {
    if ($('#message_enabled').is(":checked")) {
        $(".bloc-enabling").show();
    } else {
        $(".bloc-enabling").hide();
    }
}

$('#message_enabled').change(function () {
    handleEnablingBloc();
});
handleEnablingBloc();