$("[type='checkbox']:not('.not-toggle')").attr({
    "data-toggle": "toggle",
    "data-onstyle": "success",
    "data-offstyle": "danger",
    "data-size": "small",
    "data-on": yes,
    "data-off": no
});

$(".help").click(function () {
    var $title = $(this).find("div");
    if (!$title.length) {
        $(this).append('<div>' + $(this).attr("title") + '<span>X</span></div>');
    } else {
        $title.remove();
    }
});