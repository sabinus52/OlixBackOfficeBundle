Formulaire du filtre de recherche
==================================

~~~ php
// src/Form//MyFormType.php
use Symfony\Component\Form\AbstractType;
// ...

class MyTableFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('state', ChoiceType::class, [
                'label' => 'Statut',
                'required' => false,
                'choices' => MyEntity::getChoiceStates(),
                'attr' => [ 'tabindex' => 1 ], // Important : Number of columns to search
            ])
        ;
    }
}
~~~

Remarque :
- il faut ajouter l'option `attr: ['tabindex' => 1]` pour forcer le champ de recherche à la deuxième colonne de la Datatable
