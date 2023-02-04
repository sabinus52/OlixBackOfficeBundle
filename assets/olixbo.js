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

/**
 * Import des modules perso
 */
import "./scripts/modal.js";
import "./scripts/toastr.js";
import "./scripts/select2.js";
import Olix from "./scripts/functions.js";

/**
 * Initialisation
 */
Olix.initForms();
Olix.finalize();
