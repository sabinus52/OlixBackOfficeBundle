/**
 * Plugin de chargement des notifications 'Toastr'
 *
 * @link https://www.npmjs.com/package/toastr
 * @version 2.1
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */

import "toastr/build/toastr.min.css";
import toastr from "toastr";
import $ from "jquery";

const SELECTOR_TRIGGER = "[role='toastr']";

toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-bottom-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "10000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

$(SELECTOR_TRIGGER).each(function () {
    var type = $(this).data("alert");
    var message = $(this).html();
    if (type == undefined) {
        type = "info";
    }
    // Affiche la notification
    toastr[type](message);
});
