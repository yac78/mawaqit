/**
 * handle weather
 */
var weather = {
    /**
     * get and display weather
     */
    getWeather: function () {
        $weatherEl = $("#weather");
        $.ajax({
            url: $weatherEl.data("remote"),
            success: function (resp) {
                if (resp) {
                    $weatherEl.removeAttr("class");
                    if (parseInt(resp.temperature) <= 0) {
                        $weatherEl.addClass("blue");
                    }
                    if (parseInt(resp.temperature) > 10 && parseInt(resp.temperature) <= 25) {
                        $weatherEl.addClass("orange");
                    }
                    if (parseInt(resp.temperature) > 25) {
                        $weatherEl.addClass("red");
                    }
                    $weatherEl.find("i").attr('class', 'wi wi-' + resp.icon);
                    $weatherEl.find("span").text(resp.temperature);
                }
            },
            error: function () {
                $weatherEl.addClass("hidden");
            }
        });
    },
    initUpdateWeather: function () {
        if (prayer.confData.temperatureEnabled === true) {
            weather.getWeather();
            setInterval(function () {
                weather.getWeather();
            }, prayer.oneMinute * 20);
        }
    }
};
