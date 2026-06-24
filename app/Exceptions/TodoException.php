<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * TodoException
 * アプリ内共通の業務例外クラス
 */
class TodoException extends Exception
{
    //---------------------------------------
    // Properties
    //---------------------------------------
    // メッセージコード
    private string $messageCode;
    
    // 付加情報
    private string $additional;

    //---------------------------------------
    // Constructor
    //---------------------------------------
    public function __construct(
        string $messageCode,
        string $additional = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->messageCode = $messageCode;
        $this->additional = $additional;

        // 親クラスのConstructorコール
        parent::__construct($this->resolveMessage($messageCode), $code, $previous);
    }

    //---------------------------------------
    // Public Method
    //---------------------------------------
    public function getMessageCode(): string
    {
        return $this->messageCode;
    }

    public function getAdditional(): string
    {
        return $this->additional;
    }

    public function getErrorInfo(): array
    {
        return [
            'error' => [
                'code' => $this->messageCode,
                'message' => $this->getMessage(),
                'additional' => $this->getAdditional(),
            ],
        ];
    }

    //---------------------------------------
    // Private Method
    //---------------------------------------
    private function resolveMessage(string $messageCode): string
    {
        $message = __("messages.{$messageCode}");

        if ($message === "messages.{$messageCode}") {
            return $messageCode;
        }

        return $message;
    }
}
