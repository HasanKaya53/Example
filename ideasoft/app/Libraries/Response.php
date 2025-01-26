<?php

namespace App\Libraries;


class Response
{
    public static function responseJson($statusCode = 200, $data = [], $errorMessage = null){

      $status = ($statusCode == 200) ? true : false;
      $responseData = [
          'status' => $status,
      ];

      if(!$status){
          $responseData['error'] = $errorMessage;
      }else{
          $responseData['data'] = $data;
      }


        return response()->json($responseData, $statusCode);

    }

}
