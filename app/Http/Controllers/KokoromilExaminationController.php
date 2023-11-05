<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KokoromilExaminationController extends BaseController
{
    /**
     * kokoromil診断申し込み
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function entryKokoromiExam(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'order_id' => 'required|integer',
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
            $orderId = $request->input('order_id');

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

    /**
     * kokoromil診断アンケート登録
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function entryKokoromiExamQuestion(Request $request)
    {
        // パラメータのバリデーションの定義
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'symptoms' => 'required',
            'diabetes' => 'required|bool',
            'cholesterol' => 'required|bool',
            'hbp' => 'required|bool',
            'heart_disease' => 'required',
            'brain_disease' => 'required',
            'cardiovascular_disease' => 'required',
            'pacemaker' => 'required|bool',
            'af' => 'required|bool',
            'chronic_fatigue' => 'required|integer',
            'depression' => 'required|integer',
            'anxiiety' => 'required|integer',
            'hyposomia' => 'required|integer',
            'oversleep' => 'required|integer',
            'smoking_history' => 'required',
            'drinkiing' => 'required',
            'exercise' => 'required',
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


    /**
     * kokoromil診断結果一覧
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function outputKokoromiExam(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            $examinations = 'TODO';

            // id
            // kokoromil_examination_status
            // scheduled_start_at
            // scheduled_end_at
            // scheduled_return_at
            // is_arrhythmia
            // is_sas
            // is_autonomic_nerve
            // is_stress
            // is_counseling
            // bed_in_at
            // sleep_start_at
            // sleep_end_at
            // wasos
            // drink
            // daytime_sleepness
            // remark
            // examination_start_at
            // examination_end_at
            // technician_code
            // doctor_code
            // arrhythmia_total
            // arrhythmia_af
            // arrhythmia_1
            // arrhythmia_2
            // arrhythmia_3
            // arrhythmia_comment
            // sleep_sas
            // sleep_lfhf
            // stress
            // returned_at
            // return_at
            // report_pdf_path

            // 返却する
            $response = [
                'result' => 100,
                'message' => '正常に完了しました。',
                'examinations' => $examinations,
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
