<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\InvestmentController;

//campaigns
Route::get('campaigns', [CampaignController::class, 'index']);
Route::get('campaigns/{id}', [CampaignController::class, 'show']);

//investment
Route::post('campaigns/{id}/investments', [InvestmentController::class, 'store']);
