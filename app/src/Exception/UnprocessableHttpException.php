<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnprocessableHttpException extends HttpException
{
    /**
     * Http код ответа
     */
    const CODE = 422;

    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct(self::CODE, $message);
    }
}
