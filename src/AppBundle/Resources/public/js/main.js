$("[type='checkbox']:not('.not-toggle')").each(function () {
    $(this).attr({
        "data-toggle": "toggle",
        "data-onstyle": "success",
        "data-offstyle": "danger",
        "data-size": $(this).data("size") ? $(this).data("size") : 'small',
        "data-on": yes,
        "data-off": no
    })
});

$('#select-all').click(function() {
    var isChecked = $(this).prop("checked");
    $(this).parents().find('table').find('input[type="checkbox"]').prop('checked', isChecked);
});