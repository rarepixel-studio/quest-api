<?php

namespace QuestApi\Exceptions;

use GuzzleHttp\Exception\RequestException;

class QuestException extends \Exception
{
    /**
     * @return string|null
     */
    public function getHTTPResponseBody()
    {
        $previous = $this->getPrevious();
        if ($previous instanceof RequestException) {
            return $previous->getResponse()->getBody()->getContents();
        }
        return null;
    }
}
