<?php

namespace App\Http\Controllers;

use App\Models\MasterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
* Summary of MasterUserController
*/

class MasterUserController extends BaseController {
    /**
    * Summary of index
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\JsonResponse|mixed
    */

    public function index( Request $request ) {
        $id = $request->input( 'user_id' );
        try {
            // ユーザ情報( master_users )を取得する
            $masterUser = MasterUser::find( $id );

            if ( !is_null( $masterUser->deleted_at ) ) {
                $response = [
                    'result' => 200,
                    'message' => 'ユーザがすでに削除されました。'
                ];
                return response()->json( $response );
            }

            //->whereNull( 'deleted_at' );
            if ( is_null( $masterUser ) ) {
                $response = [
                    'result' => 200,
                    'message' => trans( 'messages.MSG_00003' )
                ];
                return response()->json( $response );
            }

            Log::error( $masterUser->lastname );

            $profile = array(
                'lastname' => $masterUser->lastname,
                'firstname' => $masterUser->firstname,
                'lastname_kana' => $masterUser->lastname_kana,
                'firstname_kana' => $masterUser->firstname_kana,
                'gender' => $masterUser->gender,
                'birthday' => $masterUser->birthday,
                'email' => $masterUser->email,
                'tel' => $masterUser->tel,
                'zip_code' => $masterUser->zip_code,
                'address' => $masterUser->address
            );

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans( 'messages.MSG_00011' ),
                'profile' => $profile,
            ];
            return response()->json( $response );
        } catch ( \Exception $e ) {
            Log::error( 'Error Log:' );
            Log::error( $e );

            // 返却する
            return response()->json( [
                'result' => 400,
                'message' => trans( 'messages.MSG_00006' )
            ] );
        }
    }

    /**
    * Summary of save
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\JsonResponse|mixed
    */

    public function save( Request $request ) {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make( $request->all(), [
            'lastname' => 'required|max:32',
            'firstname' => 'required|max:32',
            'lastname_kana' => 'max:32',
            'firstname_kana' => 'max:32',
            'gender' => 'integer|min:0|max:2',
            'birthday' => 'date',
            'email' => 'email|max:255',
            'tel' => 'max:16',
            'zip_code' => 'max:8',
            'address' => 'max:64',
        ] );

        // パラメータのバリデーション
        if ( $validator->fails() ) {
            return response()->json( [
                'result' => 200,
                'error' => $validator->errors()
            ] );
        }

        $id = $request->input( 'user_id' );
        try {
            // ユーザ情報( master_users )を取得する
            $masterUser = MasterUser::find( $id );
            if ( is_null( $masterUser ) ) {
                $response = [
                    'result' => 200,
                    'message' => trans( 'messages.MSG_00003' )
                ];
                return response()->json( $response );
            }

            if ( !is_null( $masterUser->deleted_at ) ) {
                $response = [
                    'result' => 200,
                    'message' => 'ユーザがすでに削除されました。'
                ];
                return response()->json( $response );
            }
            $masterUser->lastname = $request[ 'lastname' ];
            $masterUser->firstname = $request[ 'firstname' ];
            $masterUser->lastname_kana = $request[ 'lastname_kana' ];
            $masterUser->firstname_kana = $request[ 'firstname_kana' ];
            $masterUser->gender = $request[ 'gender' ];
            $masterUser->birthday = $request[ 'birthday' ];
            $masterUser->email = $request[ 'email' ];
            $masterUser->tel = $request[ 'tel' ];
            $masterUser->zip_code = $request[ 'zip_code' ];
            $masterUser->address = $request[ 'address' ];
            $masterUser->updated_at = Carbon::now();

            // ユーザ情報( master_users )を保存する
            $masterUser->save();

            // 返却する
            $response = [
                'result' => 100,
                'message' => trans( 'messages.MSG_00012' ),
            ];
            return response()->json( $response );
        } catch ( \Exception $e ) {
            Log::error( 'Error Log:' );
            Log::error( $e );

            // 返却する
            return response()->json( [
                'result' => 400,
                'message' => trans( 'messages.MSG_00006' )
            ] );
        }
    }

}
