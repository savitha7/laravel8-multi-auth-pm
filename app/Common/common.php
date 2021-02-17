<?php
use App\Services\BaseService;
use App\Exceptions\CustomException;

/*
	Helper functions
*/

if (!function_exists('pr')) {
	function pr($data='', $exit = false){ 
		echo '<pre>';print_r($data);echo '</pre>';
		if($exit){exit;}
	}
}

if (!function_exists('withLabel')) {
	function withLabel($text=null,$color=null){
	    switch ($color) {
	        case 'green':
	            $response = '<span class="text-sm font-medium bg-green-100 py-1 px-2 rounded text-green-500 align-middle">'.$text.'</span>';
	            break;
	        case 'red':
	            $response = '<span class="text-sm font-medium bg-red-100 py-1 px-2 rounded text-red-500 align-middle">'.$text.'</span>';
	            break;
	        
	        default:
	            $response = '<span class="text-sm font-medium bg-gray-100 py-1 px-2 rounded text-red-500 align-middle">'.$text.'</span>';
	            break;
	    }        
	    return $response;        
	}
}

if (!function_exists('app_encrypt')) {
	function app_encrypt ( $string,$prefix ='' )
    {
        return (new BaseService())->encrypt($string,$prefix); 
    }
}

if (!function_exists('app_decrypt')) {
    function app_decrypt ( $string,$prefix ='')
    {
       $decryptString = (new BaseService())->decrypt($string,$prefix);
        if(!$decryptString){
            throw new CustomException(
                'Unprocessable Entity.','Unprocessable Entity',['Invalid data.'],url()->previous()
            );
        }
        return $decryptString;      
    }
}
