/**
 * Module FOSJSRouting
 *
 * Pour mette à jour les chemins exposé :
 *    sf fos:js-routing:dump --format=json --target=config/routes/fos_js_routes.json
 */

const routes = require("/config/routes/fos_js_routes.json"); // sf fos:js-routing:dump --format=json --target=config/routes/fos_js_routes.json
const Routing = require("/public/bundles/fosjsrouting/js/router"); // do not forget to dump your assets `symfony console assets:install --symlink public`

Routing.setRoutingData(routes);

module.exports = Routing;
