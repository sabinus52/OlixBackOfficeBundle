/**
 * Plugin jQuery pour la prise en charge du formulaire de type collection
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 */

import $ from "jquery";

/**
 * Constants
 */
const NAME = "OlixCollection";
const DATA_KEY = "olix.collection";
const JQUERY_NO_CONFLICT = $.fn[NAME];
const SELECTOR_TRIGGER = `.collection-widget`;

const Default = {
    params: {},
    allow_add: true,
    allow_delete: true,
    onAddItem: undefined,
    onDeleteItem: undefined,
};

class OlixCollection {
    /**
     * Constructeur
     * @param {Element} element
     * @param {Object} settings
     */
    constructor(element, settings) {
        this._element = element;
        this._settings = $.extend({}, Default, settings);

        this._nextId = 0;
    }

    /**
     * Ajout d'un élément à la collection
     */
    addItem() {
        // Récupère l'élément ayant l'attribut data-prototype
        var newItem = this._element.data("prototype");
        // Remplace '__name__' dans le HTML du prototype par un nombre basé sur la longueur de la collection courante
        newItem = newItem.replace(/__name__/g, this._nextId);
        var $newItem = $(newItem);

        // Ajoute l'élément
        this._element.append($newItem);

        // Incrémente l'index des éléments de la collection
        this._nextId++;
        $newItem.find(":input").first().focus();

        if (this._settings.onAddItem !== undefined) {
            console.log("onAddItem");
            this._settings.onAddItem($newItem, $newItem.find(":input").first());
        }
    }

    /**
     * Suppression d'un élément de la collection
     *
     * @param {Element} $elt Élément du bouton 'delete' sélectionné
     */
    deleteItem($elt) {
        if (confirm("Veux tu enlever cet élément ?")) {
            $elt.closest(".collection-item").fadeOut(500, function () {
                $(this).remove();
            });
            if (this._settings.onDeleteItem !== undefined) {
                console.log("onDeleteItem");
                this._settings.onDeleteItem();
            }
        }
    }

    /**
     * Initialisation du plugin
     */
    _init() {
        // Prochain Index de la collection
        this._nextId = this._element.children("div").length;
        console.log("NextID = " + this._nextId);

        // Évènement du bouton 'ADD'
        if (this._settings.allow_add) {
            this._element
                .parent()
                .on("click", ".collection-btn-add", this, function (e) {
                    e.preventDefault();
                    e.data.addItem();
                });
        }

        // Évènement sur les boutons 'DELETE'
        if (this._settings.allow_delete) {
            this._element.on(
                "click",
                ".collection-btn-delete",
                this,
                function (e) {
                    e.preventDefault();
                    e.data.deleteItem($(this));
                }
            );
        }
    }

    /**
     * Interface JQUERY
     *
     * @param {Object} config
     * @returns
     */
    static _jQueryInterface(config) {
        return this.each(function () {
            let data = $(this).data(DATA_KEY);
            const _config = $.extend(
                {},
                Default,
                $(this).data(),
                typeof config === "object" ? config : {}
            );

            if (!data) {
                data = new OlixCollection($(this), _config);
                $(this).data(DATA_KEY, data);
                data._init();
            } else if (typeof config === "string") {
                if (typeof data[config] === "undefined") {
                    throw new TypeError(`No method named "${config}"`);
                }

                data[config]();
            } else if (typeof config === "undefined") {
                data._init();
            }
        });
    }
}

/**
 * Data selector API
 */
$(() => {
    $(SELECTOR_TRIGGER).each(function () {
        OlixCollection._jQueryInterface.call($(this));
    });
});

/**
 * jQuery API
 */
$.fn[NAME] = OlixCollection._jQueryInterface;
$.fn[NAME].Constructor = OlixCollection;
$.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT;
    return OlixCollection._jQueryInterface;
};

export default OlixCollection;
