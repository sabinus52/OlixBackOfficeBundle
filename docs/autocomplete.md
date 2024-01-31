# Autocomplete

Autocompletion in the search of the sidebar in block `sidebar_search`

Github : https://github.com/devbridge/jQuery-Autocomplete

## Template

~~~ twig
<!-- base.html.twig -->
{% block sidebar_search %}
    {% include '@OlixBackOffice/Sidebar/search.html.twig' with {'route': 'sidebar_autocomplete'} %}
{% endblock %}
~~~

## Script JS

~~~ javascript
$('#sdSearch').autocomplete({
  serviceUrl: $("#sdSearch").data("remote"),
  onSelect: function (suggestion) {
      console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
  }
});
~~~

## Controller

~~~ php
// src/Controller/DefaultController.php

/**
 * @Route("/autocomplete", options={"expose": true}, name="sidebar_autocomplete")
 */
public function autocompleteSideBar(Request $request, MyEntityRepository $repository): JsonResponse
{
    $term = $request->get('query');

    $items = $repository->createQueryBuilder('m')
        ->andWhere('m.field LIKE :val')
        ->setParameter('val', '%'.$term.'%')
        ->orderBy('m.field', 'ASC')
        ->getQuery()
        ->getResult()
    ;

    $result = [];
    foreach ($items as $item) {
        $result[] = [
            'value' => $item->getField(),
            'data' => $item->getId(),
        ];
    }

    // Respecter les clés
    return $this->json([
        'query' => $term,
        'suggestions' => $result,
    ]);
}
~~~
