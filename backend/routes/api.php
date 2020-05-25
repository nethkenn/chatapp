<?php

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

Route::post('login', 'AccountController@login');

Route::group(['middleware' => ['auth:api']], function() {
    Route::post('logout'	               			, 'AccountController@logout');

    //message routes
    Route::post('message/fetchMessages      '       , 'MessageController@fetchMessages');
    Route::post('message/sendMessage        '       , 'MessageController@sendMessage');
    Route::post('message/removeMessage      '       , 'MessageController@removeMessage');
    Route::post('message/changeReaction     '       , 'MessageController@changeReaction');
    Route::post('message/saveSeen           '       , 'MessageController@saveSeen');
    
    //conversation routes
    Route::post('conversation/fetchConversations'               , 'ConversationController@fetchConversations');
    Route::post('conversation/saveChatColor'                    , 'ConversationController@saveChatColor');
    Route::post('conversation/saveNickname'                     , 'ConversationController@saveNickname');
    Route::post('conversation/fetchConversationSettings'        , 'ConversationController@fetchConversationSettings');
});

