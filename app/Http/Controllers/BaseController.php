<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Str;

class BaseController extends Controller {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * md5ハッシュ生成する
    *
    * @param mixed $number
    * @return string
    */

    function generateRandomHash( $number ) {
        // ランダムな文字列を生成
        $randomString = Str::random( $number );

        // タイムスタンプ( yyyymmddhhmmss )を取得
        $timestamp = now()->format( 'YmdHis' );

        // 生成した文字列にタイムスタンプを結合
        $combinedString = $randomString . $timestamp;

        // md5ハッシュに変換
        $hashedString = md5( $combinedString );

        return $hashedString;
    }

    /**
    * success response method.
    *
    * @return \Illuminate\Http\Response
    */

    public function sendResponse( $result, $message ) {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json( $response, 200 );
    }
    /**
    * return error response.
    *
    * @return \Illuminate\Http\Response
    */

    public function sendError( $error, $errorMessages = [], $code = 404 ) {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if ( !empty( $errorMessages ) ) {
            $response[ 'data' ] = $errorMessages;
        }
        return response()->json( $response, $code );
    }
}
