<?php

use Illuminate\Support\Facades\Route;
use Baskarcm\TziChatUi\Http\Controllers\ViewPortalController;

/*
|--------------------------------------------------------------------------
| Invitation join "public" web route. Separated so auth
| type middleware does not have to be included
|--------------------------------------------------------------------------
*/

Route::get('join/{invite}', [ViewPortalController::class, 'showJoinWithInvite'])->name('messenger.invites.join');
