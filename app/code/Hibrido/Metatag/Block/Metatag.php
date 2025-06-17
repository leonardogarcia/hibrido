<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Hibrido\Metatag\Block;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;

class Metatag extends Template
{
    protected $storeManager;
    protected $pageFactory;
    protected $pageCollectionFactory;

    public function __construct(
        Template\Context $context,
        StoreManagerInterface $storeManager,
        PageFactory $pageFactory,
        PageCollectionFactory $pageCollectionFactory,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->pageFactory = $pageFactory;
        $this->pageCollectionFactory = $pageCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getAlternateLinks()
    {
        $result = [];

        $currentStore = $this->storeManager->getStore();
        $pageIdentifier = $this->getRequest()->getParam('page_id');
        $currentPage = $this->pageFactory->create()->load($pageIdentifier);

        if (!$currentPage || !$currentPage->getId()) {
            return $result;
        }

        $cmsIdentifier = $currentPage->getIdentifier();

        foreach ($this->storeManager->getStores() as $store) {
            $storeId = $store->getId();

            $cmsPage = $this->pageCollectionFactory->create()
                ->addStoreFilter($storeId)
                ->addFieldToFilter('identifier', $cmsIdentifier)
                ->getFirstItem();

            if ($cmsPage->getId()) {
                $localeCode = $store->getConfig('general/locale/code');
                $storeLanguage = strtolower(str_replace('_', '-', $localeCode));

                $baseUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
                $urlPath = trim($cmsPage->getIdentifier(), '/');
                $finalUrl = $baseUrl . $urlPath . '/';

                $result[] = [
                    'storeLanguage' => $storeLanguage,
                    'href' => $finalUrl
                ];
            }
        }

        return $result;
    }
}