<?php

namespace Padam87\BillingoBundle\Exception;

use Throwable;

class BillingoException extends \Exception
{
    public function __construct($message = "", $code = 0, ?Throwable $previous = null, private array $errors = [])
    {
        $message = [$message];

        foreach ($this->errors as $error) {
            $message[] = sprintf('%s: %s', $error['field'], $error['message']);
        }

        parent::__construct(implode(PHP_EOL, $message), $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
