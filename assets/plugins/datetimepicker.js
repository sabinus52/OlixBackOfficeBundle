/**
 * Plugin de chargement des widgets DateTimePicker
 *
 * @link https://www.npmjs.com/package/@eonasdan/tempus-dominus
 * @version 6.9
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */
import "@eonasdan/tempus-dominus/dist/css/tempus-dominus.min.css";
import { TempusDominus } from "@eonasdan/tempus-dominus";

const DATA_OPTS_KEY = "options-js";
const SELECTOR_TRIGGER = "[data-toggle='datetimepicker2']";

const DefaultOptions = {
    stepping: 5,
    useCurrent: false,
    display: {
        icons: {
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
        },
    },
};

export default {
    /**
     * Initialisation des widgets DateTimePicker
     *
     * @param {JSON} options
     */
    initialize: function (options) {
        $.each($(SELECTOR_TRIGGER), function (i, element) {
            let optionsWidget = $(element).data(DATA_OPTS_KEY);
            let optionsResult = $.extend(
                true,
                {},
                DefaultOptions,
                optionsWidget,
                options || {}
            );
            //$.extend(true, opts, options);
            console.log(optionsWidget, optionsResult);
            new TempusDominus(element, optionsResult);
        });
    },
};
