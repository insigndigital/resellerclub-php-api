<?php

namespace ResellerClub\APIs;

use Exception;
use ResellerClub\Helper;

/**
 * Class Actions
 *
 * @package ResellerClub\APIs
 */
class Actions
{
    use Helper;

    /**
     * @var string
     */
    protected $api = 'actions';

    /**
     * Gets the Current Actions based on the criteria specified.
     * 
     * @param array $eaqIds        Array of Integers
     * @param array $orderIds      Array of Integers
     * @param array $entityTypeIds Array of Integers
     * @param array $actionStatus  Array of Strings
     * @param array $actionType    Array of Strings
     * @param int   $noOfRecords
     * @param int   $pageNo
     *
     * @return Exception|array
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/908
     */
    public function searchCurrent(
        array $eaqIds = [],
        array $orderIds = [],
        array $entityTypeIds = [],
        array $actionStatus = [],
        array $actionType = [],
        $noOfRecords = 10,
        $pageNo = 1
    ) {

        $dataToSend = $this->fillParameters(
            [
                'no-of-records'  => $noOfRecords,
                'page-no'        => $pageNo,
                'eaq-id'         => $eaqIds,
                'order-id'       => $orderIds,
                'entity-type-id' => $entityTypeIds,
                'action-status'  => $actionStatus,
                'action-type'    => $actionType,
            ]
        );

        return $this->get(
            'search-current',
            $dataToSend
        );
    }

    /**
     * Searches the Archived Actions based on the criteria specified.
     * 
     * @param array $eaqIds        Array of Integers
     * @param array $orderIds      Array of Integers
     * @param array $entityTypeIds Array of Integers
     * @param array $actionStatus  Array of Strings
     * @param array $actionType    Array of Strings
     * @param int   $noOfRecords
     * @param int   $pageNo
     *
     * @return Exception|array
     * @throws Exception
     * @link https://manage.logicboxes.com/kb/node/909
     */
    public function searchArchived(
        array $eaqIds = [],
        array $orderIds = [],
        array $entityTypeIds = [],
        array $actionStatus = [],
        array $actionType = [],
        $noOfRecords = 10,
        $pageNo = 1
    ) {

        $dataToSend = $this->fillParameters(
            [
                'no-of-records'  => $noOfRecords,
                'page-no'        => $pageNo,
                'eaq-id'         => $eaqIds,
                'order-id'       => $orderIds,
                'entity-type-id' => $entityTypeIds,
                'action-status'  => $actionStatus,
                'action-type'    => $actionType,
            ]
        );

        return $this->get(
            'search-archived',
            $dataToSend
        );
    }
}
