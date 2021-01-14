<?php

namespace M2S\ProductAttachment\Ui;

use Magento\Ui\DataProvider\AbstractDataProvider;
use M2S\ProductAttachment\Model\ResourceModel\Item\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    protected $collection;

    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $result = [];
        foreach ($this->collection->getItems() as $item) {
            $result[$item->getId()]['general'] = $item->getData();
        }
        return $result;
    }
}
