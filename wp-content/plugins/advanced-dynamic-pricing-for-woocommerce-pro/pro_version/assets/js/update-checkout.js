!function (a) {
    "use strict";
    a(function () {
        a(document.body).on("change", 'input[name="payment_method"]', function () {
            a("body").trigger("update_checkout")
        })
    })
}(jQuery);