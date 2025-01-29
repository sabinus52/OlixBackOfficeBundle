# Formulaires étendus

Pour les options avec couleurs, la liste est la suivante :
~~~
'primary', 'blue', 'secondary', 'success', 'green', 'info', 'cyan', 'warning', 'yellow', 'danger', 'red', 'black', 'gray-dark', 'gray', 'light', 'default', 'indigo', 'navy', 'purple', 'fuchsia', 'pink', 'maroon', 'orange', 'lime', 'teal', 'olive'
~~~





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
    'js_minimum_input_length' => 2,
    'js_allow_clear' => true
]);
~~~

### Options

| Nom SF                  | Nom JS             | Type    |	Description                                                                                   | Défaut    | Valeurs 
|-------------------------|--------------------|---------|------------------------------------------------------------------------------------------------|-----------|-------
| color                   |                    | String  | Color of widget                                                                                | 'default' | 
| js_allow_clear          | allowClear         | Boolean | Causes a clear button ("x" icon) to appear on the select box when a value is selected          | false     | true, false
| js_close_on_select      | closeOnSelect      | Boolean | Select2 will automatically close the dropdown when an element is selected                      | true      | true, false
| js_language             | language           | String  | Specify the language used for Select2 messages                                                 | 'fr'      | 
| js_placeholder          | placeholder        | String  | Specifies the placeholder for the control.                                                     | ''        | 
| js_minimum_input_length | maximumInputLength | Integer | Minimum number of characters required to start a search.                                       | 0         | 



## Select2 with AJAX remote datas

### Exemple 1

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
]);
~~~

### Example 2 with define a custom route

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
    'class_pkey' => 'id',
    'class_label' => 'ip',
    'remote_route' => 'form_test_ajax',
    'ajax_js_scroll' => false, // or true for scrolling by page
]);
~~~

Create the fonction in a controller for return full results in AJAX
~~~ php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Olix\BackOfficeBundle\Helper\AutoCompleteService;
use App\Form\MyFormType;

/**
 * @Route("/address-ip/ajax", name="form_test_ajax")
 */
public function getSearchIPs(Request $request, AutoCompleteService $autoComplete): JsonResponse
{
    $results = $autoComplete->getResults(MyFormType::class, $request);

    return $this->json($results);
}
~~~

### Example 3 with allow add and create item

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
    'class_label' => 'ip', // !!! REQUIRED
    'js_allow_clear' => true,
    'allow_add' => true,
]);
~~~

### Example 4 with callback

~~~ php
use Olix\BackOfficeBundle\Form\Type\Select2AjaxType;
// ...

$builder->add('ajax_ips', Select2AjaxType::class, [
    'label' => 'Sélection IPs',
    'class' => AddressIP::class,
    'class_property' => 'ip',
    // Example 1
    'callback' => static function (QueryBuilder $qb): void {
        $qb->andWhere('entity.ip LIKE \'10.%\'');
    },
    // Example 2
    'callback' => static fn (QueryBuilder $qb) => $qb->andWhere("entity.ip LIKE '10.%'"),
]);
~~~

### Options

| Nom SF               | Nom JS             | Type    |	Description                                                                                    | Défaut    | Valeurs 
|----------------------|--------------------|---------|------------------------------------------------------------------------------------------------|-----------|-------
| multiple             |                    | Boolean | True for multiple select and false for single select.                                          | false     | true, false
| class                |                    | String  | The class of your entity                                                                       | null      |
| class_property       |                    | String  | The name of the property used to search the query                                              | null      |
| class_pkey           |                    | String  | The name of the property used to uniquely identify entities                                    | 'id'      |
| class_label          |                    | String  | The entity property used to retrieve the text for existing data                                | null      | __toString()
| page_limit           |                    | Integer | Number items by page for the scroll                                                            | 25        |
| remote_route         | ajax / url         | String  | Route of ajax remote datas                                                                     | olix_autocomplete_select2
| remote_params        |                    | Array   | Parameters of route                                                                            | []        | 
| ajax_js_scroll       |                    | Boolean | True will enable infinite scrolling                                                            | true      | true, false
| ajax_js_delay        | ajax / delay       | Integer | The number of milliseconds to wait for the user to stop typing before issuing the ajax request | 250       | 
| ajax_js_cache        | ajax / cache       | Boolean |                                                                                                | true      | true, false
| allow_add            | tags               | Boolean | Option for the add tags or value of Select2. 'class_label' is required                         | false     | true, false
| callback             |                    | Function| Callback you get the QueryBuilder to modify the result query                                   | null      | 





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

| Nom SF       | Type    |	Description                | Défaut    | Valeurs 
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
    'label' => 'Étapes de la préparation',
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
~~~
La classe `collection-btn-delete`est nécessaire pour l'activation du bouton de suppression.

### Options

| Nom SF           | Type    | Description                 | Défaut    | Valeurs 
|------------------|---------|-----------------------------|-----------|----------------------------------------
| button_label_add | String  | Label of add button         | 'Add'     | 


## Autocomplete

See : https://symfony.com/bundles/ux-autocomplete/current/index.html
