# CRUD


## Prerequis

For breadcrumb, positioning the route with two `__`

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
    /**
     * @Route("/tables/list", name="table__list")
     */
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
            'modal' => [
                'class' => 'modal-lg',
                'backdrop' => 'false',
            ],
        ]);
    }

    /**
     * @Route("/tables/create", name="table__create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = new MyEntity();
        $form = $this->createForm(MyFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('success', sprintf('La création de <strong>%s</strong> a bien été prise en compte', $entity));

            return $this->redirectToRoute('table_server_list'); // return new Response('OK');
        }

        return $this->renderForm('edit.html.twig', [ // @OlixBackOffice/Include/modal-form-(vertical|horizontal).html.twig
            'form' => $form,
            'modal' => [
                'title' => 'Créer un nouveau objet',
            ],
        ]);
    }

    /**
     * @Route("/tables/edit/{id}", name="table__edit")
     */
    public function update(Request $request, MyEntity $entity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MyFormType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', sprintf('La modification de <strong>%s</strong> a bien été prise en compte', $entity));

            return $this->redirectToRoute('table_server_list'); // return new Response('OK');
        }

        return $this->renderForm('edit.html.twig', [ // @OlixBackOffice/Include/modal-form-(vertical|horizontal).html.twig
            'form' => $form,
            'modal' => [
                'title' => 'Formulaire d\'édition d\'un objet',
            ],
        ]);
    }

    /**
     * @Route("/tables/delete/{id}", name="table__delete")
     */
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

        return $this->renderForm('@OlixBackOffice/Include/modal-content-delete.html.twig', [
            'form' => $form,
            'element' => sprintf('<strong>%s</strong>', $entity),
        ]);
    }
}
~~~


## Datatable

~~~ php
// src/Datatable/MyTableType.php
use App\Entity\MyEntity;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
//...

class MyTableType implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('id', TextColumn::class, [
                'label' => 'Id',
            ])
            ->add('hostname', TextColumn::class, [
                'label' => 'Hostname',
                'searchable' => true,
            ])
            ->add('state', TextColumn::class, [
                'label' => 'Statut',
                'raw' => true,
                'data' => fn ($row) => sprintf('<b>%s</b>', $row->getStateLabel()),
            ])
            ->add('buttons', TwigColumn::class, [
                'label' => '',
                'className' => 'text-right align-middle',
                'template' => 'buttonbar.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => MyEntity::class,
            ])
        ;
    }
}
~~~


## Form of item

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

## Template list of items

Pour afficher dans une fenêtre modale, il faut rajouter `data-toggle="olix-modal" data-target="#modalOlix"` dans la balise `href`.

~~~ twig
{# templates/crud-index.html.twig #}

{% extends 'base.html.twig' %}
{# ... #}

{% block content %}
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste</h3>
                        <div class="card-tools"><a href="{{ path('table__create') }}" class="btn btn-sm btn-success" data-toggle="olix-modal" data-target="#modalOlix"><i class="fas fa-plus"></i> Ajouter</a></div>
                    </div>
                    <div class="card-body">
                        <div id="olixDataTables">Loading...</div>
                        <script>
                            var olixDataTables = {{ datatable_settings(datatable) }};
                        </script>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {% include '@OlixBackOffice/Include/modal.html.twig' with { title: "Chargement du formulaire" } %}

{% endblock %}
~~~

~~~ twig
{# templates/buttonbar.html.twig #}

<a href="{{ path('table__edit', {'id': row.id}) }}" class="btn btn-sm btn-info" data-toggle="olix-modal" data-target="#modalOlix"><i class="fas fa-edit"></i><span class="d-none d-md-inline">&nbsp;Modifier<span></a>
<a href="{{ path('table__delete', {'id': row.id}) }}" class="btn btn-sm btn-danger" data-toggle="olix-modal" data-target="#modalOlix"><i class="fas fa-trash"></i><span class="d-none d-md-inline">&nbsp;Supprimer<span></a>
~~~


## Template form of a item

Dans une fenêtre modale :
~~~ twig
{# templates/crud-edit.html.twig #}

{% form_theme form '@OlixBackOffice/Twig/form-theme-horizontal-layout.html.twig' %}
{% include '@OlixBackOffice/Include/modal-content-form.html.twig' with { form: form, title: title} %}
~~~

Dans une nouvelle page :
~~~ twig
{# templates/crud-edit.html.twig #}

{% extends 'base.html.twig' %}
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
