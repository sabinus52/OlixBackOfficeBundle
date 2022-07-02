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
    });

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

})();



/**
 * Gestion de l'interface de l'admin
 *
 * @namespace olixAdminInterface
 */
 var olixBackOffice = {

    /**
     * Lors d'une Datatable, affiche le modal de confirmation d'une suppression d'un élément
     * 
     * @param Object obj Objet du lien
     */
    confirmDelete: function (obj)
    {
        $('#modalDelete form').attr('action', $(obj).attr('href'));
        $('#modalDelete').modal('show');
        return false;
    }

};