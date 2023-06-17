# Autocomplete

Autocompletion in the search of the sidebar in block `sidebar_search`

Github : https://github.com/devbridge/jQuery-Autocomplete

## Script JS

~~~ javascript
import Routing from "./dist/scripts/routing";
import "devbridge-autocomplete";

$('#sdSearch').autocomplete({
  serviceUrl: Routing.generate('sidebar_autocomplete'),
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
