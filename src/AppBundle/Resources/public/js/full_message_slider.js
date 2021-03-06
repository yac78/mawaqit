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
        }, 200, function () {
            $('#slider li:first-child').appendTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    },
    setFontSize: function () {
        $('#slider .text > div').each(function (i, slide) {
            fixFontSize(slide, 100);
        });
    }
};

messageInfoSlider.run();

setInterval(function () {
    $.ajax({
        url: $("#slider").data("remote") + "?lastUpdatedDate=" + lastUpdated,
        success: function (resp) {
            if (resp.hasBeenUpdated === true) {
                reloadIfConnected();
            }
        }
    });
}, 3 * 60000);

setInterval(function () {
    let date = dateTime.getCurrentDate(locale, "short", "2-digit", "2-digit", "2-digit").firstCapitalize();
    $(".date").text(date);
    $(".time").text(dateTime.getCurrentTime());
}, 1000);

setInterval(function () {
    let date = new Date();
    if (date.getHours() === 2) {
        reloadIfConnected();
    }
}, 3600000);