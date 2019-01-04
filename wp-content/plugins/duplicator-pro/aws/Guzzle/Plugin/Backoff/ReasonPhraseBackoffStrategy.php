<?php

namespace DuplicatorPro\Guzzle\Plugin\Backoff;

use DuplicatorPro\Guzzle\Http\Message\RequestInterface;
use DuplicatorPro\Guzzle\Http\Message\Response;
use DuplicatorPro\Guzzle\Http\Exception\HttpException;

/**
 * Strategy used to retry HTTP requests when the response's reason phrase matches one of the registered phrases.
 */
class ReasonPhraseBackoffStrategy extends AbstractErrorCodeBackoffStrategy
{
    public function makesDecision()
    {
        return true;
    }

    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        if ($response) {
            return isset($this->errorCodes[$response->getReasonPhrase()]) ? true : null;
        }
    }
}