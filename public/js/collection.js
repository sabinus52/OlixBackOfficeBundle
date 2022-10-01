/**
 * Plugin jQuery pour la prise en charge du formulaire de type collection
 * 
 * @author Olivier <sabinus52@gmail.com>
 * 
 * @package Olix
 * @subpackage FormsExtBootstrapBundle
 */

;(function ($, window, undefined) {

    $.olixCollection = function(element, options) {
        
        var defaults = {
            allow_add: true,
            allow_delete: true,
            onAddItem: undefined,
            onDeleteItem: undefined,
        };
        
        plugin = this;
        
        this.options = $.extend({}, defaults, options);
        
        this.$element = $(element);
        this.element = element;
        
        this.init();
    };



    $.olixCollection.prototype = {
    
        /**
         * Initialisation du plugin
         */
        init : function() {

            // Prochain Index de la collection
            this.nextId = this.$element.children('div').length;
            console.log('NextID = '+this.nextId);
            
            // Evènement du bouton 'ADD'
            if (this.options.allow_add) {
                this.$element.parent().on('click', '.collection-btn-add', this, function(e) {
                    e.preventDefault();
                    e.data.addItem();
                });
            }
            
            // Evènement sur les boutons 'DELETE'
            if (this.options.allow_delete) {
                this.$element.on('click', '.collection-btn-delete', this, function (e) {
                    e.preventDefault();
                    e.data.deleteItem($(this));
                })
            }
        },
        
        
        /**
         * Ajout d'un élément à la collection
         */
        addItem: function() {

            // Récupère l'élément ayant l'attribut data-prototype
            var newItem = this.$element.data('prototype');
            // Remplace '__name__' dans le HTML du prototype par un nombre basé sur la longueur de la collection courante
            newItem = newItem.replace(/__name__/g, this.nextId);
            $newItem = $(newItem);
            
            // Ajoute l'élément
            this.$element.append($newItem);
            
            // Incrémente l'index des éléments de la collection
            this.nextId++;
            $newItem.find(':input').first().focus();

            if (this.options.onAddItem !== undefined) {
                console.log('onAddItem');
                this.options.onAddItem($newItem, $newItem.find(':input').first());
            }
        },
        
        
        /**
         * Suppression d'un élément de la collection
         * 
         * @param $elt :Elément du bouton 'delete' selectionné
         */
        deleteItem: function($elt) {
            if (confirm('Veux tu enlever cet élément ?')) {
                $elt.closest('.collection-item').fadeOut(500, function () {
                    $(this).remove();
                });
                if (this.options.onDeleteItem !== undefined) {
                    console.log('onDeleteItem');
                    this.options.onDeleteItem();
                }
            }
        },
    
    }



    $.fn.olixCollection = function (options) {
        
        // Initialisation avec ou sans options
        if (options === undefined || typeof options === 'object') {
            return this.each(function() {
                // Si le plugin n'a pas été assigné
                if ( $(this).data('olixCollection') == undefined ) {
                    // on créé un instance
                    var plugin = new $.olixCollection(this, options);
                    // on stocke la référence du plugin
                    $(this).data('olixCollection', plugin);
                }
            });
        }

        return this;
        
    }

}(jQuery, window));

module.exports = olixCollection;