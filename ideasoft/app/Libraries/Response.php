<?php

namespace App\Libraries;


class Response
{
    public static function responseJson($statusCode = 200, $data = [], $errorMessage = null, $responseKey = 'data'){

      $status = ($statusCode == 200) ? true : false;
      $responseData = [
          'status' => $status,
      ];

      if(!$status){
          $responseData['error'] = $errorMessage;
      }else{
          if(!empty($responseKey))
            $responseData[$responseKey] = $data;
          else
            $responseData = $data;
      }


        return response()->json($responseData, $statusCode);

    }

}
