/** Получение html содержимого вместе с собсвенным html */
jQuery.fn.outerHtml = function(s) {
    return s ? this.before(s).remove() : jQuery("<p>").append(this.eq(0).clone()).html();
};

/** Есть ли атрибут */
jQuery.fn.hasAttr = function(name) {
   return this.attr(name) !== undefined;
};