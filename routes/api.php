<?php

use App\Helpers\PermissionManager;
use App\Http\Controllers\manager\authController;
use App\Http\Controllers\manager\GenericController;
use App\Http\Controllers\manager\manLocaleController;
use App\Http\Controllers\manager\pimLocaleController;
use App\Http\Controllers\manager\ProfileController;
use App\Http\Controllers\manager\RightController;
use App\Http\Controllers\manager\UserController;
use App\Http\Controllers\pim\inputTypeController;
use App\Http\Controllers\pim\productController;
use App\Http\Controllers\pim\SpecificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ip-check', [GenericController::class, 'isIntermixIP']);

Route::post('/login', [authController::class, 'authenticate']);

Route::get('/users/all', [UserController::class, 'read_all']);

Route::get('/user/{id}', [UserController::class, 'show']);

Route::get('/profiles/all', [ProfileController::class, 'index']);

Route::post('/profiles/save', [ProfileController::class, 'create']);

Route::put('/profiles/save/{id}', [ProfileController::class, 'save']);

Route::put('/profiles/delete/{id}', [ProfileController::class, 'delete']);

Route::put('/users/save', [UserController::class, 'create']);

Route::put('/users/save/{id}', [UserController::class, 'save']);

Route::put('/users/delete/{id}', [UserController::class, 'delete']);

Route::post('/logout', [authController::class, 'logout']);

Route::post('/right/validate', function (Request $request) {
    $rights = $request->input('rights', []); // Assuming the frontend sends an array of permissions

    $hasPermission = PermissionManager::hasAnyPermission($request, $rights);

    return response()->json(['hasRight' => $hasPermission]);
});

Route::get('/rights/all', [RightController::class, 'index']);

Route::get('/profileRights/all', [RightController::class, 'profileWithRights']);

Route::post('/profileRights/save', [RightController::class, 'saveRight']);

Route::post('/profileRights/savedAuth', [RightController::class, 'saveAuthenticatedRight']);

Route::get('/locales/manager/all', [manLocaleController::class, 'index']);

Route::put('/locales/manager/set', [manLocaleController::class, 'setLocale']);

Route::post('/locales/manager/{id}/disable', [manLocaleController::class, 'setLanguageStatus']);

Route::get('/locales/pim/all', [pimLocaleController::class, 'index']);

Route::post('/locales/pim/{id}/disable', [pimLocaleController::class, 'disableLang']);

Route::post('/locales/pim/{id}/delete', [pimLocaleController::class, 'deleteLang']);

Route::post('/locales/pim/save', [pimLocaleController::class, 'saveLang']);

Route::post('/locales/pim/{id}/update', [pimLocaleController::class, 'updateLang']);

Route::get('/products/all', [productController::class, 'index']);

Route::put('/products/delete/{id}', [productController::class, 'delete']);

Route::put('/products/save/{id}', [productController::class, 'save']);

Route::put('/products/save', [productController::class, 'create']);

Route::get('/products/{id}', [productController::class, 'show']);

Route::post('/assets/upload', [productController::class, 'assetUploader']);

Route::post('/assets/delete/all', [productController::class, 'deleteAllAssets']);

Route::post('/assets/delete/{id}', [productController::class, 'deleteAsset']);

Route::get('/specifications/all', [SpecificationController::class, 'index']);

Route::post('/specifications/delete/{id}', [SpecificationController::class, 'delete']);

Route::post('/specifications/save', [SpecificationController::class, 'create']);

Route::post('/specifications/{id}/update', [SpecificationController::class, 'update']);

Route::get('/specifications/inputs/all', [inputTypeController::class, 'index']);

Route::post('/specifications/get/{id}', [SpecificationController::class, 'getTranslation']);

Route::post('/products/save/{id}/specifications', [productController::class, 'addSpecification']);

Route::post('/products/delete/{id}/specifications', [productController::class, 'removeSpecification']);

Route::post('/products/get/{id}/specifications', [productController::class, 'getProductSpecifications']);

Route::post('/products/update/{id}/specifications', [productController::class, 'updateSpecification']);

Route::post('/products/deleteAll/{id}/specifications', [productController::class, 'removeAllSpecifications']);

Route::get('/products/get/types/relations', [productController::class, 'getRelationTypes']);

Route::post('/products/update/{id}/relations', [productController::class, 'saveRelation']);

Route::post('/products/save/{id}/relations', [productController::class, 'createRelation']);

Route::get('/products/get/{id}/relations', [productController::class, 'getRelations']);

Route::post('/products/delete/{id}/relations', [productController::class, 'deleteRelation']);

Route::post('/products/deleteAll/{id}/relations', [productController::class, 'removeAllRelations']);
