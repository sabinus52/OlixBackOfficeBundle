Formulaire de type Collection
================================================================================

**Collection** permet de créer un formulaire de type collection.

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
    'block_name' => 'recipe_steps', // Custom form => recipe_recipe_steps_row
    'attr' => [
        'class' => 'collection-widget',
    ],
]);
~~~

A rajouter dans le template de la page du formulaire :
- Le bloc `step_row` qui sera utilisé pour chaque élément de la collection
- Le nom du bloc est composé de la valeur de `block_prefix` du formulaire de la collection et suffixé de `_row`

~~~ twig
{% form_theme form _self %}

{# Valeur de 'block_prefix' du formulaire "StepType" #}
{% block step_row -%}
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

Dans l'asset `app.js` de l'application, il faut charger le plugin `OlixCollection` et l'initialiser :
~~~ js
$(".collection-widget").OlixCollection();
~~~


### Options Symfony

| Nom SF           | Type    | Description                 | Défaut    | Valeurs 
|------------------|---------|-----------------------------|-----------|----------------------------------------
| button_label_add | String  | Label of add button         | 'Add'     | 
