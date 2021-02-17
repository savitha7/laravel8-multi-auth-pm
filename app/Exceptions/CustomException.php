<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{

    /**
     * error code.
     *
     * @var string
     */
    protected $errorCode;

    /**
     * error.
     *
     * @var string
     */
    protected $message;

    /**
     * The path the user should be redirected to.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * error.
     *
     * @var string
     */
    protected $errorMessages;

    /**
     * Create a new custom exception.
     *
     * @param  string  $message
     * @param  string|null  $redirectTo
     * @return void
     */
    public function __construct($message = 'Whoops, looks like something went wrong.', $code = 401, $errors=[], $redirectTo = null)
    {
        parent::__construct($message);

        $this->redirectTo = $redirectTo;
        $this->message = $message;
        $this->errorMessages = $errors;
        $this->errorCode = $code;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $response['success'] = false;
        $response['message'] = $this->message;
        if(!empty($errorMessages)){
            $response['errors'] = $errorMessages;
        }

        if ($request->expectsJson()) {
            return response()->json($response, $this->errorCode);
        }
        if ($request->is(env('APP_ADMIN_NAME')) || $request->is(env('APP_ADMIN_NAME').'/*')) {
            return $this->redirectTo?redirect($this->redirectTo):redirect()->guest(route('admin.login'));
        }

        return abort($this->errorCode);
    }
}