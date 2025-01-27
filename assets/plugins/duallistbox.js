/**
 * Plugin de chargement des widgets DualListBox
 *
 * @link https://www.npmjs.com/package/bootstrap4-duallistbox
 * @version 4.0.2
 *
 * @author Sabinus52 <sabinus52@gmail.com>
 */

import "bootstrap4-duallistbox/dist/bootstrap-duallistbox.min.css";
import $ from "jquery";
import BootstrapDualListbox from "bootstrap4-duallistbox";
BootstrapDualListbox($);

const DATA_OPTS_KEY = "options-js";
const SELECTOR_TRIGGER = "[data-toggle='duallistbox']";

const DefaultOptions = {
    filterTextClear: "voir tous",
    filterPlaceHolder: "Filtrer",
    moveSelectedLabel: "Déplacer la sélection",
    moveAllLabel: "Déplacer tous",
    removeSelectedLabel: "Supprimer la sélection",
    removeAllLabel: "Supprimer tous",
    selectedListLabel: false,
    nonSelectedListLabel: false,
    selectorMinimalHeight: 100,
    showFilterInputs: true,
    nonSelectedFilter: "",
    selectedFilter: "",
    infoText: "Voir tous {0}",
    infoTextFiltered:
        '<span class="badge badge-warning">Filtré</span> {0} sur {1}',
    infoTextEmpty: "Liste vide",
    filterOnValues: false,
};

/**
 * Surcharge de la fonction DualListBox pour ajouter des options
 *
 * @param {JSON} options
 * @returns
 */
$.fn.OlixDualListbox = function (options) {
    this.each(function () {
        let $elt = $(this);
        let optionsResult;

        // Options de base + surcharge des options
        optionsResult = $.extend(
            DefaultOptions,
            $elt.data(DATA_OPTS_KEY),
            options || {}
        );

        $elt.bootstrapDualListbox(optionsResult);
    });

    return this;
};

export default {
    /**
     * Initialisation des widgets DualListBox
     *
     * @param {JSON} options
     */
    initialize: function (options) {
        $(SELECTOR_TRIGGER).OlixDualListbox(options);
    },
};
