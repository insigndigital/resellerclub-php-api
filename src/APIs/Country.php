<?php

namespace ResellerClub\APIs;

use Exception;
use ResellerClub\Helper;

/**
 * Class Country
 *
 * @package ResellerClub\APIs
 */
class Country
{
    use Helper;

    /**
     * @var string
     */
    protected $api = 'country';

    /**
     * Gets the list of Countries.
     * 
     * @return Exception|array
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/1747
     */
    public function list()
    {
        return $this->get('list');
    }

    /**
     * Gets the list of States for a specified Country.
     * 
     * @param string $countryCode
     * 
     * @return Exception|array
     * @throws Exception
     * @link https://manage.br.resellerclub.com/kb/node/1748
     */
    public function stateList($countryCode)
    {
        return $this->get(
            'state-list',
            [
                'country-code' => $countryCode
            ]
        );
    }
}
