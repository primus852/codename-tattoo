<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Doctrine\Orm\Paginator;

class CollectionPaginationDataPersister implements DataPersisterInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        // This Data Persister is only for collections and if it's a GET operation
        return $data instanceof Paginator && $context['collection_operation_name'] === 'get';
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data, array $context = [])
    {
        // The $data is the collection (Paginator instance)
        // We add additional fields and return the modified array
        return [
            'page' => $data->getCurrentPage(),
            'items_per_page' => $data->getItemsPerPage(),
            'total_items' => $data->getTotalItems(),
            'total_pages' => ceil($data->getTotalItems() / $data->getItemsPerPage()),
            'items' => iterator_to_array($data->getIterator()), // The actual items that were fetched
        ];
    }

    /**
     * We don't need to remove anything in this case, so just return the data as is
     *
     * {@inheritdoc}
     */
    public function remove($data, array $context = []): void
    {
        // nothing to do here
    }
}
