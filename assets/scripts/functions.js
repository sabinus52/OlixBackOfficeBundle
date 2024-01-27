/**
 * Fonctions de base et d'initialisation
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */

import $ from "jquery";

// Import des modules pour les widgets des formulaires
import BootstrapDualListbox from "bootstrap4-duallistbox";
BootstrapDualListbox($);
import "bootstrap-switch";
import * as Popper from "@popperjs/core";
window.Popper = Popper;
import { TempusDominus } from "@eonasdan/tempus-dominus";

const displayDatePicker = {
    type: "icons",
    time: "fas fa-clock",
    date: "fas fa-calendar",
    up: "fas fa-arrow-up",
    down: "fas fa-arrow-down",
    previous: "fas fa-chevron-left",
    next: "fas fa-chevron-right",
    today: "fas fa-calendar-check",
    clear: "fas fa-trash",
    close: "fas fa-xmark",
};

export default {
    initForms() {
        // Initialisation des widgets Bootstrap-Switch
        $.each($("[data-toggle='switch']"), function (i, element) {
            $(element).bootstrapSwitch($(element).data("options-js"));
        });

        // Initialisation des widgets Select2
        $("[data-toggle='select2']").OlixSelect2();
        $(document).on("select2:open", () => {
            document
                .querySelector(
                    ".select2-container--open .select2-search__field"
                )
                .focus();
        });

        // Initialisation des widgets DateTimePicker
        $.each($("[data-toggle='datetimepicker2']"), function (i, element) {
            let opts = $(element).data("options-js");
            $.extend(true, opts, { display: { icons: displayDatePicker } });
            console.log(opts);
            new TempusDominus(element, opts);
        });

        // Initialisation des widgets DualListBox
        $.each($("[data-toggle='duallistbox']"), function (i, element) {
            $(element).bootstrapDualListbox($(element).data("options-js"));
        });
    },

    finalize() {
        // JavaScript to be fired on the home page, after the init JS
        if (typeof olixDataTables !== "undefined") {
            $("#olixDataTables").initDataTables(olixDataTables);
        }
    },
};
