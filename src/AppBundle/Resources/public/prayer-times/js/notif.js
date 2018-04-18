$(".toggle").click(function () {
    $(".sidebar").toggleClass('active');

});
$(".cancel").click(function () {
    $(this).parent().toggleClass('gone');
});