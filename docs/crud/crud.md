Implémentation de la gestion des données *CRUD*
================================================================================


## Prérequis

Pour le fil d’Ariane, positionner la route avec deux `__`

***Facultatif***, dans l'entity, il faut ajouter la fonction `__toString` pour déterminer si l'objet est vide ou pas :
~~~ php
public function __toString(): string
{
    return ($this->label) ?: ''; // or return "{$this->label}";
}
~~~


## Controller

~~~ php
// src/Controller/TablesController
use App\Datatable\MyTableType;
use App\Entity\MyEntity;
use App\Form\MyFormType;
use Omines\DataTablesBundle\DataTableFactory;
// ...

class TablesController extends AbstractController
{
    #[Route('/tables/list', name: 'table__list')]
    public function index(Request $request, DataTableFactory $factory): Response
    {
        $datatable = $factory->createFromType(MyTableType::class)
            ->handleRequest($request)
        ;

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->renderForm('index.html.twig', [
            'datatable' => $datatable,
            'filter' => $this->createForm(MyTableFilterType::class),
            'modal' => [
                'class' => 'modal-lg',
                'backdrop' => 'false',
            ],
        ]);
    }

    #[Route('/tables/create', name: 'table__create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = new MyEntity();
        $form = $this->createForm(MyFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('success', sprintf('La création de <strong>%s</strong> a bien été prise en compte', $entity));

            return $this->redirectToRoute('table_server_list');
        }

        return $this->renderForm('edit.html.twig', [
            'form' => $form,
            'modal' => [
                'title' => 'Créer un nouveau objet',
            ],
        ]);
    }

    #[Route('/tables/edit/{id}', name: 'table__edit')]
    public function update(Request $request, MyEntity $entity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MyFormType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', sprintf('La modification de <strong>%s</strong> a bien été prise en compte', $entity));

            return $this->redirectToRoute('table_server_list');
        }

        return $this->renderForm('edit.html.twig', [ 
            'form' => $form,
            'modal' => [
                'title' => 'Formulaire d\'édition d\'un objet',
                'btnlabel' => 'Mettre à jour', //Surcharge du label du bouton
            ],
        ]);
    }

    #[Route('/tables/delete/{id}', name: 'table__delete')]
    public function remove(Request $request, MyEntity $entity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($entity);
            $entityManager->flush();
            $this->addFlash('success', sprintf('La suppression de <strong>%s</strong> a bien été prise en compte', $entity));

            return new Response('OK');
        }

        return $this->renderForm('@OlixBackOffice/Modal/form-delete.html.twig', [
            'form' => $form,
            'element' => sprintf('<strong>%s</strong>', $entity),
        ]);
    }
}
~~~


## Table des données (Datatable)

Suivre la documentation : [Mise en place d'un DataTable avec le bundle DataTablesBundle (table de données)](datatables.md)


## Formulaire d'une entité

~~~ php
// src/Form//MyFormType.php
use Symfony\Component\Form\AbstractType;
// ...

class MyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hostname', TextType::class, [
                'label' => 'Hostname',
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => MyEntity::getChoiceStates(),
            ])
            ->add('opSystemVersion', Select2EntityType::class, [
                'label' => 'Système d\'exploitation',
                'required' => false,
                'multiple' => false,
                'class' => OpSystemVersion::class,
                'query_builder' => static fn (EntityRepository $er) => $er->createQueryBuilder('ver')
                    ->addSelect('os')
                    ->innerJoin('ver.opSystem', 'os')
                    ->orderBy('os.name', 'ASC')
                    ->addOrderBy('ver.version', 'ASC'),
                'choice_label' => 'fullName',
                'options_js' => [
                    'placeholder' => 'Rechercher un système d\'exploitation',
                    'allowClear' => true,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MyEntity::class,
        ]);
    }
}
~~~


### Dans une nouvelle page :

## Template pour l'édition d'une entité

~~~ twig
{# templates/crud-edit.html.twig #}

{% extends 'base_bo.html.twig' %}
{# ... #}

{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                {{ form_start(form) }}
                <div class="card card-blue">
                    <div class="card-header">
                        <h3 class="card-title">Formulaire d'édition</h3>
                    </div>
                    <div class="card-body">
                        {{ form_rest(form) }}
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right">Valider</button>
                    </div>
                </div>
                {{ form_end(form) }}
            </div>
        </div>
    </div>
{% endblock %}
~~~


## Filtre des éléments de la table

Suivre la documentation : [Formulaire du filtre de recherche](filters.md)
