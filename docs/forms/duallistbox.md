Bootstrap Dual Listbox
================================================================================

**Bootstrap Dual Listbox** est un widget d'une double liste responsive 

Source : https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\DualListBoxChoiceType;
// ...

$builder->add('dual_list', DualListBoxChoiceType::class, [
    'label' => 'DualBox multiple',
    'choices' => ['toto', 'tata', 'titi'],
    'options_js' => [
        'filterPlaceHolder' => 'Texte à filtrer',
        'showFilterInputs' => false,
    ],
]);
~~~

### Options JavaScriptLa liste des options est disponible dans la documentation du widget : https://getdatepicker.com/6/options/

Ces options sont utilisées pour le widget JavaScript et sont passées en JSON.
Ces options sont à renseigner dans le paramètre `options_js` du formulaire.

| Nom JavaScript        | Type            | Description                                                              | Défaut                   | Valeurs 
|-----------------------|-----------------|--------------------------------------------------------------------------|--------------------------|-
| filterTextClear       | String          | The text for the "Show All" button                                       | 'voir tous'              |
| filterPlaceHolder     | String          | The placeholder for the input element for filtering elements             | 'Filtrer'                |
| moveSelectedLabel     | String          | The label for the "Move Selected" button                                 | 'Déplacer la sélection'  |
| moveAllLabel          | String          | The label for the "Move All" button                                      | 'Déplacer tous'          |
| removeSelectedLabel   | String          | The label for the "Remove Selected" button                               | 'Supprimer la sélection' |
| removeAllLabelText    | String          | The label for the "Remove All" button                                    | 'Supprimer tous'         |
| selectedListLabel     | Boolean, String | Can be a string specifying the name of the selected list                 | false                    | true, false, string
| nonSelectedListLabel  | Boolean, String | Can be a string specifying the name of the non selected list             | false                    | true, false, string
| selectorMinimalHeight | Integer         | Represents the minimal height of the generated dual listbox              | 100                      |
| showFilterInputs      | Boolean         | Whether to show filter input                                             | true                     | true, false
| nonSelectedFilter     | String          | Initializes the dual listbox with a filter for the non selected elements | ''                       |
| selectedFilter        | String          | Initializes the dual listbox with a filter for the selected elements     | ''                       |
| infoText              | String, Boolean | Set this to false to hide this information                               | 'Voir tous {0}'          | false, string
| infoTextFiltered      | String          | Determines which element format to use when some element is filtered     | '<span class="badge badge-warning">Filtré</span> {0} sur {1}' |
| infoTextEmpty         | String          | Determines the string to use when there are no options in the list       | 'Liste vide'             |
| filterOnValues        | Boolean         | Set this to true to filter the options according to their values         | false                    | true, false

La liste des options est disponible dans la documentation du widget : https://www.virtuosoft.eu/code/bootstrap-duallistbox/
