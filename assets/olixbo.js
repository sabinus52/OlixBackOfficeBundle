
import "admin-lte/plugins/bootstrap/js/bootstrap.bundle";
import "admin-lte";
import "bootstrap-switch";
import "admin-lte/plugins/select2/js/select2.full";
import moment from 'moment';
window.moment = moment;
require("tempusdominus-bootstrap-4");
import "bootstrap4-duallistbox";


import "./scripts/modal.js";
import "./scripts/toastr.js";

/**
 * Formulaires
 */



export default {

    init() {
        // Initialisation des widgets Bootstrap-Switch
        $.each($("[data-toggle='switch']"), function (i, element) {
            $(element).bootstrapSwitch($(element).data("options-js"));
        });

        // Initialisation des widgets DateTimePicker
        $.each($("[data-toggle='datetimepicker2']"), function (i, element) {
            $(element).datetimepicker($(element).data("options-js"));
        });

        // Initialisation des widgets DualListBox
        $.each($("[data-toggle='duallistbox']"), function (i, element) {
            $(element).bootstrapDualListbox($(element).data("options-js"));
        });
    },

    finalize() {
        // JavaScript to be fired on the home page, after the init JS

    },
};
