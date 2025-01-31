# Formulaires étendus

Pour les options avec couleurs, la liste est la suivante :
~~~
'primary', 'blue', 'secondary', 'success', 'green', 'info', 'cyan', 'warning', 'yellow', 'danger', 'red', 'black', 'gray-dark', 'gray', 'light', 'default', 'indigo', 'navy', 'purple', 'fuchsia', 'pink', 'maroon', 'orange', 'lime', 'teal', 'olive'
~~~






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
