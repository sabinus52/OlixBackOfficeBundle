# CRUD

For breadcrumb, positioning the route with two `__`

## Controller

~~~ php
// src/Controller/TablesController

use App\Datatable\AddressIPDatatable;
use App\Entity\AddressIP;
use App\Form\AddressIPType;
use Doctrine\ORM\EntityManagerInterface;
use Olix\BackOfficeBundle\Datatable\Response\DatatableResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TablesController extends AbstractController
{
    /**
     * @Route("/tables/addressip/list", name="table_adrip__list")
     */
    public function listAddressIP(Request $request, AddressIPDatatable $datatable, DatatableResponse $responseService): Response
    {
        $isAjax = $request->isXmlHttpRequest();

        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        $form = $this->createFormBuilder()->getForm();

        return $this->renderForm('default/addressip-table.html.twig', [
            'datatable' => $datatable,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/tables/addressip/create", name="table_adrip__create", methods={"GET", "POST"})
     */
    public function createAddressIP(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adrip = new AddressIP();
        $form = $this->createForm(AddressIPType::class, $adrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adrip);
            $entityManager->flush();

            $this->addFlash('success', 'La création a bien été prise en compte');

            return $this->redirectToRoute('table_adrip__list');
        }

        return $this->renderForm('default/addressip-edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Update server.
     *
     * @Route("/tables/addressip/edit/{id}", name="table_adrip__edit")
     */
    public function updateAddressIP(Request $request, AddressIP $adrip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddressIPType::class, $adrip);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La validation a bien été prise en compte');

            return $this->redirectToRoute('table_adrip__list');
        }

        return $this->renderForm('default/addressip-edit.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/tables/addressip/delete/{id}", name="table_adrip__delete", methods={"POST"})
     */
    public function deleteAddressIP(Request $request, AddressIP $adrip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($adrip);
            $entityManager->flush();

            $this->addFlash('success', 'La suppression a bien été prise en compte');

            return $this->redirectToRoute('table_adrip__list');
        }

        $this->addFlash('danger', 'Erreur lors de laa suppression');

        return $this->redirectToRoute('table_adrip__list');
    }
}
~~~


## Datatable

~~~ php
// src/Datatable/AddressIPDatatable.php

use Olix\BackOfficeBundle\Datatable\AbstractDatatable;
use Olix\BackOfficeBundle\Datatable\Column\ActionColumn;
use Olix\BackOfficeBundle\Datatable\Column\Column;
//...

class AddressIPDatatable extends AbstractDatatable
{
    public function buildDatatable(array $options = []): void
    {
        $this->ajax->set([]);

        $this->options->set([]);

        $this->columnBuilder
            ->add('id', Column::class, [
                'title' => 'Id',
            ])
            ->add('ip', Column::class, [
                'title' => 'IP',
            ])
            ->add(null, ActionColumn::class, [
                'actions' => [
                    [
                        'route' => 'table_adrip__edit',
                        'icon' => 'fas fa-edit',
                        'label' => 'Edit',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'attributes' => [
                            'title' => 'Edit',
                            'class' => 'btn btn-primary btn-sm',
                            'role' => 'button',
                        ],
                    ],
                    [
                        'route' => 'table_adrip__delete',
                        'icon' => 'fas fa-trash',
                        'label' => 'Delete',
                        'route_parameters' => [
                            'id' => 'id',
                        ],
                        'attributes' => [
                            'title' => 'Delete',
                            'class' => 'btn btn-danger btn-sm',
                            'role' => 'button',
                            // Pour la confirmation de la suppression d'un item
                            'onclick' => 'return olixBackOffice.confirmDelete(this)',
                        ],
                    ],
                ],
            ])
        ;
    }

    public function getEntity()
    {
        return AddressIP::class;
    }

    public function getName()
    {
        return 'addressip_datatable';
    }
}
~~~


## Form of item

~~~ php
// src/Form//AddressIPType.php

use Symfony\Component\Form\AbstractType;
// ...

class AddressIPType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ip', TextType::class, [
                'label' => 'Adresse IP',
            ])
            ->add('hostname', TextType::class, [
                'label' => 'Hostname',
            ])
            ->add('number', NumberType::class, [
                'label' => 'Numéro',
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => AddressIP::getChoiceStates(),
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressIP::class,
        ]);
    }
}
~~~

## Template list of items

~~~ twig
{# templates/addressip-table.html.twig #}

{% extends 'base.html.twig' %}
{# ... #}
{% block javascripts %}
{{ parent()}}
{{ olixbo_datatable_js(datatable) }}
{% endblock %}

{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-blue">
                    <div class="card-header">
                        <h3 class="card-title">Liste des IPs</h3>
                        <div class="card-tools"><a href="{{ path('table_adrip__create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Ajouter</a></div>
                    </div>
                    <div class="card-body">
                        {{ olixbo_datatable_html(datatable) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {# For confirm suppress #}
    {% include '@OlixBackOffice/Include/modal-delete.html.twig' with {'element': 'cette IP'} %}
{% endblock %}
~~~


## Template form of a item

~~~ twig
{# templates/addressip-edit.html.twig #}

{% extends 'base.html.twig' %}
{# ... #}

{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                {{ form_start(form) }}
                <div class="card card-blue">
                    <div class="card-header">
                        <h3 class="card-title">Formulaire d'édition d'une adresse IP</h3>
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
