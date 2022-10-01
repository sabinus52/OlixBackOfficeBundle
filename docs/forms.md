# Formulaires étendus

Pour les options avec couleurs, la liste est la suivante :
~~~
'primary', 'blue', 'secondary', 'success', 'green', 'info', 'cyan', 'warning', 'yellow', 'danger', 'red', 'black', 'gray-dark', 'gray', 'light', 'default', 'indigo', 'navy', 'purple', 'fuchsia', 'pink', 'maroon', 'orange', 'lime', 'teal', 'olive'
~~~

## BootstrapSwitch

Transforme une checkbox en "toggle switch"

Source : https://github.com/Bttstrp/bootstrap-switch

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\SwitchType;
// ...

$builder->add('public', SwitchType::class, [
    'label'    => 'Show this entry publicly?',
    'ojs_size' => 'mini',
    'ojs_on_text' => 'OUI',
    'ojs_off_text' => 'NON',
]);
~~~

### Options

| Nom SF            | Nom JS        | Type    |	Description                             | Defaut    | Valeurs 
|-------------------|---------------|---------|-----------------------------------------|-----------|----------------------------------------
| ojs_size          | size          | String  | The checkbox size                       | null      | null, 'mini', 'small', 'normal', 'large'
| ojs_indeterminate | indeterminate | Boolean | Indeterminate state                     | false     | true, false
| obj_inverse       | inverse       | Boolean | Inverse switch direction                | false     | true, false
| ojs_on_color      | onColor       | String  | Color of the left side of the switch    | 'primary' | List of COLORS
| ojs_off_color     | offColor      | String  | Color of the right side of the switch   | 'default' | List of COLORS
| ojs_on_text       | onText        | String  | Text of the left side of the switch     | 'OUI'     |
| ojs_off_text      | offText       | String  | Text of the right side of the switch    | 'NON'     |
| ojs_label_text    | labelText     | String  | Text of the center handle of the switch	| '\&nbsp;' |



## Bootstrap Dual Listbox

Bootstrap Dual Listbox est un widget d'une double liste responsive 

Source : https://github.com/istvan-ujjmeszaros/bootstrap-duallistbox

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\DualListBoxChoiceType;
// ...

$builder->add('duallist', DualListBoxChoiceType::class, [
    'label' => 'DualBox multiple',
    'choices' => ['toto', 'tata', 'titi'],
    'ojs_show_filter_inputs' => false,
]);
~~~

### Options

| Nom SF                      | Nom JS                | Type            | Description                                                              | Defaut                   | Valeurs 
|-----------------------------|-----------------------|-----------------|--------------------------------------------------------------------------|--------------------------|-
| ojs_filter_text_clear       | filterTextClear       | String          | The text for the "Show All" button                                       | 'voir tous'              |
| ojs_filter_place_holder     | filterPlaceHolder     | String          | The placeholder for the input element for filtering elements             | 'Filtrer'                |
| ojs_move_selected_label     | moveSelectedLabel     | String          | The label for the "Move Selected" button                                 | 'Déplacer la sélection'  |
| ojs_move_all_label          | moveAllLabel          | String          | The label for the "Move All" button                                      | 'Déplacer tous'          |
| ojs_remove_selected_label   | removeSelectedLabel   | String          | The label for the "Remove Selected" button                               | 'Supprimer la sélection' |
| ojs_remove_all_label        | removeAllLabelnText   | String          | The label for the "Remove All" button                                    | 'Supprimer tous'         |
| ojs_selected_list_label     | selectedListLabel     | Boolean, String | Can be a string specifying the name of the selected list                 | false                    | true, false, string
| ojs_non_selected_list_label | nonSelectedListLabel  | Boolean, String | Can be a string specifying the name of the non selected list             | false                    | true, false, string
| ojs_selector_minimal_height | selectorMinimalHeight | Integer         | Represents the minimal height of the generated dual listbox              | 100                      |
| ojs_show_filter_inputs      | showFilterInputs      | Boolean         | Whether to show filter input                                             | true                     | true, false
| ojs_non_selected_filter     | nonSelectedFilter     | String          | Initializes the dual listbox with a filter for the non selected elements | ''                       |
| ojs_selected_filter         | selectedFilter        | String          | Initializes the dual listbox with a filter for the selected elements     | ''                       |
| ojs_info_text               | infoText              | String, Boolean | Set this to false to hide this information                               | 'Voir tous {0}'          | false, string
| ojs_info_text_filtered      | infoTextFiltered      | String          | Determines which element format to use when some element is filtered     | '<span class="badge badge-warning">Filtré</span> {0} sur {1}' |
| ojs_info_text_empty         | infoTextEmpty         | String          | Determines the string to use when there are no options in the list       | 'Liste vide'             |
| ojs_filter_on_values        | filterOnValues        | Boolean         | Set this to true to filter the options according to their values         | false                    | true, false



## Select2

Select2 gives you a customizable select box with support for searching, tagging, remote data sets, infinite scrolling, and many other highly used options.

Source : https://github.com/select2/select2

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2ChoiceType;
// ...

$builder->add('ajax_ips', Select2ChoiceType::class, [
    'label' => 'Sélection IPs',
    'color' => 'red',
    'ojs_minimum_input_length' => 2,
    'ojs_allow_clear' => true
]);
~~~

### Options

| Nom SF                   | Nom JS             | Type    |	Description                                                                                    | Defaut    | Valeurs 
|--------------------------|--------------------|---------|------------------------------------------------------------------------------------------------|-----------|-------
| color                    |                    | String  | Color of widget                                                                                | 'default' | 
| ojs_allow_clear          | allowClear         | Boolean | Causes a clear button ("x" icon) to appear on the select box when a value is selected          | false     | true, false
| ojs_close_on_select      | closeOnSelect      | Boolean | Select2 will automatically close the dropdown when an element is selected                      | true      | true, false
| ojs_language             | language           | String  | Specify the language used for Select2 messages                                                 | 'fr'      | 
| ojs_placeholder          | placeholder        | String  | Specifies the placeholder for the control.                                                     | ''        | 
| ojs_minimum_input_length | maximumInputLength | Integer | Minimum number of characters required to start a search.                                       | 0         | 



## Select2 with AJAX remote datas

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'ajax_route' => 'addressip_ajax',
    'ajax_scroll' => false, // or true for scrolling by page
]);
~~~

Create the fonction in a controller for return full results (no scroll)
~~~ php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/addressip/ajax", name="addressip_ajax")
 */
public function getSearchIPs(Request $request, ManagerRegistry $doctrine): JsonResponse
{
    $entityManager = $doctrine->getManager();
    $term = $request->get('term');

    $query = $entityManager->getRepository(AddressIP::class)->createQueryBuilder('m')
        ->andWhere('m.ip LIKE :val')
        ->setParameter('val', '%'.$term.'%')
        ->orderBy('m.ip', 'ASC')
        ->getQuery();

    $results = [];
    foreach ($query->getResult() as $value) {
        $results[] = [
            'id' => $value->getId(),
            'text' => $value->getIp(),
        ];
    }

    return $this->json($results);
}
~~~

Create the fonction in a controller for return results by page with scrolling
~~~ php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @Route("/addressip/ajax", name="addressip_ajax")
 */
public function getSearchIPs(Request $request, ManagerRegistry $doctrine): JsonResponse
{
    $entityManager = $doctrine->getManager();
    $term = $request->get('term');
    $page = (int) $request->get('page', 1);

    $query = $entityManager->getRepository(AddressIP::class)->createQueryBuilder('m')
        ->andWhere('m.ip LIKE :val')
        ->setParameter('val', '%'.$term.'%')
        ->orderBy('m.ip', 'ASC')
        ->setFirstResult(($page-1) * 10)
        ->setMaxResults(10)
        ->getQuery();

    $addressips = new Paginator($query, $fetchJoinCollection = true);

    $results = [];
    foreach ($addressips as $value) {
        $results[] = [
            'id' => $value->getId(),
            'text' => $value->getIp(),
        ];
    }

    return $this->json([
        'results' => $results,
        'more' => (($page * 10) < count($addressips)),
    ]);
}
~~~

### Options

| Nom SF               | Nom JS             | Type    |	Description                                                                                    | Defaut    | Valeurs 
|----------------------|--------------------|---------|------------------------------------------------------------------------------------------------|-----------|-------
| multiple             |                    | Boolean | True for multiple select and false for single select.                                          | false     | true, false
| class                |                    | String  | True will enable infinite scrolling                                                            | null      |
| primary_key          |                    | String  | The name of the property used to uniquely identify entities                                    | 'id'      |
| label_field          |                    | String  | The entity property used to retrieve the text for existing data                                | null      |
| color                |                    | String  | Color of widget                                                                                | 'default' | 
| allow_clear          | allowClear         | Boolean | Causes a clear button ("x" icon) to appear on the select box when a value is selected          | false     | true, false
| close_on_select      | closeOnSelect      | Boolean | Select2 will automatically close the dropdown when an element is selected                      | true      | true, false
| language             | language           | String  | Specify the language used for Select2 messages                                                 | 'fr'      | 
| placeholder          | placeholder        | String  | Specifies the placeholder for the control.                                                     | ''        | 
| minimum_input_length | maximumInputLength | Integer | Minimum number of characters required to start a search.                                       | 0         | 
| ajax_route           | ajax / url         | String  | Route of ajax remote datas                                                                     | null      | 
| ajax_param           |                    | Array   | Parameters of route                                                                            | []        | 
| ajax_scroll          |                    | Boolean | "infinite scrolling" for remote data sources out of the box                                    | true      | true, false
| ajax_delay           | ajax / delay       | Integer | The number of milliseconds to wait for the user to stop typing before issuing the ajax request | 250       | 
| ajax_cache           | ajax / cache       | Boolean |                                                                                                | true      | true, false



## DateTime picker

Date and time picker designed to integrate into your Bootstrap

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\DatePickerType;
use Olix\BackOfficeBundle\Form\Type\DateTimePickerType;
use Olix\BackOfficeBundle\Form\Type\TimePickerType;

$builder
    ->add('datetime', DateTimePickerType::class, [
        'label' => 'Date et heure',
        'ojs_default_date' => new DateTime('2022-05-10'),
        'ojs_disabled_dates' => [new DateTime('2022-05-13'), new DateTime('2022-05-15')],
        'ojs_side_by_side' => true,
        'ojs_days_of_week_disabled' => [0, 6],
    ])
    ->add('date', DatePickerType::class, [
        'label' => 'Date',
        'ojs_min_date' => new DateTime('05/05/2022'),
        'ojs_calendar_weeks' => true,
    ])
    ->add('time', TimePickerType::class, [
        'label' => 'Heure',
    ]);
~~~

### Options

| Nom SF                      | Nom JS                | Type               | Description                                                                               | Defaut | Valeurs 
|-----------------------------|-----------------------|--------------------|-------------------------------------------------------------------------------------------|--------|---------
| button_icon                 |                       | String             | Icon from right input                                                                     |        | 
| locale                      |                       | String             | Locale                                                                                    | 'fr'   |
| ojs_stepping                | stepping              | Integer            | Number of minutes the up/down arrow's will move the minutes value in the time picker      | 1      |
| ojs_min_date                | minDate               | String,Date,moment | Disallows the user to select a moment that is before that moment                          | false  | 
| ojs_max_date                | maxDate               | String,Date,moment | Disallows the user to select a moment that is after that moment                           | false  | 
| ojs_use_current             | useCurrent            | String,Boolean     |                                                                                           | true   | true, false
| ojs_collapse                | collapse              | Boolean            | Using a Bootstraps collapse to switch between date/time pickers                           | true   | true, false
| ojs_default_date            | defaultDate           | String,Date,moment | Sets the picker default date/time. Overrides useCurrent                                   | false  | 
| ojs_disabled_dates          | disabledDates         | Array              | Array with the currently set disabled dates on the component                              | false  | 
| ojs_enabled_dates           | enabledDates          | Array              | Array with the currently set enabled dates on the component                               | false  | 
| ojs_icons                   | icons                 | Object             | Change the default icons for the pickers functions                                        |        | 
| ojs_side_by_side            | sideBySide            | Boolean            | Shows the picker side by side when using the time and date together                       | false  | 
| ojs_days_of_week_disabled   | daysOfWeekDisabled    | Array              | Disables the section of days of the week                                                  | []     | 
| ojs_calendar_weeks          | calendarWeeks         | Integer            | Shows the week of the year to the left of first day of the week                           | false  | 
| obj_view_mode               | viewMode              | String             | The default view to display when the picker is shown                                      | 'days' | 'decades','years','months','days', 'times'
| obj_keep_open               | keepOpen              | Boolean            | Will cause the date picker to stay open after selecting a date                            | false  | true, false
| obj_disabled_time_intervals | disabledTimeIntervals | Boolean            | Disables time selection between the given moments                                         | false  | true, false
| obj_allow_input_toggle      | allowInputToggle      | Boolean            | If true, the picker will show on textbox focus and icon click when used in a button group | false  | true, false
| obj_focus_on_show           | focusOnShow           | Boolean            | If false, the textbox will not be given focus when the picker is                          | true   | true, false
| obj_disabled_hours          | disabledHours         | Array              | Disallow hour selections but will affect all days                                         | false  | []


## Input text with icon

### Exemple

~~~ php
use Olix\BackOfficeBundle\Form\Type\TextType;
// ...

$builder->add('text', TextType::class, [
    'label' => 'Texte avec ico',
    'left_symbol' => '<i class="fas fa-phone"></i>',
    'right_symbol' => 'Km',
]);
~~~

### Options

| Nom SF       | Type    |	Description                | Defaut    | Valeurs 
|--------------|---------|-----------------------------|-----------|----------------------------------------
| left_symbol  | String  | Left symbol of widget       | null      | 
| right_symbol | String  | Right symbol of widget      | null      | 



## Collection

## Exemple

Dans la classe du formulaire :
~~~ php
use Olix\BackOfficeBundle\Form\Type\CollectionType;
// ...

$builder->add('steps', CollectionType::class, [
    'label' => 'Etapes de la préparation',
    'button_label_add' => 'Nouvelle étape',
    'entry_type' => StepType::class,
    'entry_options' => ['label' => false],
    'allow_add' => true,
    'allow_delete' => true,
    'by_reference' => false,
    'delete_empty' => true,
    'block_name' => 'recipe_steps', // Custom form => _recipe_recipe_steps_row
    'attr' => [
        'class' => 'collection-widget',
    ],
]);
~~~

A rajouter dans le template de la page du formulaire :
~~~ twig
{% form_theme form _self %}

{# Nom de 'block_prefix' du formulaire "StepType" #}
{% block recipe_step_row -%}
    <div class="form-group form-row collection-item">
        <div class="col-sm-12">
            <div class="form-group form-row form-control-sm">
                <div class="col-12 col-lg-11">
                    {{ form_widget(form.content, { attr: form.content.vars.attr|merge({'class': form.content.vars.attr['class']|default('') ~ ' form-control-sm' }) }) }}
                    {{ form_errors(form.content) }}
                    {{ form_help(form.content) }}
                </div>
                <div class="col-2 col-lg-1">
                    <button type="button" class="btn btn-danger btn-sm collection-btn-delete">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
{%- endblock %}

{% block javascripts %}
    {{ parent() }}
    <script src="{{ asset('bundles/olixbackoffice/js/collection.js') }}"></script>
    <script type="text/javascript">
        $('.collection-widget').olixCollection();
    </script>
{% endblock %}
~~~

### Options

| Nom SF           | Type    | Description                 | Defaut    | Valeurs 
|------------------|---------|-----------------------------|-----------|----------------------------------------
| button_label_add | String  | Label of add button         | 'Add'     | 
