Formulaires
================================================================================


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


## Customisation des checkbox et radio

Pour personnaliser les checkbox et radio, il faut utiliser les classes `checkbox-custom` et `radio-custom` sur les labels.

~~~ php
$builder
    ->add('roles', ChoiceType::class, [
        'choices' => [
            'User' => 'ROLE_USER',
            'Admin' => 'ROLE_ADMIN',
        ],
        'multiple' => true,
        'expanded' => true,
        'label_attr' => [
            'class' => 'switch-custom', // 'checkbox-custom',
        ],
    ]);
~~~


## Formulaires étendus

- [**DualListBox**](forms/duallistbox.md)
- [**DateTimePicker**](forms/datetimepicker.md)
- [**Switch**](forms/switch.md)
- [**Select2**](forms/select2.md)
