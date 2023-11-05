<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KokologClientController;
use App\Http\Controllers\KokoromilHealthController;
use App\Http\Controllers\MasterCompanyGroupController;
use App\Http\Controllers\MasterHomeGroupController;
use App\Http\Controllers\MasterHospitalGroupController;
use App\Http\Controllers\MasterUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

});

Route::middleware(['api_key'])->group(function () {

    // サインアップ
    Route::post('/auth/signup', [AuthController::class, 'signup']);

    // ログイン
    Route::post('/auth/login', [AuthController::class, 'login']);
});

// SNSログイン (Google)
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// SNSログイン (LINE)
Route::get('/auth/line', [AuthController::class, 'redirectToLine']);
Route::get('/auth/line/callback', [AuthController::class, 'handleLineCallback']);

// SNSログイン (Facebook)
Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);

// TODO SNSログイン (Apple)
Route::get('/auth/apple', [AuthController::class, 'redirectToApple']);
Route::get('/auth/apple/callback', [AuthController::class, 'handleAppleCallback']);

// SNSログインの後、Emailのチェック
Route::get('/auth/email', [AuthController::class, 'loginWithEmail']);

Route::middleware(['api_key', 'access_token'])->group(function () {

    // ログアウト
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // プロフィール取得
    Route::get('/user/profile', [MasterUserController::class, 'index'])->middleware('api_key');

    // プロフィール保存
    Route::post('/user/profile/save', [MasterUserController::class, 'save'])->middleware('api_key');

    // ホームグループ取得
    Route::get('/home-group', [MasterHomeGroupController::class, 'index']);

    // ホームグループ保存
    Route::post('/home-group/create', [MasterHomeGroupController::class, 'create']);

    // ホームグループメンバー保存
    Route::post('/home-group/member/save', [MasterHomeGroupController::class, 'saveMember']);

    // ホームグループメンバー削除
    Route::post('/home-group/member/delete', [MasterHomeGroupController::class, 'deleteMember']);

    // ホームグループデバイス保存
    Route::post('/home-group/device/save', [MasterHomeGroupController::class, 'saveDevice']);

    // ホームグループデーバイス削除
    Route::post('/home-group/device/delete', [MasterHomeGroupController::class, 'deleteDevice']);

    // 企業デバイスペア妥当性チェック
    Route::get('/company-group/device/validate', [MasterCompanyGroupController::class, 'validateCompanyGroupDevice']);

    // 病院デバイスペア妥当性チェック
    Route::get('/hospital-group/device/validate', [MasterHospitalGroupController::class, 'validateHospitalGroupDevice']);

    // kokoromil診断申し込み
    Route::post('/kokoromil-exam/entry', [MasterUserController::class, 'entryKokoromiExam']);

    // kokoromil診断アンケート登録
    Route::post('/kokoromil-exam/question/entry', [MasterUserController::class, 'entryKokoromiExamQuestion']);

    // kokoromil診断結果一覧
    Route::get('/kokoromil-exam/results', [MasterUserController::class, 'outputKokoromiExam']);

    // KokologClientサマリー一覧
    Route::get('/kokolog/summary', [KokologClientController::class, 'listKokologSumary']);

    // KokoromilHealthサマリー一覧
    Route::get('/kokoromil/summary', [KokoromilHealthController::class, 'listKokoromilSumary']);

    // KokologClientサマリー保存
    Route::post('/kokolog/summary/save', [KokologClientController::class, 'saveKokologSummary']);

    // KokomilHealthサマリー保存
    Route::post('/kokoromil/summary/save', [KokoromilHealthController::class, 'saveKokoromilSummary']);

    // Analysis監視開始
    Route::post('/analysis/open', [AnalysisController::class, 'openAnalysis']);

    // Analysis監視継続
    Route::post('/analysis/update', [AnalysisController::class, 'updateAnalysis']);

    // Analysis監視停止
    Route::post('/analysis/close', [AnalysisController::class, 'closeAnalysis']);
});
