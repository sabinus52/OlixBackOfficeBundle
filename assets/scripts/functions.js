/**
 * Fonctions de base et d'initialisation
 * 
 * @author Sabinus52 <sabinus52@gmail.com>
 */
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
                .querySelector(".select2-container--open .select2-search__field")
                .focus();
        });

        // Initialisation des widgets DateTimePicker
        $.each($("[data-toggle='datetimepicker2']"), function (i, element) {
            $(element).datetimepicker($(element).data("options-js"));
        });

        // Initialisation des widgets DualListBox
        $.each($("[data-toggle='duallistbox']"), function (i, element) {
            $(element).bootstrapDualListbox($(element).data("options-js"));
        });
    },

    finalize() {
        // JavaScript to be fired on the home page, after the init JS
        if (typeof olixDataTables !== 'undefined') {
            $('#olixDataTables').initDataTables(olixDataTables);
        }
    },
};