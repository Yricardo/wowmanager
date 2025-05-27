<?php

namespace App\Exception;

class MessageException extends \Exception
{
    public const NOT_FRIENDS = 'NOT_FRIENDS';
    public const EMPTY_MESSAGE = 'EMPTY_MESSAGE';
    public const MESSAGE_TOO_LONG = 'MESSAGE_TOO_LONG';
    public const INVALID_TIMESTAMP = 'INVALID_TIMESTAMP';

    private string $errorCode;

    public function __construct(string $message, string $errorCode, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getHttpStatusCode(): int
    {
        return match($this->errorCode) {
            self::NOT_FRIENDS => 403,
            self::EMPTY_MESSAGE, self::MESSAGE_TOO_LONG, self::INVALID_TIMESTAMP => 400,
            default => 500
        };
    }
}