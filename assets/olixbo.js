/**
 * Import des modules
 */
import "admin-lte/plugins/bootstrap/js/bootstrap.bundle";
import "admin-lte";
import "bootstrap-switch";
import "admin-lte/plugins/select2/js/select2.full";
import moment from 'moment';
window.moment = moment;
require("tempusdominus-bootstrap-4");
import "bootstrap4-duallistbox";
import "datatables.net";
import "datatables.net-bs4";
import "datatables.net-responsive";
import "datatables.net-responsive-bs4";
import "../../public/bundles/datatables/js/datatables.js";

/**
 * Import des modules perso
 */
import "./scripts/modal.js";
import "./scripts/toastr.js";
import "./scripts/select2.js";
import "./scripts/collection.js";
import Olix from "./scripts/functions.js";

/**
 * Initialisation
 */
Olix.initForms();
Olix.finalize();
