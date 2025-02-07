<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * 404自訂義
     */
    public function render($request, Throwable $exception)
    {

        // 404 - Not Found
        if ($exception instanceof NotFoundHttpException) {
            return $this->status(404);
        }
        if (!config('app.debug')) {

            // 401 - Unauthorized
            if ($exception instanceof UnauthorizedHttpException) {
                return $this->status(401);
            }

            // 403 - Forbidden
            if ($exception instanceof AccessDeniedHttpException) {
                return $this->status(403);
            }

            // 其他 HTTP 錯誤（例如 405, 406, 408 等）
            if ($exception instanceof HttpException) {
                return $this->status($exception->getStatusCode());
            }

            // 500 - Internal Server Error（應放在最後，避免攔截其他異常）
            if ($exception instanceof \Exception) {
                return $this->status(500);
            }
        }
        return parent::render($request, $exception);
    }

    /**
     * 根據 HTTP 狀態碼返回錯誤頁面
     */
    private function status(int $statusCode)
    {
        // 如果不是開發模式，則顯示對應的錯誤頁面
        $errorView = "errors.{$statusCode}";

        // 確保錯誤視圖存在，避免錯誤
        if (view()->exists($errorView)) {
            return response()->view($errorView, [], $statusCode);
        }

        // 預設顯示 500 錯誤頁面
        return response()->view('errors.500', [], 500);

    }
}
