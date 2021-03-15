<?php

namespace Padam87\BillingoBundle\Exception;

use Throwable;

class BillingoException extends \Exception
{
    private array $errors = [];

    public function __construct($message = "", $code = 0, Throwable $previous = null, array $errors = [])
    {
        $this->errors = $errors;

        $message = [$message];

        foreach ($errors as $error) {
            $message[] = sprintf('%s: %s', $error['field'], $error['message']);
        }

        parent::__construct(implode(PHP_EOL, $message), $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
