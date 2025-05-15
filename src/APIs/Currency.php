<?php

namespace ResellerClub\APIs;

use Exception;
use ResellerClub\Helper;

/**
 * Class Currency
 *
 * @package ResellerClub\APIs
 */
class Currency
{
    use Helper;

    /**
     * @var string
     */
    protected $api = 'currency';

    /**
     * Gets details of the supported Currencies.
     * 
     * @return Exception|array
     * @throws Exception
     * @link https://manage.br.resellerclub.com/kb/node/1746
     */
    public function details()
    {
        return $this->get('details');
    }
}
