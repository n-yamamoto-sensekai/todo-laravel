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
    // エラーコード
    private string $errorCode;

    //---------------------------------------
    // Constructor
    //---------------------------------------
    public function __construct(
        string $errorCode,
        string $message,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->errorCode = $errorCode;

        // 親クラスのConstructorコール
        parent::__construct($message, $code, $previous);
    }

    //---------------------------------------
    // Public Method
    //---------------------------------------
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getErrorInfo(): array
    {
        return [
            'error' => [
                'code'=> $this->errorCode,
                'message'=> $this->getMessage(),
            ],
        ];
    }
}
