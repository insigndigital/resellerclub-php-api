<?php

declare(strict_types=1);

namespace ResellerClub\Exceptions;

/**
 * Thrown when authentication fails (invalid credentials or IP not whitelisted).
 */
class AuthenticationException extends ResellerClubApiException
{
}

