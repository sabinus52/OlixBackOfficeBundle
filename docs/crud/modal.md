Utilisation des formulaires modales
================================================================================

Si on veut afficher un formulaire dans une fenêtre modale, voici les étapes à suivre :


## Dans le template de la page

Suivre les étapes de la section **prérequis** : [Afficher une fenêtre modale](../modal.md)


## Dans le controller

~~~ php
// src/Controller/MyController

use App\Entity\MyEntity;
use App\Form\MyFormType;
// ...

class MyController extends AbstractController
{
    #[Route('/create', name: 'route_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = new MyEntity();
        $form = $this->createForm(MyFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();
            $this->addFlash('success', sprintf('La création de <strong>%s</strong> a bien été prise en compte', $entity));

            return new Response('OK');
        }

        return $this->renderForm('@OlixBackOffice/Modal/form-(vertical|horizontal).html.twig', [
            'form' => $form,
            'modal' => [
                'title' => 'Créer un nouveau objet',
            ],
        ]);
    }

    #[Route('/edit/{id}', name: 'route_edit')]
    public function update(Request $request, MyEntity $entity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MyFormType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', sprintf('La modification de <strong>%s</strong> a bien été prise en compte', $entity));

            return new Response('OK');
        }

        return $this->renderForm('@OlixBackOffice/Modal/form-(vertical|horizontal).html.twig', [
            'form' => $form,
            'modal' => [
                'title' => 'Formulaire d\'édition d\'un objet',
                'btnlabel' => 'Mettre à jour', //Surcharge du label du bouton
            ],
        ]);
    }

    #[Route('/delete/{id}', name: 'route_delete')]
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

Paramètres possibles pour les templates `form-vertical` et `form-horizontal` :

- `form` : L'objet du formulaire
- `modal`
  - `title` : Titre du modal
  - `btnLabel` : Label du bouton submit
  
Paramètres possibles pour le template `form-delete` :

- `form` : L'objet du formulaire
- `element` : Texte à afficher dans le message de confirmation
