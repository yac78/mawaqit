$('#full-screen i').click(function () {
    var self = $(this);
    if (self.hasClass('glyphicon-resize-full')) {
        fullscreen();
        self.removeClass('glyphicon-resize-full');
        self.addClass('glyphicon-resize-small');
    } else if (self.hasClass('glyphicon-resize-small')) {
        exitFullscreen();
        self.removeClass('glyphicon-resize-small');
        self.addClass('glyphicon-resize-full');
    }
})