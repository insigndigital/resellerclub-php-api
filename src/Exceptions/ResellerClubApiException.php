<?php

declare(strict_types=1);

namespace ResellerClub\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Base exception for ResellerClub API errors.
 */
class ResellerClubApiException extends RuntimeException
{
    protected ?int $httpStatusCode;
    protected array $responseData;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?int $httpStatusCode = null,
        array $responseData = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->httpStatusCode = $httpStatusCode;
        $this->responseData = $responseData;
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }
}

