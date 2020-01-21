/**
 * Messages slider class
 * @type {Object}
 */
var messageInfoSlider = {
    slider: $("#slider"),
    interval: null,
    /**
     *  run message slider
     */
    run: function () {
        var screenWidth = $(window).width();
        var nbSlides = $('#slider li').length;
        $('#slider li').width(screenWidth);

        setTimeout(function () {
            messageInfoSlider.setFontSize();
        }, 300);

        var sliderUlWidth = nbSlides * screenWidth;
        $('#slider ul').css({width: sliderUlWidth});
        clearInterval(messageInfoSlider.interval);
        messageInfoSlider.interval = setInterval(function () {
            if (nbSlides > 1) {
                messageInfoSlider.moveRight();
            }
        }, timeToDisplayMessage * 1000);
    },
    /**
     * Get message from server
     */
    moveRight: function () {
        var screenWidth = $(window).width();
        $('#slider ul').animate({
            left: -screenWidth
        }, 1000, function () {
            $('#slider li:first-child').appendTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    },
    setFontSize: function () {
        $('#slider li').each(function (i, slide) {
            var $slide = $(slide);
            if ($slide.find("img").length > 0) {
                return true;
            }
            fixFontSize(slide, 20);
        });
    }
};

messageInfoSlider.run();

setInterval(function () {
    $.ajax({
        url:  $("#slider").data("remote") + "?lastUpdatedDate=" + lastUpdated,
        success: function (resp) {
            if (resp.hasBeenUpdated === true) {
                // check if screen page is ok (http status = 200 )
                $.ajax({
                    url: location.href,
                    method: 'HEAD',
                    success: function (resp) {
                        location.reload();
                    }
                });
            }
        }
    });
}, 3 * 60000);