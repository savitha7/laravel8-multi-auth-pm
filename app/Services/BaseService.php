<?php
namespace App\Services;

use Illuminate\Http\Request;

class BaseService
{
    public $encrypt_method = 'AES-256-CBC';
    public $secret_key = 'test.app';
    public $secret_iv = 'test.app2021';

    public const SUCCESS = 200;
    public const FORBIDDEN = 403;
    public const UNAUTHORIZED = 401;
    public const NOT_FOUND = 404;
    public const NOT_ALLOWED = 405;
    public const UNPROCESSABLE = 422;
    public const SERVER_ERROR = 500;
    public const BAD_REQUEST = 400;
    public const VALIDATION_ERROR = 252;

    public function getHashKey(){
        return hash('sha256', $this->secret_key);
    }

    public function gethashIv(){
        return substr(hash('sha256', $this->secret_iv), 0, 16);
    }

    public function encrypt ( $string, $prefix ='' )
    {
        return base64_encode(openssl_encrypt($prefix.$string, $this->encrypt_method, $this->getHashKey(), 0, $this->gethashIv()));
    }
    public function decrypt ( $string, $prefix ='')
    {
        $decryptString = openssl_decrypt(base64_decode($string), $this->encrypt_method,  $this->getHashKey(), 0, $this->gethashIv());

        if ($prefix)
            $decryptString = str_replace($prefix,"",$decryptString);

        return $decryptString;
    }

    /**
     * success response method.
     *
     * @param  array  $result
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($response = [])
    {        
        $response['status'] = true;
       if((new Request())->ajax()){
            return json_encode($response);
        } else {
            return $response;
        }        
    }

    /**
     * success response method.
     *
     * @param  str  $message
     * @return \Illuminate\Http\Response
     */
    public function respondWithMessage($message = NULL) {
        if((new Request())->ajax()){
            return json_encode(['status' => true,'message' => $message]);
        } else {
            return ['status' => true,'message' => $message];
        }
    }

    /**
     * error response method.
     *
     * @param  int  $code
     * @param  str  $error
     * @param  array  $errorMessages
     * @return \Illuminate\Http\Response
     */
    public function sendError($code = NULL, $error = NULL, $errorMessages = [])
    {
        $response['status'] = false;

        switch ($code) {
            case self::UNAUTHORIZED:
                $response['message'] = 'Unauthorized';
                break;
            case self::FORBIDDEN:
                $response['message'] = 'Forbidden';
                break;
            case self::NOT_FOUND:
                $response['message'] = 'Not Found.';
                break;
            case self::NOT_ALLOWED:
                $response['message'] = 'Method Not Allowed.';
                break;
            case self::BAD_REQUEST:
                $response['message'] = 'Bad Request.';
                break;
            case self::UNPROCESSABLE:
                $response['message'] = 'Unprocessable Entity.';
                break;
            case self::SERVER_ERROR:
                $response['message'] = 'Whoops, looks like something went wrong.';
                break;
            case self::VALIDATION_ERROR:
                $response['message'] = 'Validation Error.';
                break;
            default:
                $response['message'] = 'Whoops, looks like something went wrong.';
                break;
        }

        $response['message'] = $error?$error:$response['message'];
        if(!empty($errorMessages)){
            $response['errors'] = $errorMessages;
        }

        if((new Request())->ajax()){
            return json_encode($response);
        } else {
            return $response;
        }
    }
}