/**
 * Fonctions de base et d'initialisation
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */

import $ from "jquery";

// Import des modules pour les widgets des formulaires
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
    },

    finalize() {
        // JavaScript to be fired on the home page, after the init JS
        if (typeof olixDataTables !== "undefined") {
            $("#olixDataTables")
                .initDataTables(olixDataTables, {
                    stateLoadCallback: function (settings) {
                        let state = localStorage.getItem(
                            "DataTables_" +
                                settings.sInstance +
                                "_" +
                                window.location.pathname
                        );
                        if (state) return JSON.parse(state);
                        else return null;
                    },
                    stateSaveCallback: function (settings, data) {
                        localStorage.setItem(
                            "DataTables_" +
                                settings.sInstance +
                                "_" +
                                window.location.pathname,
                            JSON.stringify(data)
                        );
                    },

                    initComplete: function () {
                        var api = this.api();

                        // For each column
                        api.columns()
                            .eq(0)
                            .each(function (colIdx) {
                                // On every keypress in this input
                                $("select[tabindex=" + colIdx + "]").on(
                                    "change",
                                    function (e) {
                                        api.column(colIdx)
                                            .search(
                                                this.value != ""
                                                    ? this.value
                                                    : "",
                                                this.value != "",
                                                this.value == ""
                                            )
                                            .draw();
                                    }
                                );

                                // $("input", $(".filters th").eq($(api.column(colIdx).header()).index()))
                                $("input[tabindex=" + colIdx + "]")
                                    .off("keyup change")
                                    .on("change", function (e) {
                                        api.column(colIdx)
                                            .search(
                                                this.value != ""
                                                    ? this.value
                                                    : "",
                                                this.value != "",
                                                this.value == ""
                                            )
                                            .draw();
                                    })
                                    .on("keyup", function (e) {
                                        e.stopPropagation();

                                        $(this).trigger("change");
                                        $(this)
                                            .focus()[0]
                                            .setSelectionRange(
                                                cursorPosition,
                                                cursorPosition
                                            );
                                    });
                            });
                    },
                })
                .then(function (dt) {
                    // dt contains the initialized instance of DataTables
                    dt.on("draw", function () {
                        console.log("Redrawing table");
                    });
                });
            $("#olixDataTables").on(
                "stateLoaded.dt",
                function (e, settings, data) {
                    let isFilter = false;
                    var api = new $.fn.dataTable.Api(settings);
                    api.draw();
                    // Au chargement des données, on rempli le formulaire de filtre
                    $(".card-filter :input").each(function (params) {
                        let colIdx = $(this).attr("tabindex");
                        if (data.columns[colIdx].search.search) {
                            this.value = data.columns[colIdx].search.search;
                            isFilter = true;
                        }
                        if (isFilter) {
                            $("#collapseFilter").collapse();
                        }
                    });
                }
            );
        }
    },
};
