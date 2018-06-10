$("#fos_user_registration_form_tou").change(function (e) {
    $validButton = $('#validate-btn');
    $validButton.prop('disabled', true);
    if ($(this).is(":checked")) {
        $validButton.prop('disabled', false);
    }
});