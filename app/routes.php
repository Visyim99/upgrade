<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*Route::get('/', function()
{
    return View::make('home');
});*/

Route::get('/', 'AppController@index');
Route::get('/pplogintemplate', 'AppController@loginTemplate');
Route::get('/ppinfotemplate', 'AppController@infoTemplate');
/*Route::get('/ppaddresstemplate', 'AppController@addressTemplate');
Route::get('/ppmanagementtemplate', 'AppController@managementTemplate');
Route::get('/ppfinalizetemplate', 'AppController@finalizeTemplate');*/
Route::get('/ppdonetemplate', 'AppController@doneTemplate');
//Route::get('/pprealestatechangestemplate', 'AppController@realEstateChangesTemplate');

/*Route::any('/admin', 'AppController@adminLogin');
Route::any('/admin/dashboard', 'AppController@adminDashboard');
Route::any('/admin/report', 'AppController@adminReport');
Route::any('/admin/confirm', 'AppController@confirm');*/

Route::post('/api/user/login', 'ApiUserController@login');
Route::post('/api/user/getUser', 'ApiUserController@getUser');
Route::post('/api/user/isLoggedIn', 'ApiUserController@isLoggedIn');
Route::post('/api/user/logout', 'ApiUserController@logout');
Route::post('/api/user/getVehicleTypes', 'ApiUserController@getVehicleTypes');
Route::post('/api/user/getLivestockTypes', 'ApiUserController@getLivestockTypes');
Route::post('/api/user/getHvyEquipTypes', 'ApiUserController@getHvyEquipTypes');
//Route::post('/api/user/getAllAvailablePpItems', 'ApiUserController@getAllAvailablePpItems');
Route::post('/api/user/getVehicleMakesByType', 'ApiUserController@getVehicleMakesByType');
Route::post('/api/user/getVehicleModelsByMake', 'ApiUserController@getVehicleModelsByMake');
Route::post('/api/user/getVehicleBodysByModel', 'ApiUserController@getVehicleBodysByModel');
/*Route::post('/api/user/getOptionsForType', 'ApiUserController@getOptionsForType');
Route::post('/api/user/getSalvage', 'ApiUserController@getSalvage');*/
Route::post('/api/user/logChanges', 'ApiUserController@logChanges');

/*Route::any('/api/admin/noChangeRpt', 'ApiUserController@noChangeRpt');*/
Route::any('/api/admin/updateDb', 'ApiUserController@updateDb');

// Can use this route to create a new password
Route::any('/createPw', function () {
    echo 'test';
    //echo hash('sha256', '');
});

// route for testing bits of code
Route::any('/test', function () {
    /*$zip = new ZipArchive();
    $filename = storage_path() . "/test.zip";

    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
        exit("cannot open <$filename>\n");
    }

    $zip->addFromString("testfilephp.txt" . time(), "#1 This is a test string added as testfilephp.txt.\n");
    $zip->addFromString("testfilephp2.txt" . time(), "#2 This is a test string added as testfilephp2.txt.\n");
    //$zip->addFile($thisdir . "/too.php","/testfromfile.php");
    echo "numfiles: " . $zip->numFiles . "\n";
    echo "status:" . $zip->status . "\n";
    $zip->close();*/
    /*if (fopen(storage_path() . '\\test.txt', 'r+') === false) {
        echo "failure man";
    }*/
    $zip = new ZipArchive();

    $res = $zip->open(storage_path().'\asdf.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
    if ($res === true) {
        $zip->addFile(storage_path().'\\test.txt', 'test.txt');
        //$zip->filename = storage_path() . '\asdf.zip';
        echo $zip->filename;
        $zip->close();
        echo 'ok '.$zip->filename.' endfilename';
        echo 'created: '.storage_path().'\\test.txt';
    } else {

        echo 'failed ';
    }
    /*$data = Array();
    // Some basic data that all pages need
    $data['countyname'] = Config::get('app.countyname');
    $data['title'] = $data['countyname'] . ' - Online Personal Property Declaration Filing';
    $data['canonicalUrl'] = null;
    $data['metaDescription'] = "Cole County - Online Personal Property Declaration Filing";
    $data['usevehicledropdowns'] = Config::get('app.usevehicledropdowns');
    return Response::view('oldbrowser', $data);*/
    /*$data = Array();
    Mail::send('emails.thankyou', $data, function($message)
    {
        $message->subject('Test email');
        $message->to('danrbritt@gmail.com', 'Dan');
        $message->from('d.britt@villagis.com', 'Dan2');
    });*/
});
