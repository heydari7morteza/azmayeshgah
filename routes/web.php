<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DevicePositionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LiquidController;

use App\Http\Controllers\OptionController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ProjectMapController;
use App\Http\Controllers\ProjectProtocolController;
use App\Http\Controllers\gcodeDecoderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LiquidProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/user/userInfo',[UserController::class, 'userInfo'])->name('userInfo')->middleware(['auth','admin']);
Route::get('/user/addUser',[UserController::class, 'addUserForm'])->name('addUserForm')->middleware(['auth','admin']);
Route::post('/user/addUser',[UserController::class, 'addUser'])->name('addUser')->middleware(['auth','admin']);
Route::get('/user/editUser/{id}',[UserController::class, 'editUser'])->name('editUser')->middleware(['auth','admin']);
Route::post('/user/editUserInfo/{id}',[UserController::class, 'editUserInfo'])->name('editUserInfo')->middleware(['auth','admin']);
Route::get('/user/deleteUser/{id}',[UserController::class, 'deleteUser'])->name('deleteUser')->middleware(['auth','admin']);


Route::get('/device/createDevice/{id}',[DeviceController::class, 'createDevice'])->name('createDevice')->middleware(['auth']);
Route::post('/device/createDevice/{id}',[DeviceController::class, 'createDeviceForm'])->name('createDeviceForm')->middleware(['auth']);
Route::get('/device/show/{id}',[DeviceController::class, 'showDevice'])->name('showDevice')->middleware(['auth']);
Route::get('/device/allDevices',[DeviceController::class, 'allDevice'])->name('allDevice')->middleware(['auth','admin']);
Route::get('/device/edit/{id}',[DeviceController::class, 'editDevice'])->name('editDevice')->middleware(['auth']);
Route::post('/device/edit/{id}',[DeviceController::class, 'editDeviceForm'])->name('editDeviceForm')->middleware(['auth']);
Route::get('/device/delete/{id}',[DeviceController::class, 'deleteDevice'])->name('deleteDevice')->middleware(['auth']);


Route::get('/position/show',[PositionController::class, 'showPosition'])->name('showPosition')->middleware(['auth','admin']);
Route::get('/position/create',[PositionController::class, 'createPosition'])->name('createPosition')->middleware(['auth','admin']);
Route::post('/position/create',[PositionController::class, 'createPositionForm'])->name('createPositionForm')->middleware(['auth','admin']);
Route::get('/position/edit/{id}',[PositionController::class, 'editPosition'])->name('editPosition')->middleware(['auth','admin']);
Route::post('/position/edit/{id}',[PositionController::class, 'checkEditPosition'])->name('checkEditPosition')->middleware(['auth','admin']);
Route::get('/position/delete/{id}',[PositionController::class, 'deletePosition'])->name('deletePosition')->middleware(['auth','admin']);



Route::get('/devicePosition/{id}',[DevicePositionController::class, 'devicePosition'])->name('devicePosition')->middleware(['auth','admin']);
Route::get('/createDevicePosition/{id}',[DevicePositionController::class, 'createDevicePosition'])->name('createDevicePosition')->middleware(['auth','admin']);
Route::post('/createDevicePosition/{id}',[DevicePositionController::class, 'checkCreateDevicePosition'])->name('checkCreateDevicePosition')->middleware(['auth','admin']);
Route::get('/editDevicePosition/{id}',[DevicePositionController::class, 'editDevicePosition'])->name('editDevicePosition')->middleware(['auth','admin']);
Route::post('/editDevicePosition/{id}',[DevicePositionController::class, 'checkEditDevicePosition'])->name('checkEditDevicePosition')->middleware(['auth','admin']);
Route::get('/deleteDevicePosition/{id}',[DevicePositionController::class, 'deleteDevicePosition'])->name('deleteDevicePosition')->middleware(['auth','admin']);



Route::get('/project/{id}',[ProjectController::class, 'showProject'])->name('showProject')->middleware(['auth']);
Route::get('/projects/allProjects',[ProjectController::class, 'allProjects'])->name('allProjects')->middleware(['auth','admin']);
Route::get('/createProject/{id}',[ProjectController::class, 'createProject'])->name('createProject')->middleware(['auth']);
Route::post('/createProject/{id}',[ProjectController::class, 'checkCreateProject'])->name('checkCreateProject')->middleware(['auth']);
Route::get('/editProject/{id}',[ProjectController::class, 'editProject'])->name('editProject')->middleware(['auth']);
Route::post('/editProject/{id}',[ProjectController::class, 'checkEditProject'])->name('checkEditProject')->middleware(['auth']);
Route::get('/deleteProject/{id}',[ProjectController::class, 'deleteProject'])->name('deleteProject')->middleware(['auth']);


Route::get('/all_liquids',[LiquidController::class, 'all_liquids'])->name('all_liquids')->middleware(['auth','admin']);
Route::get('/liquids/{id}',[LiquidController::class, 'showLiquids'])->name('showLiquids')->middleware(['auth']);
Route::get('/createLiquid/{id}',[LiquidController::class, 'createLiquid'])->name('createLiquid')->middleware(['auth']);
Route::post('/createLiquid/{id}',[LiquidController::class, 'checkCreateLiquid'])->name('checkCreateLiquid')->middleware(['auth']);
Route::get('/editLiquid/{id}',[LiquidController::class, 'editLiquid'])->name('editLiquid')->middleware(['auth']);
Route::post('/checkEditLiquid/{id}',[LiquidController::class, 'checkEditLiquid'])->name('checkEditLiquid')->middleware(['auth']);
Route::get('/deleteLiquid/{id}',[LiquidController::class, 'deleteLiquid'])->name('deleteLiquid')->middleware(['auth']);

// this liquids for user ***
Route::get('/user/all_liquids',[LiquidController::class, 'userAll_liquids'])->name('userAll_liquids')->middleware(['auth','admin']);
Route::get('/user/liquids/{id}',[LiquidController::class, 'userShowLiquids'])->name('userShowLiquids')->middleware(['auth']);
Route::get('/user/createLiquid/{id}',[LiquidController::class, 'userCreateLiquid'])->name('userCreateLiquid')->middleware(['auth']);
Route::post('/user/createLiquid/{id}',[LiquidController::class, 'userCheckCreateLiquid'])->name('userCheckCreateLiquid')->middleware(['auth']);
Route::get('/user/editLiquid/{id}',[LiquidController::class, 'userEditLiquid'])->name('userEditLiquid')->middleware(['auth']);
Route::post('/user/checkEditLiquid/{id}',[LiquidController::class, 'userCheckEditLiquid'])->name('userCheckEditLiquid')->middleware(['auth']);
Route::get('/user/deleteLiquid/{id}',[LiquidController::class, 'userDeleteLiquid'])->name('userDeleteLiquid')->middleware(['auth']);
// end ***


Route::get('/liquid/project/{id}',[LiquidProjectController::class, 'liquidProject'])->name('liquidProject')->middleware(['auth']);
Route::get('/liquid/createLiquid/{id}',[LiquidProjectController::class, 'CreateLiquidProject'])->name('CreateLiquidProject')->middleware(['auth']);
Route::post('/liquid/createLiquid/{id}',[LiquidProjectController::class, 'checkCreateLiquidProject'])->name('checkCreateLiquidProject')->middleware(['auth']);
Route::get('/liquid/editLiquid/{id}',[LiquidProjectController::class, 'editLiquidProject'])->name('editLiquidProject')->middleware(['auth']);
Route::post('/liquid/checkEditLiquid/{id}',[LiquidProjectController::class, 'checkEditLiquidProject'])->name('checkEditLiquidProject')->middleware(['auth']);
Route::get('/liquid/deleteLiquid/{id}',[LiquidProjectController::class, 'deleteLiquidProject'])->name('deleteLiquidProject')->middleware(['auth']);


Route::get('/entities',[EntityController::class, 'entities'])->name('entityInfo')->middleware('auth');
Route::get('/entity/addEntity',[EntityController::class, 'addEntityForm'])->name('addEntityForm')->middleware(['auth','admin']);
Route::post('/entity/addEntity',[EntityController::class, 'addEntity'])->name('addEntity')->middleware(['auth','admin']);
Route::get('/entity/editEntity/{id}',[EntityController::class, 'editEntity'])->name('editEntity')->middleware(['auth','admin']);
Route::post('/entity/editEntityInfo/{id}',[EntityController::class, 'editEntityInfo'])->name('editEntityInfo')->middleware(['auth','admin']);
Route::get('/entity/deleteEntity/{id}',[EntityController::class, 'deleteEntity'])->name('deleteEntity')->middleware(['auth','admin']);



Route::get('/options/show',[OptionController::class, 'showOptions'])->name('showOptions')->middleware(['auth','admin']);
Route::get('/options/create/{id}',[OptionController::class, 'createOption'])->name('createOption')->middleware(['auth','admin']);
Route::post('/options/create/{id}',[OptionController::class, 'checkCreateOption'])->name('checkCreateOption')->middleware(['auth','admin']);
Route::get('/options/edit/{id}',[OptionController::class, 'editOption'])->name('editOption')->middleware(['auth','admin']);
Route::post('/options/edit/{id}',[OptionController::class, 'checkEditOption'])->name('checkEditOption')->middleware(['auth','admin']);
Route::get('/options/delete/{id}',[OptionController::class, 'deleteOption'])->name('deleteOption')->middleware(['auth','admin']);


Route::get('/project/map/{id}',[ProjectMapController::class, 'showMap'])->name('showMap')->middleware(['auth']);
Route::post('/project/map/setEntity',[ProjectMapController::class, 'setEntity'])->name('setEntityToPosition')->middleware(['auth']);
Route::get('/project/map/deleteEntity/{id}',[ProjectMapController::class, 'deleteEntity'])->name('deleteEntityToPosition')->middleware(['auth']);
Route::post('/project/map/addCalibrate',[ProjectMapController::class, 'addCalibrate'])->name('addCalibrate')->middleware(['auth']);
Route::post('/project/map/addLiquid',[ProjectMapController::class, 'addLiquid'])->name('addLiquid')->middleware(['auth']);
Route::post('/project/map/editLiquid',[ProjectMapController::class, 'editLiquid'])->name('editLiquidMap')->middleware(['auth']);
Route::post('/project/map/removeLiquid',[ProjectMapController::class, 'removeLiquid'])->name('removeLiquidMap')->middleware(['auth']);



Route::post('/project/protocol/addProtocol',[ProjectProtocolController::class, 'addProtocol'])->name('addProtocol')->middleware(['auth']);
Route::get('/project/protocol/editProtocolShow/{id}',[ProjectProtocolController::class, 'editProtocolShow'])->name('editProtocolShow')->middleware(['auth']);
Route::post('/project/protocol/editProtocol',[ProjectProtocolController::class, 'editProtocol'])->name('editProtocol')->middleware(['auth']);
Route::get('/project/protocol/removeProtocol/{id}',[ProjectProtocolController::class, 'removeProtocol'])->name('removeProtocol');
Route::post('/project/protocol/changeSequence',[ProjectProtocolController::class, 'changeSequence'])->name('changeSequence');
Route::get('/project/gcode/decoder/{id}',[gcodeDecoderController::class, 'decoder'])->name('gcodeDecoder');
Route::get('/project/gcode/download/{id}',[gcodeDecoderController::class, 'downloadGcode'])->name('gcodeDownloader');




Route::get('/all_tickets',[TicketController::class, 'all_tickets'])->name('all_tickets')->middleware(['auth','admin']);
Route::get('/ticket/{id}',[TicketController::class, 'showTicket'])->name('showTicket')->middleware(['auth']);
Route::get('/tickets/edit/{id}',[TicketController::class, 'editTicket'])->name('editTicket')->middleware(['auth']);
Route::post('/tickets/edit/{id}',[TicketController::class, 'checkEditTicket'])->name('checkEditTicket')->middleware(['auth']);
Route::get('/tickets/create/{id}',[TicketController::class, 'createTicket'])->name('createTicket')->middleware(['auth']);
Route::post('/tickets/create/{id}',[TicketController::class, 'checkCreateTicket'])->name('checkCreateTicket')->middleware(['auth']);
Route::get('/tickets/delete/{id}',[TicketController::class, 'deleteTicket'])->name('deleteTicket')->middleware(['auth']);


Route::get('/messages/show/{id}',[MessageController::class, 'showMessage'])->name('showMessage')->middleware(['auth']);
Route::post('/messages/create/{id}',[MessageController::class, 'createMessage'])->name('createMessage')->middleware(['auth']);
// Route::post('/messages/create/{id}',[MessageController::class, 'checkCreateMessage'])->name('checkCreateMessage')->middleware(['auth']);




require __DIR__.'/auth.php';

