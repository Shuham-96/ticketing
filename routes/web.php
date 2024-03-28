<?php

use App\Http\Controllers\AdministratorsController;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ConfigurationsController;
use App\Http\Controllers\PrioritiesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusesController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::middleware(['auth'])->group(function () {
Route::get('/home', function(){
	return redirect(action('\App\Http\Controllers\TicketsController@index'));
});

    Route::get("tickets/complete", 'App\Http\Controllers\TicketsController@indexComplete')->name("tickets-complete");
    Route::get("tickets/data/{id?}", 'App\Http\Controllers\TicketsController@data')->name("tickets.data");
    
    Route::get("tickets/get-agent-agencies", 'App\Http\Controllers\TicketsController@getAgentAgencies')->name("tickets.getAgentAgencies");

    $field_name = last(explode('/', 'tickets'));
    Route::resource('tickets', 'App\Http\Controllers\TicketsController', [
            'names' => [
                'index'   => 'tickets.index',
                'store'   => 'tickets.store',
                'create'  => 'tickets.create',
                'update'  => 'tickets.update',
                'show'    => 'tickets.show',
                'destroy' => 'tickets.destroy',
                'edit'    => 'tickets.edit',
            ],
            'parameters' => [
                $field_name => 'ticket',
            ],
        ]);

    $field_name = last(explode('/', "tickets-comment"));
    Route::resource("tickets-comment", 'App\Http\Controllers\CommentsController', [
            'names' => [
                'index'   => "tickets-comment.index",
                'store'   => "tickets-comment.store",
                'create'  => "tickets-comment.create",
                'update'  => "tickets-comment.update",
                'show'    => "tickets-comment.show",
                'destroy' => "tickets-comment.destroy",
                'edit'    => "tickets-comment.edit",
            ],
            'parameters' => [
                $field_name => 'ticket_comment',
            ],
        ]);

    Route::get("tickets/{id}/complete", 'App\Http\Controllers\TicketsController@complete')
            ->name("tickets.complete");

    Route::get("tickets/{id}/reopen", 'App\Http\Controllers\TicketsController@reopen')
            ->name("tickets.reopen");

    Route::group(['middleware' => 'App\Http\Middleware\IsAgentMiddleware'], function () {
        Route::get("tickets/agents/list/{category_id?}/{ticket_id?}", [
            'as'   => 'ticketsagentselectlist',
            'uses' => 'App\Http\Controllers\TicketsController@agentSelectList',
        ]);
    });

    Route::group(['middleware' => 'App\Http\Middleware\IsAdminMiddleware'], function () {
        Route::get("admin-tickets/indicator/{indicator_period?}", [
            'as'   => 'admin-tickets.dashboard.indicator',
            'uses' => 'App\Http\Controllers\DashboardController@index',
        ]);
        Route::get('admin-tickets', 'App\Http\Controllers\DashboardController@index');

        Route::resource('admin-tickets/status', StatusesController::class)->names([
            'index'   => "admin-tickets.status.index",
            'store'   => "admin-tickets.status.store",
            'create'  => "admin-tickets.status.create",
            'update'  => "admin-tickets.status.update",
            'show'    => "admin-tickets.status.show",
            'destroy' => "admin-tickets.status.destroy",
            'edit'    => "admin-tickets.status.edit",
        ]);
        
        Route::resource('admin-tickets/priority', PrioritiesController::class)->names([
            'index'   => "admin-tickets.priority.index",
            'store'   => "admin-tickets.priority.store",
            'create'  => "admin-tickets.priority.create",
            'update'  => "admin-tickets.priority.update",
            'show'    => "admin-tickets.priority.show",
            'destroy' => "admin-tickets.priority.destroy",
            'edit'    => "admin-tickets.priority.edit",
        ]);

        Route::resource('admin-tickets/agent', AgentsController::class)->names([
            'index'   => "admin-tickets.agent.index",
            'store'   => "admin-tickets.agent.store",
            'create'  => "admin-tickets.agent.create",
            'update'  => "admin-tickets.agent.update",
            'show'    => "admin-tickets.agent.show",
            'destroy' => "admin-tickets.agent.destroy",
            'edit'    => "admin-tickets.agent.edit",
        ]);
        
        Route::resource('admin-tickets/category', CategoriesController::class)->names([
            'index'   => "admin-tickets.category.index",
            'store'   => "admin-tickets.category.store",
            'create'  => "admin-tickets.category.create",
            'update'  => "admin-tickets.category.update",
            'show'    => "admin-tickets.category.show",
            'destroy' => "admin-tickets.category.destroy",
            'edit'    => "admin-tickets.category.edit",
        ]);

        Route::resource('admin-tickets/configuration', ConfigurationsController::class)->names([
            'index'   => "admin-tickets.configuration.index",
            'store'   => "admin-tickets.configuration.store",
            'create'  => "admin-tickets.configuration.create",
            'update'  => "admin-tickets.configuration.update",
            'show'    => "admin-tickets.configuration.show",
            'destroy' => "admin-tickets.configuration.destroy",
            'edit'    => "admin-tickets.configuration.edit",
        ]);

        
        Route::resource('admin-tickets/administrator', AdministratorsController::class)->names([
            'index'   => "admin-tickets.administrator.index",
            'store'   => "admin-tickets.administrator.store",
            'create'  => "admin-tickets.administrator.create",
            'update'  => "admin-tickets.administrator.update",
            'show'    => "admin-tickets.administrator.show",
            'destroy' => "admin-tickets.administrator.destroy",
            'edit'    => "admin-tickets.administrator.edit",
        ]);
    });
});



