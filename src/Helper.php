<?php

declare(strict_types=1);

namespace ResellerClub;

use Exception;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use ResellerClub\Exceptions\AuthenticationException;
use ResellerClub\Exceptions\ResellerClubApiException;
use ResellerClub\Exceptions\ResourceNotFoundException;
use ResellerClub\Exceptions\ValidationException;
use SimpleXMLElement;

/**
 * Trait Helper
 *
 * Provides shared HTTP request execution and response parsing for ResellerClub API modules.
 *
 * @package ResellerClub
 */
trait Helper
{
    protected Guzzle $guzzle;
    private array $authentication = [];

    public function __construct(Guzzle $guzzle, array $authentication)
    {
        $this->authentication = $authentication;
        $this->guzzle = $guzzle;
    }

    /**
     * Build HTTP query string avoiding bracketed indexes for arrays (required by LogicBoxes/ResellerClub API).
     */
    protected function buildQuery(array $args): string
    {
        $merged = array_merge($args, $this->authentication);
        $query = http_build_query($merged);
        return preg_replace('/%5B[0-9]+%5D/simU', '', $query) ?? $query;
    }

    /**
     * Executes a GET request against the API.
     *
     * @throws ResellerClubApiException
     */
    protected function get(string $method, array $args = [], string $prefix = ''): mixed
    {
        $endpoint = $this->api . '/' . $prefix . $method . '.json?' . $this->buildQuery($args);

        try {
            $response = $this->guzzle->get($endpoint);
            return $this->parse($response, 'json');
        } catch (ClientException|ServerException|BadResponseException $e) {
            $this->handleHttpException($e);
        } catch (Exception $e) {
            throw new ResellerClubApiException($e->getMessage(), (int) $e->getCode(), null, [], $e);
        }
    }

    /**
     * Executes a GET request expecting an XML response.
     *
     * @throws ResellerClubApiException
     */
    protected function getXML(string $method, array $args = [], string $prefix = ''): SimpleXMLElement
    {
        $endpoint = $this->api . '/' . $prefix . $method . '.xml?' . $this->buildQuery($args);

        try {
            $response = $this->guzzle->get($endpoint);
            return $this->parse($response, 'xml');
        } catch (ClientException|ServerException|BadResponseException $e) {
            $this->handleHttpException($e);
        } catch (Exception $e) {
            throw new ResellerClubApiException($e->getMessage(), (int) $e->getCode(), null, [], $e);
        }
    }

    /**
     * Executes a POST request with form parameters.
     *
     * @throws ResellerClubApiException
     */
    protected function post(string $method, array $args = [], string $prefix = ''): mixed
    {
        $args = array_merge($args, $this->authentication);
        $endpoint = $this->api . '/' . $prefix . $method . '.json';

        try {
            $response = $this->guzzle->request('POST', $endpoint, [
                RequestOptions::FORM_PARAMS => $args,
            ]);
            return $this->parse($response, 'json');
        } catch (ClientException|ServerException|BadResponseException $e) {
            $this->handleHttpException($e);
        } catch (Exception $e) {
            throw new ResellerClubApiException($e->getMessage(), (int) $e->getCode(), null, [], $e);
        }
    }

    /**
     * Executes a POST request using raw query-like parameters in the URL.
     *
     * @throws ResellerClubApiException
     */
    public function postArgString(string $method, string $args = '', string $prefix = ''): mixed
    {
        $authQuery = http_build_query($this->authentication);
        $queryString = preg_replace('/%5B[0-9]+%5D/simU', '', $args . '&' . $authQuery) ?? ($args . '&' . $authQuery);
        $endpoint = $this->api . '/' . $prefix . $method . '.json?' . $queryString;

        try {
            $response = $this->guzzle->request('POST', $endpoint);
            return $this->parse($response, 'json');
        } catch (ClientException|ServerException|BadResponseException $e) {
            $this->handleHttpException($e);
        } catch (Exception $e) {
            throw new ResellerClubApiException($e->getMessage(), (int) $e->getCode(), null, [], $e);
        }
    }

    /**
     * Parses the response body according to content type and detects API-level errors.
     *
     * @throws ResellerClubApiException
     */
    protected function parse(ResponseInterface $response, string $type = 'json'): mixed
    {
        $body = (string) $response->getBody();

        if ($type === 'xml') {
            $xml = simplexml_load_string($body);
            if ($xml === false) {
                throw new ResellerClubApiException('Failed to parse XML response from API.', 500, $response->getStatusCode());
            }
            return $xml;
        }

        $decoded = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Some endpoints return scalar responses like numbers or strings
            return $body;
        }

        // Check if API returned an error payload even with HTTP 200
        if (is_array($decoded) && isset($decoded['status']) && in_array(strtoupper((string) $decoded['status']), ['ERROR', 'FAILED'], true)) {
            $msg = $decoded['message'] ?? $decoded['error'] ?? 'ResellerClub API error response.';
            $this->throwTypedException($msg, $response->getStatusCode(), $decoded);
        }

        return $decoded;
    }

    /**
     * Handles Guzzle HTTP exceptions and maps them to domain exceptions.
     */
    private function handleHttpException(BadResponseException $e): never
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 500;
        $body = $response ? (string) $response->getBody() : '';
        $data = json_decode($body, true) ?? ['raw' => $body];
        $msg = is_array($data) && !empty($data['message']) ? $data['message'] : $e->getMessage();

        $this->throwTypedException($msg, $statusCode, is_array($data) ? $data : ['response' => $data]);
    }

    /**
     * Determines the specific exception type based on message and HTTP code.
     */
    private function throwTypedException(string $message, int $statusCode, array $data): never
    {
        $lowerMsg = strtolower($message);

        if ($statusCode === 401 || str_contains($lowerMsg, 'authentication failed') || str_contains($lowerMsg, 'ip has not been authorized') || str_contains($lowerMsg, 'invalid apikey') || str_contains($lowerMsg, 'access denied')) {
            throw new AuthenticationException($message, $statusCode, $statusCode, $data);
        }

        if ($statusCode === 404 || str_contains($lowerMsg, 'not found') || str_contains($lowerMsg, 'does not exist') || str_contains($lowerMsg, 'invalid order id')) {
            throw new ResourceNotFoundException($message, $statusCode, $statusCode, $data);
        }

        if ($statusCode === 400 || str_contains($lowerMsg, 'mandatory') || str_contains($lowerMsg, 'invalid parameter') || str_contains($lowerMsg, 'validation failed')) {
            throw new ValidationException($message, $statusCode, $statusCode, $data);
        }

        throw new ResellerClubApiException($message, $statusCode, $statusCode, $data);
    }

    /**
     * Formats attributes array to attr-name1 / attr-value1 format expected by LogicBoxes API.
     */
    protected function processAttributes(array $attributes = []): array
    {
        $data = [];
        $i = 0;
        foreach ($attributes as $key => $value) {
            $i++;
            $data["attr-name{$i}"] = $key;
            $data["attr-value{$i}"] = $value;
        }
        return $data;
    }

    /**
     * Filter empty parameters.
     */
    protected function fillParameters(array $parameters): array
    {
        $output = [];
        foreach ($parameters as $parameter => $input) {
            if ($input !== null && $input !== '') {
                $output[$parameter] = $input;
            }
        }
        return $output;
    }
}