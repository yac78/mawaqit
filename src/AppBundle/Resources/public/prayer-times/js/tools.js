/* ##### string ##### */
String.prototype.firstCapitalize = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

/**
 * check if string match time hh:mm
 * @returns {Boolean}
 */
String.prototype.matchTime = function () {
    var regex = /^\d{2}:\d{2}$/g;
    return regex.test(this);
};


/* ##### time ##### */
Date.prototype.stdTimezoneOffset = function () {
    var jan = new Date(this.getFullYear(), 0, 1);
    var jul = new Date(this.getFullYear(), 6, 1);
    return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
}

Date.prototype.dst = function () {
    return this.getTimezoneOffset() < this.stdTimezoneOffset();
}


/* ##### others ##### */
/**
 * add zero to number if < to 10, ex : 1 becomes 01
 * @param {integer} value
 * @returns {String}
 */
function addZero(value) {
    if (value < 10) {
        value = '0' + value;
    }
    return value;
}

/**
 * Reload page if internet connection is available
 */
function reloadIfConnected() {
    $.ajax({
        url: window.location.href,
        success: function () {
            location.reload();
        }
    });
}
