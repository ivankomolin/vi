<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationList;

class InvalidRequestHttpException extends HttpException
{
    /**
     * Http код ответа
     */
    const CODE = 400;

    /**
     * Сообщение ответа
     */
    const MESSAGE = 'Request invalid';

    /**
     * Список ошибок валидации
     *
     * @var ConstraintViolationList
     */
    private $errors;

    /**
     * @param ConstraintViolationList $errors
     */
    public function __construct(ConstraintViolationList $errors)
    {
        $this->errors = $errors;
        parent::__construct(self::CODE, self::MESSAGE);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        $result = [];
        foreach ($this->errors as $error) {
            $result[$error->getPropertyPath()] = $error->getMessage();
        }

        return $result;
    }
}
