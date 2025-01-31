/**
 * Fonction de surcharge d'initialisation des objets "Select2"
 *
 * @link https://www.npmjs.com/package/select2
 * @version 4
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */

import "select2/dist/css/select2.min.css";
import $ from "jquery";
import Select2 from "select2/dist/js/select2.full.js";
Select2($);

const DATA_OPTS_KEY = "options-js";
const SELECTOR_TRIGGER = "[data-toggle='select2']";

const DefaultOptions = {
    minimumInputLength: 2,
};

/**
 * Surcharge de la fonction Select2 pour ajouter des options
 *
 * @param {JSON} options
 * @returns
 */
$.fn.OlixSelect2 = function (options) {
    this.each(function () {
        let $elt = $(this);
        let optionsResult;

        // Options de base
        optionsResult = $.extend(
            true,
            {},
            DefaultOptions,
            $elt.data(DATA_OPTS_KEY),
            options || {}
        );

        // Options pour l'auto completion en AJAX
        let optionsAjax = {};
        if ($elt.data("ajax")) {
            let opts = $elt.data("ajax");
            optionsAjax = {
                createTag: function (data) {
                    if ($elt.data("prefix-new") && data.term.length > 0) {
                        return {
                            id: $elt.data("prefix-new") + data.term,
                            text: data.term,
                        };
                    }
                },
                ajax: {
                    url: opts.url,
                    dataType: "json",
                    delay: opts.delay,
                    cache: opts.cache,
                    data: function (params) {
                        let parameter = {};
                        parameter["term"] = params.term;
                        if (opts.scroll) {
                            parameter["page"] = params.page || 1;
                        }
                        return parameter;
                    },
                    processResults: function (data, params) {
                        let results,
                            more = false,
                            response = {};
                        params.page = params.page || 1;
                        if (Array.isArray(data)) {
                            results = data;
                        } else if (typeof data === "object") {
                            results = data.results;
                            more = data.more;
                        } else {
                            results = [];
                        }
                        response.results = results;
                        if (opts.scroll) {
                            response.pagination = { more: more };
                        }

                        return response;
                    },
                },
            };
        }

        optionsResult = $.extend(optionsResult, optionsAjax);

        $elt.select2(optionsResult);
    });

    return this;
};

export default {
    /**
     * Initialisation des widgets Select2
     *
     * @param {JSON} options
     */
    initialize: function (options) {
        $(SELECTOR_TRIGGER).OlixSelect2(options);
        $(document).on("select2:open", () => {
            document
                .querySelector(
                    ".select2-container--open .select2-search__field"
                )
                .focus();
        });
    },
};
