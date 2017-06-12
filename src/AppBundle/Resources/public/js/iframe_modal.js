$(document).ready(function () {
    $(".iframe-button").click(function (event) {
        var iframe =  '<iframe src="' + $(this).data('url') + '" frameborder="0" scrolling="no" style="width: 360px; height: 570px;"></iframe>'
        $('#iframe-modal #iframe-text').text(iframe);
        $('#iframe-modal #iframe-html').html(iframe);
        $('#iframe-modal').modal();
    });
});