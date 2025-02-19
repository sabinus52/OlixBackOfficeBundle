/**
 * Import des styles
 */
import "@fortawesome/fontawesome-free/css/all.min.css";
import "toastr/build/toastr.min.css";
import "icheck-bootstrap/icheck-bootstrap.min.css";
import "select2/dist/css/select2.min.css";
import "@eonasdan/tempus-dominus/dist/css/tempus-dominus.min.css";
import "bootstrap4-duallistbox/dist/bootstrap-duallistbox.min.css";
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import "datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css";
import "admin-lte/dist/css/adminlte.min.css";
import "./styles/switch.css";

/**
 * Import des modules
 */
import jQuery from "jquery";
import * as Popper from "@popperjs/core";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "admin-lte";
import "datatables.net";
import "datatables.net-bs4";
import "datatables.net-responsive";
import "datatables.net-responsive-bs4";

/**
 * Import des modules perso
 */
import OlixModal from "./plugins/modal.js";
import DualListBox from "./plugins/duallistbox.js";
import DateTimePicker from "./plugins/datetimepicker.js";
import Select2 from "./plugins/select2.js";
import "./plugins/collection.js";
import "./plugins/datatables.js";
import "./plugins/toastr.js";

/**
 * Initialisation
 */
// Initialisation des modules globales
window.$ = jQuery;
window.jQuery = jQuery;
window.Popper = Popper;
// Initialisation des widgets des formulaires
DualListBox.initialize();
DateTimePicker.initialize();
Select2.initialize();
// Initialisation des fenêtres modales
OlixModal.initialize({
    onLoaded: function () {
        console.log("olix-modal:loaded2");
        DateTimePicker.initialize();
        DualListBox.initialize();
        Select2.initialize();
    },
});
