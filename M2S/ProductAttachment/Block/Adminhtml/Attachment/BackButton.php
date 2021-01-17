<?php

namespace M2S\ProductAttachment\Block\Adminhtml\Attachment;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GeneralUrl implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf(
                "location.href = '%s';",
                $this->getBackUrl()
            ),
            'class' => 'back',
            'sort_order' => 10

        ];
    }

    public function getBackUrl(): string
    {
        return $this->getUrl('*/*');
    }
}
