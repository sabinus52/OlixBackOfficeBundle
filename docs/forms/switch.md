Case à cocher au format "toggle switch"
================================================================================

Transforme une checkbox en **toggle switch**

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\SwitchType;
// ...

$builder->add('public', SwitchType::class, [
    'label'    => 'Show this entry publicly?',
    'on_color' => 'success',
    'off_color' => 'danger',
    'size' => 'small',
]);
~~~

### Options

| Nom Symfony   | Type    |	Description                                      | Défaut | Valeurs 
|---------------|---------|--------------------------------------------------|--------|----------------------------------------
| on_color      | String  | Couleur du widget switch dans l'état checked     | null   | Liste de COLORS_SIMPLIFY
| off_color     | String  | Couleur du widget switch dans l'état non checked | null   | Liste de COLORS_SIMPLIFY
| size          | String  | Dimension du widget                              | null   | `small`, `large`
| chk_label     | String  | Label à droite du widget                         | ''     |

Liste des couleurs disponibles : `primary`,  `secondary`, `success`,  `info`,  `warning`,  `danger`, `light`, `dark`, `indigo`

Le paramètre `chk_label` n'est disponible que pour les formulaires au format horizontal.
