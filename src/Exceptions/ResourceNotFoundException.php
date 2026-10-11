<?php

declare(strict_types=1);

namespace ResellerClub\Exceptions;

/**
 * Thrown when an order, domain, customer, or plan is not found.
 */
class ResourceNotFoundException extends ResellerClubApiException
{
}
