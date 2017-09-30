/**
 * handle weather
 */
var weather = {
    /**
     * get and display temperature
     */
    getTemperature: function () {
        $(".temperature").hide();
        $.ajax({
            url: "temperature",
            success: function (resp) {
                if (resp != "") {
                    if (parseInt(resp) > 15 && parseInt(resp) < 25) {
                        $(".temperature").addClass("orange");
                    } else if (parseInt(resp) >= 25) {
                        $(".temperature").addClass("red");
                    }
                    $(".temperature span").text(resp);
                    $(".temperature").show();
                }
            },
            error: function (resp) {
                $(".temperature").hide();
            }
        });
    },
    initUpdateTemperature: function () {
        weather.getTemperature();
        setInterval(function () {
            weather.getTemperature();
        }, prayer.oneMinute * 60);
    }
};
