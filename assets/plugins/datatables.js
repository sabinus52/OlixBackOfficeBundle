/**
 * Plugin de chargement des DataTables
 *
 * @link https://www.npmjs.com/package/datatables.net
 * @version 1.13
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */

import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import "datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css";
import "datatables.net";
import "datatables.net-bs4";
import "datatables.net-responsive";
import "datatables.net-responsive-bs4";

import "./dt.bundle.js";
import $ from "jquery";

$.fn.initDataTables.defaults = {
    language: {
        decimal: "",
        emptyTable: "No data available in table",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
        infoPostFix: "",
        thousands: ",",
        lengthMenu: "Show _MENU_ entries",
        loadingRecords: "Loading...",
        processing: "",
        search: "Rechercher:",
        zeroRecords: "No matching records found",
        paginate: {
            first: "First",
            last: "Last",
            next: "Next",
            previous: "Previous",
        },
        aria: {
            orderable: "Order by this column",
            orderableReverse: "Reverse order this column",
        },
    },
};

if (typeof olixDataTables !== "undefined") {
    $("#olixDataTables")
        .initDataTables(olixDataTables, {
            language: {
                decimal: "",
                emptyTable: "Pas de données disponibles dans la table",
                info: "_START_ à _END_ sur _TOTAL_ entrées",
                infoEmpty: "0 à 0 sur 0 entrées",
                infoFiltered: "(filtré sur un total de _MAX_ entrées)",
                infoPostFix: "",
                thousands: ",",
                lengthMenu: "Voir _MENU_ entrées",
                loadingRecords: "Chargement des données ...",
                processing: "",
                search: "Rechercher:",
                zeroRecords: "Pas de données disponibles",
                paginate: {
                    first: "Premier",
                    last: "Dernier",
                    next: "Suivant",
                    previous: "Précédent",
                },
                aria: {
                    orderable: "Order by this column",
                    orderableReverse: "Reverse order this column",
                },
            },
            stateLoadCallback: function (settings) {
                let state = localStorage.getItem(
                    "DataTables_" +
                        settings.sInstance +
                        "_" +
                        window.location.pathname
                );
                console.log(state);
                if (state) return JSON.parse(state);
                else return null;
            },
            stateSaveCallback: function (settings, data) {
                console.log(data);
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
                                        this.value != "" ? this.value : "",
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
                                        this.value != "" ? this.value : "",
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
    $("#olixDataTables").on("stateLoaded.dt", function (e, settings, data) {
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
    });
}
