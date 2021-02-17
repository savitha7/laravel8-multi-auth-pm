<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BaseService;
use Exception;
use Throwable;
use App\Exceptions\CustomException;

class BaseController extends Controller
{
	public const SUCCESS = 200;
    public const FORBIDDEN = 403;
    public const UNAUTHORIZED = 401;
    public const NOT_FOUND = 404;
    public const NOT_ALLOWED = 405;
    public const UNPROCESSABLE = 422;
    public const SERVER_ERROR = 500;
    public const BAD_REQUEST = 400;
    public const VALIDATION_ERROR = 252;

    /**
     * success response method.
     *
     * @param  array  $result
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($response = [], $request)
    {        
        $redirect_url = $response['url']??url()->previous();
        $code = $response['code']??200;
        $response['alert-type'] = $response['alert-type']??(isset($response['status'])?($response['status']?'success':'error'):NULL);
        if ($request && $request->ajax()) {
        	return response()->json($response, $code);
    	} else {
    		return redirect($redirect_url)->with($response);
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
        	return response()->json(['success' => true,'message' => $message], self::SUCCESS);
    	} else {
    		return redirect()->back()->with('success', $message);
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
        $response['success'] = false;

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
        	return response()->json($response, $code);
    	} else {
    		return redirect()->back()->with($response);
    	}
    }

    public function loadView($template=null,$data=[]){
        return view($template, $data);
    }

    public function encrypt ( $string, $prefix ='' )
    {
        return (new BaseService())->encrypt($string,$prefix); 
    }

    public function decrypt ( $string, $prefix ='')
    {
       $decryptString = (new BaseService())->decrypt($string,$prefix);
        if(!$decryptString){
            throw new CustomException(
                'Unprocessable Entity.',self::UNPROCESSABLE,['Invalid data.'],url()->previous()
            );
        }
        return $decryptString;      
    }
}