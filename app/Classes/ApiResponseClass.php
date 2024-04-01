<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
class ApiResponseClass
{
    public static function rollback($e, $message ="Something went wrong! Process not completed"){
        dd($e);
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message ="Something went wrong! Process not completed", $code = 500){
        Log::info($e);
        throw new HttpResponseException(response()->json(['success' => false,"message"=> $message], $code));
    }

    public static function sendResponse($result , $message ,$code=200){
        $response=[
            'success' => true,
            'data'    => $result
        ];
        if(!empty($message)){
            $response['message'] =$message;
        }
        return response()->json($response, $code);
    }
    public static function sendError($message, $data = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];
    
        if (!empty($data)) {
            $response['data'] = $data;
        }
    
        return response()->json($response, $code);
    }
    
}