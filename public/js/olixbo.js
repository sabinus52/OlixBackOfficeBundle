(function()
{

    /**
     * Event sur ouverture du modal pour charger une page distante
     */
    $('.modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this); 
        if (button.data("remote")) {
            modal.find('.modal-body').load(button.data("remote"));
        }
    });


    /**
     * Event su click bouton "Delete"
     */
    $('.olix-actions .delete').on('click', function (event) {
        if (confirm('Confirmer la suppression')) {
            return true;
        }
        return false;
    })


    /**
     * Chargement des éléments de type "Bootstrap-Switch"
     */
    $('input[data-toggle="switch"]').each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

})();
