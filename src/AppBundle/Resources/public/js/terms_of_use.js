$("#terms-of-use").click(function (e) {
    e.preventDefault();
    $("#terms-of-use-modal").modal({
        keyboard: false,
        backdrop: false
    });
});