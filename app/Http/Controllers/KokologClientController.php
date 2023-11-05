<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;



class KokologClientController extends BaseController
{
    /**
     * KokologClientサマリー一覧
     *
     * @param \Illuminate\Support\Facades\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function listKokologSumary(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // TODO


            $mesurements = $request->input('');

            // 返却する
            $response = [
                'result' => 100,
                'message' => '正常に完了しました。',
                'mesurements' => $mesurements,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => 'システムエラーが発生しました。'
            ]);
        }
    }



    /**
     * KokologClientサマリー保存
     *
     * @param \Illuminate\Support\Facades\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function saveKokologSummary(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            // TODO Độ dài
            'timestamp' => 'required|max:255',
            'cardiogram_raw' => 'required|max:255',
            'cardiogram_qrs_position' => 'required|max:255',
            'gyro_1sec_verage' => 'required|max:255',
            'breath_raw' => 'required|max:255',
            'breath_accel_position' => 'required|max:255',
        ]);

        // パラメータのバリデーション
        if ($validator->fails()) {
            return response()->json([
                'result' => 200,
                'error' => $validator->errors()
            ]);
        }

        try {
            $userId = $request->input('user_id');

            // TODO




            // 返却する
            $response = [
                'result' => 100,
                'message' => '正常に完了しました。',
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error Log:');
            Log::error($e);

            // 返却する
            return response()->json([
                'result' => 400,
                'message' => 'システムエラーが発生しました。'
            ]);
        }
    }
}
