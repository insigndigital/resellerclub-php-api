<?php

namespace ResellerClub\APIs;

use Exception;
use ResellerClub\Helper;

/**
 * Class Products
 *
 * @package ResellerClub\APIs
 */
class Products
{
    use Helper;

    protected $api = 'products';

    /**
     * Get customer prices
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/864
     * @todo Add optional parameters
     */
    public function customerPrice()
    {
        return $this->get('customer-price');
    }

    /**
     * Gets the Reseller Slab Pricing
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/865
     * @todo Add optional parameters
     */
    public function resellerPrice()
    {
        return $this->get('reseller-price');
    }

    /**
     * Gets the Reseller's Cost Price
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/1029
     * @todo Add optional parameters
     */
    public function resellerCostPrice()
    {
        return $this->get('reseller-cost-price');
    }

    /**
     * Checks the availability of a product for the specified domain name
     * 
     * @param string $domainName
     * @param string $productKey
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/832
     */
    public function available(
        $domainName,
        $productKey
    ) {
        return $this->get(
            'availability',
            [
                'domain-name' => $domainName,
                'product-key' => $productKey,
            ]
        );
    }

    /**
     * Gets the details of the active products
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/833
     */
    public function details()
    {
        return $this->get('details');
    }

    /**
     * Gets the mapping of product category to product keys
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/862
     * @todo Add optional parameters
     */
    public function categoryKeysMapping()
    {
        return $this->get('category-keys-mapping');
    }

    /**
     * Gets the details of the all active product plans of the Reseller
     * 
     * @param string $productKey
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/939
     * @todo Add optional parameters
     */
    public function planDetails(
        $productKey = ''
    ) {
        return $this->get(
            'plan-details',
            [
                'product-key' => $productKey,
            ]
        );
    }

    /**
     * Moves all products from one customer to other customer
     * 
     * @param string $domainName
     * @param int $existingCustomerId
     * @param int $newCustomerId
     *
     * @return array|Exception
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/904
     */
    public function move($domainName, $existingCustomerId, $newCustomerId)
    {
        return $this->post(
            'move',
            [
                'domain-name'          => $domainName,
                'existing-customer-id' => $existingCustomerId,
                'new-customer-id'      => $newCustomerId
            ]
        );
    }
}
