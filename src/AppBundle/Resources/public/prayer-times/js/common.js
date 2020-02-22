var flashMessage = {
    show: function () {
        if ($("footer .textSlide").length > 0) {
            $("footer .textSlide").removeClass("hidden");
            $("footer .info").addClass("hidden");
        }
    },
    hide: function () {
        $("footer .textSlide").addClass("hidden");
        $("footer .info").removeClass("hidden");
    },
};