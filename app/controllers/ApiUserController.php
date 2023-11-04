<?php

class ApiUserController extends BaseController {

    /**
     * Expects input of a json object with 2 properties: OwnerID, PIN
     * 
     * @return type
     */
    public function login() {
        $cookie = null;
        $user = null;
        $user = null;
        $tooManyAttempts = false;
        $attemptDateDiff = null;
        $alreadyCompleted = false;
        $isBusinessOrFarmAcct = false;


        // Make sure credentials variable exists
        if (Input::has('loginInfo')) {
            $credentials = Input::get('loginInfo');
            // See if this user has attempted to log in before
            $attempt = UserAttempt::whereRaw('ip = ? AND OwnerID = ?', Array($_SERVER['REMOTE_ADDR'], $credentials['OwnerID']))->first();
            $curDate = new DateTime();

            // If they have attempted to log in before
            if ($attempt) {
                $lastAttemptDate = new DateTime($attempt->last_attempt_date);
                // Get difference (in days) from last login attempt to today
                $attemptDateDiff = $curDate->diff($lastAttemptDate)->format('%d');

                // If the difference is more than 1 day
                if ($attemptDateDiff >= 1) {
                    // Reset last attempt date to today and attempts count to 0
                    $attempt->last_attempt_date = $curDate->format('Y-m-d');
                    $attempt->attempts = 0;
                }
            } else {
                // Create new attempt
                $attempt = new UserAttempt();
                $attempt->ip = $_SERVER['REMOTE_ADDR'];
                $attempt->OwnerID = $credentials['OwnerID'];
                $attempt->attempts = 0;
                $attempt->last_attempt_date = $curDate->format('Y-m-d');
            }
            
            // If the amount of attempts for this person is >= max attempts allowed
            if ($attempt->attempts >= Config::get('app.maxattempts')) {
                // Flag as too many attempts
                $tooManyAttempts = true;
            } else {
                // Try logging in
                $user = PublicUser::whereRaw('`OwnerId` = ? AND `PIN` = ?', array($credentials['OwnerID'], $credentials['PIN']))->get()->first();
                
                if ($user) {
                    $changeRecordCount = Change::whereRaw('`OwnerID` = ?', Array($user->OwnerID))->count();
                    
                    if ($user->FileCode != '0') {
                        $isBusinessOrFarmAcct = true;
                    } else if ($changeRecordCount > 0 || $user->ReturnDate) {
                        // If the user has records in the change table, they've
                        // already filled out the online form
                        // If the user's record has a ReturnDate, they've already
                        // filled out a physical form
                        $alreadyCompleted = true;
                    } else {
                        // Create cookie if user is authenticated and able to log in
                        $cookie = Cookie::make(Config::get('app.cookie.user'), $user->OwnerID, Config::get('app.cookie.user.time'));
                    }
                } else {
                    // User credentials were incorrect, increment attempts
                    $attempt->attempts = $attempt->attempts + 1;
                    $attempt->last_attempt_date = $curDate->format('Y-m-d');
                }
            }

            // Save attempt record to database
            $browser = get_browser($_SERVER['HTTP_USER_AGENT']);

            $attempt->browser = $browser->browser . ' v' . $browser->version . ' | ' . $_SERVER['HTTP_USER_AGENT'];
            $attempt->save();
        }
        
        
        
        if ($cookie) {
            // Login was successful if cookie was created
            return Response::json('')->withCookie($cookie);
        } else if ($tooManyAttempts) {
            return Response::json(Array('attemptMsg' => 'Too many attempts today, please try again tomorrow'), 401);
        } else if ($isBusinessOrFarmAcct) {
            return Response::json('', 402);
        } else if ($alreadyCompleted) {
            return Response::json('', 400);
        } else {
            return Response::json(Array('attemptMsg' => (Config::get('app.maxattempts') - $attempt->attempts) . ' attempts left today'), 403);
        }
    }
    
    /**
     * Retrieves all details for the logged in user account
     * @return type
     */
    public function getUser() {
        // If cookie exists
        if (Cookie::has(Config::get('app.cookie.user'))) {
            $userId = Cookie::get(Config::get('app.cookie.user'));
            $user = PublicUser::find($userId);
            $vehicleItems = PublicUser::find($userId)->ppItems->toArray();
            $mobileItems = PublicUser::find($userId)->ppMobiles->toArray();
            $livestockItems = Array();
            if (Config::get('app.showCurrentLivestock')) {
                $livestockItems = PublicUser::find($userId)->ppLivestock->toArray();
            }
            $hvyEquipItems = Array();
            if (Config::get('app.showCurrentHeavyEquip')) {
                $hvyEquipItems = PublicUser::find($userId)->ppHvyEquip->toArray();
            }

            // Create array representation of $user so we can add another property to it
            $fullUser = $user->toArray();
            $allUserPpItems = array_merge($vehicleItems, $mobileItems, $hvyEquipItems, $livestockItems);
            //$newUserPpItems = Array();
            $userPpItemsVehicle = Array();
            $userPpItemsMobile = Array();
            $userPPItemsHeavyEquip = Array();
            $userPPItemsLivestock = Array();
            foreach ($allUserPpItems as $item) {                
                if (isset($item['MobileID'])) {
                    // Item is mobile home
                    $type = 'Mobile Home';
                    $mobileAc = $item['MobileAC'] ? 'YES' : 'NO';
                    //$desc =  . ' ' . $item['MobileMake'] . ' (' . $item['MobileWidth'] . '\' W  ' . $item['MobileLength'] . '\' L  AC: ' . $mobileAc . ')';
                    
                    $userPpItemsMobile[] = Array(
                        'year' => $item['MobileYear'],
                        'ac' => $mobileAc,
                        'width' => $item['MobileWidth'],
                        'length' => $item['MobileLength'],
                        'make' => $item['MobileMake'],
                        'type' => $type,
                        'MobileID' => isset($item['MobileID']) ? $item['MobileID'] : null
                    );
                } else if (isset($item['VehicleID'])) {
                    // Item is a vehicle
                    // Get type record for this personal property item
                    $type = '';
                    $needsVin = false;
                    $typeObj = PpTypes::find($item['VehicleType']);
                    if ($typeObj) {
                        // If type record was found, set the description to $type
                        $type = $typeObj->Description;
                    }
                    if ($item['VIN'] == null || $item['VIN'] == '') {
                        $needsVin = true;
                    } 
                    
                    $userPpItemsVehicle[] = Array(
                        'year' => $item['VehicleYear'],
                        'desc' => $item['VehicleDescription'],
                        'needsVin' => $needsVin,
                        'vin' => $item['VIN'],
                        'type' => $type,
                        'VehicleID' => isset($item['VehicleID']) ? $item['VehicleID'] : null
                    );
                } else if (isset($item['HvyEquipID'])) {
                    // Item is heavy equipment
                    // Get type record for this personal property item
                    $type = '';
                    $needsVin = false;
                    $typeObj = HvyEquipTypes::find($item['HvyEquipType']);
                    if ($typeObj) {
                        // If type record was found, set the description to $type
                        $type = $typeObj->Description;
                    }
                    if ($item['VIN'] == null || $item['VIN'] == '') {
                        $needsVin = true;
                    } 
                    
                    $userPPItemsHeavyEquip[] = Array(
                        'year' => $item['Year'],
                        'make' => $item['Make'],
                        'model' => $item['Model'],
                        'needsVin' => $needsVin,
                        'vin' => $item['VIN'],
                        'type' => $type,
                        'HvyEquipID' => isset($item['HvyEquipID']) ? $item['HvyEquipID'] : null
                    );
                    
                } else if (isset($item['OtherID'])) {
                    // This is livestock... does the field name need to be LivestockID? Will there be other tables with OtherID?
                    // Get type record for this personal property item
                    $type = '';
                    $typeObj = Livestock::find($item['OtherCode']);
                    if ($typeObj) {
                        // If type record was found, set the description to $type
                        $type = $typeObj->Description;
                    }
                    
                    $userPPItemsLivestock[] = Array(
                        'type' => $type,
                        'qty' => $item['OtherNumber'],
                        'origQty' => $item['OtherNumber'],
                        'OtherID' => isset($item['OtherID']) ? $item['OtherID'] : null
                    );
                }
                
                // Add to array of personal property items
                // This creates an array of all owned personal property in a common format
                /*$newUserPpItems[] = Array(
                    'desc' => $desc,
                    'needsVin' => $needsVin,
                    'vin' => '',
                    'type' => $type,
                    'subType' => $subType,
                    'VehicleID' => isset($item['VehicleID']) ? $item['VehicleID'] : null,
                    'MobileID' => isset($item['MobileID']) ? $item['MobileID'] : null
                );*/
            }
            $fullUser['ppItems']['VEHICLE'] = $userPpItemsVehicle;
            $fullUser['ppItems']['MOBILE'] = $userPpItemsMobile;
            $fullUser['ppItems']['HVYEQUIP'] = $userPPItemsHeavyEquip;
            $fullUser['ppItems']['LIVESTOCK'] = $userPPItemsLivestock;
            
            // Return the $fullUser array
            return Response::json($fullUser);
        } else {
            // Return 403 not authorized
            return Response::json('', 403);
        }
    }

    public function isLoggedIn() {
        if (Cookie::has(Config::get('app.cookie.user'))) {
            return Response::json('');
        } else {
            return Response::json('', 403);
        }
    }
    
    public function logout() {
        $cookie = Cookie::forget(Config::get('app.cookie.user'));
        return Response::json('')->withCookie($cookie);
    }

    /**
     * Returns array of vehicle item types (Truck, SUV, Airplane, etc
     * @return type
     */
    public function getVehicleTypes() {
        $newPpTypesArr = Array();

        // Gets all personal property types in the types table
        $ppTypes = PpTypes::all();

        foreach ($ppTypes as $ppType) {
            $newPpTypesArr[] = Array(
                'id' => $ppType['VehicleType'],
                'desc' => $ppType['Description']
            );
        }

        return Response::json($newPpTypesArr);
    }
    
    /**
     * Returns array of livestock item types (Horse, Goat, etc
     * @return type
     */
    public function getLivestockTypes() {
        $newPpTypesArr = Array();

        $allLivestockPp = Livestock::all()->toArray();
        foreach ($allLivestockPp as $livestockType) {
            $newPpTypesArr[] = Array(
                'id' => $livestockType['Code'],
                'desc' => $livestockType['Description']
            );
        }


        return Response::json($newPpTypesArr);
    }
    
    /**
     * Returns array of heavy equipment item types 
     * @return type
     */
    public function getHvyEquipTypes() {
        $newPpTypesArr = Array();

        $hvyEquipTypes = HvyEquipTypes::all()->toArray();
        foreach ($hvyEquipTypes as $hvyEquipType) {
            $newPpTypesArr[] = Array(
                'id' => $hvyEquipType['Code'],
                'desc' => $hvyEquipType['Description']
            );
        }


        return Response::json($newPpTypesArr);
    }

    /**
     * Get array of vehicle makes by vehicle type
     * @return type
     */
    public function getVehicleMakesByType() {
        $makes = Array();
        if (Input::has('vehicleTypeId')) {
            $vehicleTypeId = Input::get('vehicleTypeId');
            $makes = AllPersonalProperty::whereRaw('`VehicleType` = ?', Array($vehicleTypeId))->groupBy('STMAKE')->orderBy('STMAKE')->get(array('STMAKE'));
        }

        // gzip response to reduce size
        //ob_start("ob_gzhandler");
        
        // Return merged arrays
        return Response::json($makes);
    }
    
    /**
     * Get array of vehicle models by vehicle make
     * @return type
     */
    public function getVehicleModelsByMake() {
        $models = Array();
        if (Input::has('vehicleMake')) {
            $vehicleMake = Input::get('vehicleMake');
            $models = AllPersonalProperty::whereRaw('`STMAKE` = ?', Array($vehicleMake))->groupBy('STMODEL')->orderBy('STMODEL')->get(array('STMODEL'));
        }

        // gzip response to reduce size
        //ob_start("ob_gzhandler");
        
        // Return merged arrays
        return Response::json($models);
    }
	/**
     * Get array of vehicle body types by vehicle make and model
     * @return type
     */
    public function getVehicleBodysByModel() {
        $bodys = Array();
        if (Input::has('vehicleModel')) {
            $vehicleModel = Input::get('vehicleModel');
            $bodys = AllPersonalProperty::whereRaw('`STMODEL` = ?', Array($vehicleModel))->groupBy('STBODY')->orderBy('STBODY')->get(array('STBODY'));
        }

        // gzip response to reduce size
        //ob_start("ob_gzhandler");
        
        // Return merged arrays
        return Response::json($bodys);
    }
	

    public function logChanges() {
        $cookie = null;
        
        // Only log changes if user is logged in
        if (Cookie::has(Config::get('app.cookie.user'))) {
            // Make sure request has the proper inputs
            if (Input::has('changes') && Input::has('phone') && Input::has('agreementSignature')) {
                $changes = Input::get('changes');
                $phone = Input::get('phone');
                $agreementSignature = Input::get('agreementSignature');
                $email = Input::has('email') ? Input::get('email') : '';
                $changeObj = null;
                $ownerId = '';
                
                // For each change in the array, create a change record and save it
                foreach ($changes as $change) {
                    $ownerId = $change['OwnerID'];
                    $changeObj = new Change();
                    $changeObj->OwnerID = $change['OwnerID'];
                    $changeObj->TaxYear = $change['TaxYear'];
                    $changeObj->OwnerName = $change['OwnerName'];
                    $changeObj->change_type = $change['changeType'];
                    $changeObj->change_desc = $change['changeDesc'];
                    $curDate = date('Y-m-d');
                    $changeObj->date = $curDate;
                    $changeObj->phone = $phone;
                    $changeObj->signature = $agreementSignature;
                    $changeObj->email = $email;
                    if (Config::get('app.saveChanges')) {
                        $changeObj->save();
                    }
                }
                
                // Send email to user if email isn't blank
                if ($email) {
                    $emailData = Array();
                    $emailData['countyName'] = Config::get('app.countyname');
                    $emailData['acctNum'] = $ownerId;
                    $emailData['date'] = date('Y-m-d');
                    Mail::send('emails.thankyou', $emailData, function($message) use ($email, $agreementSignature) {
                        $message->subject('DO NOT REPLY - ' . Config::get('app.countyname') . ' Personal Property Assessment Completed');
                        $message->to($email, $agreementSignature);
                        $message->from(Config::get('app.emailFromAddr'), Config::get('app.emailSubjPrefix') . ' - ' . Config::get('app.countyname'));
                    });
                }
                
                // Log user out
                $cookie = Cookie::forget(Config::get('app.cookie.user'));
                return Response::json('')->withCookie($cookie);
            } else {
                return Response::json('', 401);
            }
        } else {
            return Response::json('', 403);
        }
    }

    public function updateDb() {
        if (Input::has('key') && (Input::get('key') == Config::get('app.updateDbKey'))) {
            // Set site to maintenance mode so no one can file while this is going on
            $maintMode = Setting::find('maintenance_mode');
            $maintMode->value = 1;
            $maintMode->save();

            $success = true;
            $result = '';

            // The local zip file of the web table backup
            $local_webtable_zip_file = storage_path() . '\\' . Config::get('app.webTablesFile') . '.sql.zip';
            // The local sql file of the web table backup
            $local_webtable_sql_file = storage_path() . '\\' . Config::get('app.webTablesFile') . '.sql';
            // ftp path to web table backup zip
            //$ftp_webtable_file = Config::get('app.ftp.remoteDir') . Config::get('app.webTablesFile') . '.sql.zip';
            $ftp_webtable_file = Config::get('app.webTablesFile') . '.sql.zip';

            // local web changes backup zip file
            $local_changes_zip_file = storage_path() . '\\' . Config::get('app.webchangesFileName') . '.sql.zip';
            // local web changes backup sql file
            $local_changes_sql_file = storage_path() . '\\' . Config::get('app.webchangesFileName') . '.sql';
            // Ftp path to web changes backup zip file
            //$ftp_changes_file = Config::get('app.ftp.remoteDir') . Config::get('app.webchangesFileName') . '.sql.zip';
            //$ftp_changes_file = Config::get('app.webchangesFileName') . '.sql.zip';

            $winscpCommandFilePath = storage_path() . '\\winscp\\' . Config::get('app.winscpScript');

            // Get web tables from rsync
            if ($success) {
                // Create winscp command file
                $winscpCommandFile = fopen($winscpCommandFilePath, 'w+');
                if ($winscpCommandFile) {
                    // If file creation was successful, write commands to it
                    $winscpCommands = '';
                    // Automatically abort script on errors
                    $winscpCommands .= 'option batch abort' . "\r\n"; 
                    // Disable overwrite confirmations that conflict with the previous
                    $winscpCommands .= 'option confirm off' . "\r\n"; 
                    // Connect using password
                    $winscpCommands .= 'open sftp://' . Config::get('app.ftp.user') . ':' . Config::get('app.ftp.pass') . '@' . Config::get('app.ftp.host') . ' -hostkey="' . Config::get('app.ftp.hostKey') . '"' . "\r\n"; 
                    // Change directory
                    $winscpCommands .= 'cd ' . Config::get('app.ftp.remoteDir') . "\r\n"; 
                    // Get web table file
                    $winscpCommands .= 'get ' . $ftp_webtable_file . ' ' . storage_path() . '\\' . "\r\n"; 
                    // Close connection
                    $winscpCommands .= 'close' . "\r\n"; 
                    // exit winscp
                    $winscpCommands .= 'exit' . "\r\n"; 
                    fwrite($winscpCommandFile, $winscpCommands);
                    fclose($winscpCommandFile);

                    // Run winscp using the command file we created
                    $exitCode = 0;
                    //die(storage_path());
                    system(storage_path() . '\\winscp\\winscp.com /script=' . $winscpCommandFilePath, $exitcode);
                    // Delete winscp commands file
                    unlink($winscpCommandFilePath);
                    if ($exitCode != 0) {
                        $result = 'Exit code from winscp was not 0';
                        $success = false;
                    }
                } else {
                    $result = 'Could not create winscp command file';
                    $success = false;
                }


            }

            // Unzip web tables archive
            if ($success) {
                $zip = new ZipArchive();
                $result = $zip->open($local_webtable_zip_file);
                if ($result === true) {
                    // zip file was opened
                    $zip->extractTo(storage_path());
                } else {
                    $result = 'web table zip archive could not be opened';
                    $success = false;
                }
            }

            // Restore from web tables sql file
            if ($success) {
                $returnCode = 0;
                system('"' . Config::get('app.mysql.bindir') . '\mysql.exe" --user=' . Config::get('database.connections.mysql.username') . ' --password=' . Config::get('database.connections.mysql.password') . ' ' . Config::get('database.connections.mysql.database') . ' < "' . $local_webtable_sql_file . '"', $returnCode);

                if ($returnCode != 0) {
                    $result = 'Failed to restore webtables from .sql file';
                    $success = false;
                }
            }

            // Update changes table to set the 'done' flag
            // on records whos corresponding Owner record has a return date
            if ($success) {
                // Get all change records where done is false
                $changes = Change::whereRaw('done <> ?', Array(1))->get();
                // Iterate through change records
                $changes->each(function($changeModel) {
                    // Get user that change belongs to
                    $user = PublicUser::where('OwnerID', '=', $changeModel->OwnerID)->first();
                    // If the return date is set, then the change has been updated in the system
                    if ($user && $user->ProcessedDate) {
                        // Set the change record to 'done' and save
                        $changeModel->done = true;
                        $changeModel->save();
                    }
                });
            }

            // Use mysqldump.exe to create backup of changes table
            if ($success) {
                $returnCode = 0;
                system('"' . Config::get('app.mysql.bindir') . '\mysqldump.exe" --user=' . Config::get('database.connections.mysql.username') . ' --password=' . Config::get('database.connections.mysql.password') . ' --opt ' . Config::get('database.connections.mysql.database') . ' webchanges > "' . $local_changes_sql_file . '"', $returnCode);
                if ($returnCode != 0) {
                    $result = 'Failed to create changes table dump on web server';
                    $success = false;
                }
            }

            // Zip the changes table mysqldump file
            if ($success) {
                $zip = new ZipArchive();

                $res = $zip->open($local_changes_zip_file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
                if ($res === TRUE) {
                    $zip->addFile($local_changes_sql_file, Config::get('app.webchangesFileName') . '.sql');
                    $zip->close();
                } else {
                    $result = 'Error zipping web changes backup sql file';
                    $success = false;
                }
            }


            // Upload the zipped changes file to ftp server
            if ($success) {
                // Create winscp command file
                $winscpCommandFile = fopen($winscpCommandFilePath, 'w+');
                if ($winscpCommandFile) {
                    // If file creation was successful, write commands to it
                    $winscpCommands = '';
                    // Automatically abort script on errors
                    $winscpCommands .= 'option batch abort' . "\r\n"; 
                    // Disable overwrite confirmations that conflict with the previous
                    $winscpCommands .= 'option confirm off' . "\r\n";
                    // Connect using password
                    $winscpCommands .= 'open sftp://' . Config::get('app.ftp.user') . ':' . Config::get('app.ftp.pass') . '@' . Config::get('app.ftp.host') . ' -hostkey="' . Config::get('app.ftp.hostKey') . '"' . "\r\n"; 
                    // Change directory
                    $winscpCommands .= 'cd ' . Config::get('app.ftp.remoteDir') . "\r\n";
                    // Get web table file
                    $winscpCommands .= 'put ' . $local_changes_zip_file . "\r\n";
                    // Close connection
                    $winscpCommands .= 'close' . "\r\n";
                    // exit winscp
                    $winscpCommands .= 'exit' . "\r\n";
                    fwrite($winscpCommandFile, $winscpCommands);
                    fclose($winscpCommandFile);

                    // Run winscp using the command file we created
                    $exitCode = 0;
                    system(storage_path() . '\\winscp\\winscp.com /script=' . $winscpCommandFilePath, $exitCode);
                    // Delete winscp commands file
                    unlink($winscpCommandFilePath);
                    if ($exitCode != 0) {
                        $result = 'Exit code from winscp upload was not 0';
                        $success = false;
                    }
                } else {
                    $result = 'Could not create winscp command file for upload';
                    $success = false;
                }
            }

            $maintMode->value = 0;
            $maintMode->save();

            ob_clean();

            if ($success) {
                $result = 'Web server database updated successfully and changes database uploaded to ftp server';
            } else {
                return Response::json($result,500);
            }

            // return result string
            return $result;
        } else {
            return Response::json('Not authorized to run this web service method',401);
        }
    }

}