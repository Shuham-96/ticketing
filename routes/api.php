<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pcs', [TicketController::class, 'PCS']);

/* Route::apiResource('/tickets', TicketController::class); */
Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);
    Route::get('/active', [TicketController::class, 'active']);
    Route::get('/completed', [TicketController::class, 'completed']);
    Route::post('/', [TicketController::class, 'store']);
    Route::get('/{ticket}', [TicketController::class, 'show']);
    Route::put('/{ticket}', [TicketController::class, 'update']);
    Route::delete('/{ticket}', [TicketController::class, 'destroy']);

    // Additional routes can be defined within the group
    Route::post('/{ticket}/close', [TicketController::class, 'close']);
    Route::post('/{ticket}/reopen', [TicketController::class, 'reopen']);
});
