$(".iframe-button").click(function () {
    var iframe = '<iframe src="' + $(this).data('url') + '" frameborder="0" scrolling="no" style="width: 400px; height: 200px;"></iframe>'
    $('#iframe-modal #iframe-text').text(iframe);
    $('#iframe-modal #iframe-html').html(iframe);
    $('#iframe-modal').modal();
});