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
        'filter_place_holder' => 'Texte à filtrer',
        'show_filter_inputs' => false,
    ],
]);
~~~

### Options JavaScript

Ces options sont utilisées pour le widget JavaScript et sont passées en JSON.
Ces options sont à renseigner dans le paramètre `options_js` du formulaire au format *camelize*.

| Nom Symfony             | Nom JavaScript        | Type            | Description                                                              | Défaut                   | Valeurs 
|-------------------------|-----------------------|-----------------|--------------------------------------------------------------------------|--------------------------|-
| filter_text_clear       | filterTextClear       | String          | The text for the "Show All" button                                       | 'voir tous'              |
| filter_place_holder     | filterPlaceHolder     | String          | The placeholder for the input element for filtering elements             | 'Filtrer'                |
| move_selected_label     | moveSelectedLabel     | String          | The label for the "Move Selected" button                                 | 'Déplacer la sélection'  |
| move_all_label          | moveAllLabel          | String          | The label for the "Move All" button                                      | 'Déplacer tous'          |
| remove_selected_label   | removeSelectedLabel   | String          | The label for the "Remove Selected" button                               | 'Supprimer la sélection' |
| remove_all_label        | removeAllLabelText    | String          | The label for the "Remove All" button                                    | 'Supprimer tous'         |
| selected_list_label     | selectedListLabel     | Boolean, String | Can be a string specifying the name of the selected list                 | false                    | true, false, string
| non_selected_list_label | nonSelectedListLabel  | Boolean, String | Can be a string specifying the name of the non selected list             | false                    | true, false, string
| selector_minimal_height | selectorMinimalHeight | Integer         | Represents the minimal height of the generated dual listbox              | 100                      |
| show_filter_inputs      | showFilterInputs      | Boolean         | Whether to show filter input                                             | true                     | true, false
| non_selected_filter     | nonSelectedFilter     | String          | Initializes the dual listbox with a filter for the non selected elements | ''                       |
| selected_filter         | selectedFilter        | String          | Initializes the dual listbox with a filter for the selected elements     | ''                       |
| info_text               | infoText              | String, Boolean | Set this to false to hide this information                               | 'Voir tous {0}'          | false, string
| info_text_filtered      | infoTextFiltered      | String          | Determines which element format to use when some element is filtered     | '<span class="badge badge-warning">Filtré</span> {0} sur {1}' |
| info_text_empty         | infoTextEmpty         | String          | Determines the string to use when there are no options in the list       | 'Liste vide'             |
| filter_on_values        | filterOnValues        | Boolean         | Set this to true to filter the options according to their values         | false                    | true, false
