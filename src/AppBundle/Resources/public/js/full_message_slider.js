/**
 * Messages slider class
 * @type {Object}
 */
var messageInfoSlider = {
    slider: $("#slider"),
    oneMessageShowingTime: 3000,
    interval: null,
    ajaxJsonHashCode: '',
    /**
     *  run message slider
     */
    run: function () {
        var screenWidth = $(window).width();
        var nbSlides = $('#slider li').length;

        $('#slider li').width(screenWidth);
        var sliderUlWidth = nbSlides * screenWidth;
        $('#slider ul').css({width: sliderUlWidth, marginLeft: -screenWidth});
        $('#slider li:last-child').prependTo('#slider ul');

        setTimeout(function () {
            messageInfoSlider.setFontSize();
        }, 1000);

        clearInterval(messageInfoSlider.interval);
        messageInfoSlider.interval = setInterval(function () {
            messageInfoSlider.moveRight();
        }, messageInfoSlider.oneMessageShowingTime);
    },
    /**
     * Get message from server
     */
    get: function () {
        $.ajax({
            dataType: "json",
            url: messageInfoSlider.slider.data("remote"),
            success: function (data) {
                var dataHashCode = JSON.stringify(data).hashCode();
                if (data.length > 0 && dataHashCode !== messageInfoSlider.ajaxJsonHashCode) {
                    messageInfoSlider.ajaxJsonHashCode = dataHashCode;
                    var items = [];
                    $.each(data, function (i, message) {
                        if (message.image) {
                            items.push('<li class="message-image">'
                                    + '<img src="/upload/images/' + message.image + '"/>'
                                    + "</li>"
                                    );
                        } else {
                            items.push('<li>'
                                    + '<div class="title">' + message.title + '</div>'
                                    + '<div class="content">' + message.content + '</div>'
                                    + "</li>"
                                    );
                        }
                    });
                    messageInfoSlider.slider.html("<ul>" + items.join("") + "</ul>");
                    messageInfoSlider.run();
                }
            },
            /**
             * If error show offline existing message
             */
            error: function () {
                if ($("#slider li").length > 0) {
                    messageInfoSlider.run();
                }
            },
        });
    },
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
        var $body = $('body');
        $('#slider li').each(function (i, slide) {
            var $slide = $(slide);
            $slide.css('font-size', '200px');
            while ($slide.height() > $body.height() - 20) {
                $slide.css('font-size', (parseInt($slide.css('font-size')) - 1) + "px");
            }
        });
    }
};

messageInfoSlider.get();

/**
 * Check for new slides every 5 min
 */
setInterval(function () {
    messageInfoSlider.get();
}, 5000);
//}, 5 * 60 * 1000);