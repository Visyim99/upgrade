/* This section is needed to support IE8
 * IE8 is missing some javascript functions that JQuery uses
 * This section adds these functions
 */
'use strict';

// Add ECMA262-5 method binding if not supported natively
//
if (!('bind' in Function.prototype)) {
    Function.prototype.bind= function(owner) {
        var that= this;
        if (arguments.length<=1) {
            return function() {
                return that.apply(owner, arguments);
            };
        } else {
            var args= Array.prototype.slice.call(arguments, 1);
            return function() {
                return that.apply(owner, arguments.length===0? args : args.concat(Array.prototype.slice.call(arguments)));
            };
        }
    };
}

// Add ECMA262-5 string trim if not supported natively
//
if (!('trim' in String.prototype)) {
    String.prototype.trim= function() {
        return this.replace(/^\s+/, '').replace(/\s+$/, '');
    };
}

// Add ECMA262-5 Array methods if not supported natively
//
if (!('indexOf' in Array.prototype)) {
    Array.prototype.indexOf= function(find, i /*opt*/) {
        if (i===undefined) i= 0;
        if (i<0) i+= this.length;
        if (i<0) i= 0;
        for (var n= this.length; i<n; i++)
            if (i in this && this[i]===find)
                return i;
        return -1;
    };
}
if (!('lastIndexOf' in Array.prototype)) {
    Array.prototype.lastIndexOf= function(find, i /*opt*/) {
        if (i===undefined) i= this.length-1;
        if (i<0) i+= this.length;
        if (i>this.length-1) i= this.length-1;
        for (i++; i-->0;) /* i++ because from-argument is sadly inclusive */
            if (i in this && this[i]===find)
                return i;
        return -1;
    };
}
if (!('forEach' in Array.prototype)) {
    Array.prototype.forEach= function(action, that /*opt*/) {
        for (var i= 0, n= this.length; i<n; i++)
            if (i in this)
                action.call(that, this[i], i, this);
    };
}
if (!('map' in Array.prototype)) {
    Array.prototype.map= function(mapper, that /*opt*/) {
        var other= new Array(this.length);
        for (var i= 0, n= this.length; i<n; i++)
            if (i in this)
                other[i]= mapper.call(that, this[i], i, this);
        return other;
    };
}
if (!('filter' in Array.prototype)) {
    Array.prototype.filter= function(filter, that /*opt*/) {
        var other= [], v;
        for (var i=0, n= this.length; i<n; i++)
            if (i in this && filter.call(that, v= this[i], i, this))
                other.push(v);
        return other;
    };
}
if (!('every' in Array.prototype)) {
    Array.prototype.every= function(tester, that /*opt*/) {
        for (var i= 0, n= this.length; i<n; i++)
            if (i in this && !tester.call(that, this[i], i, this))
                return false;
        return true;
    };
}
if (!('some' in Array.prototype)) {
    Array.prototype.some= function(tester, that /*opt*/) {
        for (var i= 0, n= this.length; i<n; i++)
            if (i in this && tester.call(that, this[i], i, this))
                return true;
        return false;
    };
}

/** End IE8 Compatibility section **/


// Create new angular module that includes ngRoute
var ppApp = angular.module('ppApp', ['ngRoute']);

// Configuration information for ppApp
ppApp.config(['$routeProvider', '$provide',
    function($routeProvider, $provide) {
        // Set up routes
        // templateUrl is the relative url that returns the html for the view
        // from the server
        $routeProvider.
            when('/login', {
                templateUrl: 'pplogintemplate'
            }).
            when('/ppinfo', {
                templateUrl: 'ppinfotemplate'
            }).
            when('/finalize', {
                templateUrl: 'ppfinalizetemplate'
            }).
            when('/done', {
                templateUrl: 'ppdonetemplate'
            }).
            otherwise({
                redirectTo: '/login'
            });
        
        // Create angularjs service to hold info that should persist between routes
        $provide.factory('PersistentInfo', [function() {
            return {
                // Holds user object - user object set after user is logged in
                user : {},
                loginInfo : {
                    OwnerID : "",
                    PIN : ""
                },
                userphone : '',
                useremail : '',
                /* Settings from config file on server */
                useVehicleDropdowns : false,
                showCurrentLivestock : false,
                showCurrentHeavyEquip : false,
                allowAddLivestock : false,
                allowAddHeavyEquip : false,
                showRealEstateChangesSection : false,
                // Array of change objects
                // Will be sent to server when filing is complete
                // Built in the FinalizeController
                changes : [],       
                // Arrays containing items that were removed from the personal 
                // property the user already had
                removedVehicles : [],
                removedMobileHomes : [],
                removedHvyEquip : [],
                // Arrays of personal property items that have been added
                addedVehicles : [],
                addedMobileHomes : [],
                addedLivestock : [],
                addedHeavyEquip : [],
                // Arrays for personal property types
                vehicleTypes : [],
                livestockTypes : [],
                hvyEquipTypes : [],
                // Arrays of vehicle makes and models
                vehicleMakes : [],
                vehicleModels : [],
				vehicleBodys : [],
                // State of final agreement checkbox
                agreementChecked: false,
                // Final agreement signature
                agreementSignature: '',
                // State of real estate changes checkbox
                realEstateChangesChecked: false,
                // Info about real estate changes
                realEstateChanges: {
                    type: '',
                    completionDate: ''
                },
                // Checkbox states for contact info page
                addrMovedOutOfCounty: false,
                addrMovedOutOfCountyDate: '',
                addrNameChanged: false,
                addrChanged: false,
                addrLocChanged: false,
                addrLesseeChanged: false,
                addrDateMoved: '',
                // Name field is tied to user.Name, so this is the variable that
                // Holds the original name on the acct
                origName: '',         
                finalizeSubmitDisabled: false,
                ResetData: function() {
                    this.user = {};
                    this.loginInfo = {
                        OwnerID : "",
                        PIN : ""
                    };
                    this.changes = [];
                    //this.removedItems = [];
                    this.removedMobileHomes = [];
                    this.removedVehicles = [];
                    this.removedHvyEquip = [];
                    this.addedVehicles = [];
                    this.addedMobileHomes = [];
                    this.addedLivestock = [];
                    this.addedHeavyEquip = [];
                    this.agreementChecked = false;
                    this.realEstateChangesChecked = false;
                    this.realEstateChanges = {
                        type: '',
                        completionDate: ''
                    };
                    this.addrMovedOutOfCounty = false;
                    this.addrNameChanged = false;
                    this.addrChanged = false;
                    this.addrLocChanged = false;
                    this.addrLesseeChanged = false;
                    this.origName = '';
                    this.agreementChecked = false;
                    this.agreementSignature = '';
                    this.userphone = '';
                    this.useremail = '';
                }
            };
        }]);
    
        // Create angularjs service for web service calls
        $provide.factory('PpWebServices', ['PersistentInfo', '$http', '$q', '$window', '$location', function(pInfo, $http, $q, $window, $location) {
            var obj = {      
                // Logs user in and runs successCallback function if login is a success
                // On error, alerts user that authentication failed
                //login : function(successCallback) {
                login : function(successCallback) {
                    $http.post($window.baseUrl + '/api/user/login', {loginInfo: pInfo.loginInfo}).
                        success(function(data, status, headers, config) {
                            //successCallback();
                            $location.path('/ppinfo');
                        }).
                        error(function(data, status, headers, config) {
                            // Data returned contains attemptMsg property on error 403 and 401
                    
                            // 403 - Incorrect credentials
                            if (status == 403) {
                                alert('Login credentials incorrect. Please try again.' + "\n" + data.attemptMsg);
                            // 401 - Too many attempts today
                            } else if (status == 401) {
                                alert('Login credentials incorrect. ' + "\n" + data.attemptMsg);
                            // 400 - Already completed
                            } else if (status == 400) {
                                alert('You\'ve already completed the online filing form or sent in a physical declaration form. If you need to make changes, please contact the ' + countyName + ' Assessor\'s Office at ' + countyPhone);
                            // 402 - Business acct
                            } else if (status == 402) {
                                alert('Your account is not the correct type: ' + acctTypesCaveat)
                            } else {
                                alert('There was an unexpected error while logging in. Please try again later.');
                            }
                        });
                },
                logout : function() {
                    $http.post($window.baseUrl + '/api/user/logout').
                        success(function(data, status, headers, config) {
                            //successCallback();
                            //$location.path('/login');
                        }).
                        error(function(data, status, headers, config) {
                            //$location.path('/login');
                        });
                },
                // Retrieves user data if user is logged in
                // If not logged in, redirect to /login route
                getUser : function() {
                    $http.post($window.baseUrl + '/api/user/getUser').
                        success(function(data, status, headers, config) {
                            pInfo.user = data;
                            pInfo.origName = pInfo.user.Name;
                        }).
                        error(function(data, status, headers, config) {
                            // 403 - Unauthorized - session expired
                            $location.path('/login');
                            if (status === 403) {
                                alert('Session expired. Please log in again.');
                            } else {
                                alert('There was an unexpected error retreiving user data. Please try again later.');
                            }
                        });
                },
                // Gets all personal property base types
                getVehicleTypes : function() {
                    $http.post($window.baseUrl + '/api/user/getVehicleTypes').
                        success(function(data, status, headers, config) {
                            pInfo.vehicleTypes = data;
                        }).
                        error(function(data, status, headers, config) {
                            alert('There was an unexpected error retrieving vehicle types. Please try again later.');
                        });
                },
                getLivestockTypes : function() {
                    $http.post($window.baseUrl + '/api/user/getLivestockTypes').
                        success(function(data, status, headers, config) {
                            pInfo.livestockTypes = data;
                        }).
                        error(function(data, status, headers, config) {
                            alert('There was an unexpected error retrieving livestock types. Please try again later.');
                        });
                },
                getHvyEquipTypes : function() {
                    $http.post($window.baseUrl + '/api/user/getHvyEquipTypes').
                        success(function(data, status, headers, config) {
                            pInfo.hvyEquipTypes = data;
                        }).
                        error(function(data, status, headers, config) {
                            alert('There was an unexpected error retrieving heavy equipment types. Please try again later.');
                        });
                },
                getVehicleMakesByType : function(vehicleItem) {
                    $http.post($window.baseUrl + '/api/user/getVehicleMakesByType', {vehicleTypeId : vehicleItem.type.id}).
                        success(function(data, status, headers, config) {
                            pInfo.vehicleMakes[vehicleItem.type.id] = data;
                            vehicleItem.makeOptions = pInfo.vehicleMakes[vehicleItem.type.id];
                            // JQuery command to close loading dialog
                            //$("#loadingDialog").dialog('close');
                        }).
                        error(function(data, status, headers, config) {
                            // JQuery command to close loading dialog
                            //$("#loadingDialog").dialog('close');
                            alert('There was an unexpected error retreiving vehicle makes. Please try again later.');
                        });
                },
                getVehicleModelsByMake : function(vehicleItem) {
                    $http.post($window.baseUrl + '/api/user/getVehicleModelsByMake', {vehicleMake : vehicleItem.make.STMAKE}).
                        success(function(data, status, headers, config) {
                            pInfo.vehicleModels[vehicleItem.make.STMAKE] = data;
                            vehicleItem.modelOptions = pInfo.vehicleModels[vehicleItem.make.STMAKE]
                            // JQuery command to close loading dialog
                            //$("#loadingDialog").dialog('close');
                        }).
                        error(function(data, status, headers, config) {
                            // JQuery command to close loading dialog
                            //$("#loadingDialog").dialog('close');
                            alert('There was an unexpected error retreiving vehicle models. Please try again later.');
                        });
                },
				getVehicleBodysByModel : function(vehicleItem) {
                    $http.post($window.baseUrl + '/api/user/getVehicleBodysByModel', {vehicleModel : vehicleItem.model.STMODEL}).
                        success(function(data, status, headers, config) {
                            pInfo.vehicleBodys[vehicleItem.model.STMODEL] = data;
                            vehicleItem.bodyOptions = pInfo.vehicleBodys[vehicleItem.model.STMODEL]
                            // JQuery command to close loading dialog
                            //$("#loadingDialog").dialog('close');
                        }).
                        error(function(data, status, headers, config) {
                            // JQuery command to close loading dialog
                            //$("#loadingDialog").dialog('close');
                            alert('There was an unexpected error retreiving vehicle bodys. Please try again later.');
                        });
                },
                // Sends the changes array out to be saved to the database     
                // Also sends the phone number to be entered for the change records
                logChanges : function(changes, phone, agreementSignature, email) {
                    $http.post($window.baseUrl + '/api/user/logChanges', {changes : changes, phone: phone, agreementSignature: agreementSignature, email: email}).
                        success(function(data, status, headers, config) {
                            $("#loadingDialog").dialog('close');
                            $location.path('/done');
                        }).
                        error(function(data, status, headers, config) {
                            $("#loadingDialog").dialog('close');
                            alert('There was an unexpected error updating the account changes. Please try again later.');
                            pInfo.finalizeSubmitDisabled = false;
                        });
                }
            };   
            return obj;
        }]); 
        
    }]);

// Sets up directive "customLoad" to load JQuery UI stuff
ppApp.directive('cLoad', function() {
    return function (scope, element, attr) {
        element.ready(function() {
            // Turns all submit and button type input elements to JQueryUI buttons
            $('input[type=submit], input[type=button]').button();
            // Creates JQueryUI dialog #loadingDialog div
            // Can remove loading dialog I think...
            $("#loadingDialog").dialog({
                modal: true,
                dialogClass: 'loadingDialog',
                closeOnEscape: false,
                position: 'center',
                autoOpen: false,
                draggable: false
            });
            // Creates JQueryUI datepicker
            $('.datePicker').datepicker();
        });
    }; 
});

// Controller for login route
ppApp.controller('LoginController', ['$scope', '$http', '$window', '$location', 'PpWebServices', 'PersistentInfo', function($scope, $http, $window, $location, ppWebServices, pInfo) {
    $("html body").scrollTop(0);
    // Reset the pInfo service data.
    pInfo.ResetData();
    
    $scope.pInfo = pInfo;
    
    // Called on login button click
    $scope.login = function(form) {
        if (form.$valid) {
            //ppWebServices.login(ppWebServices.getUser); 
            ppWebServices.login();
        }
    };
}]);

// Controller for info route
ppApp.controller('InfoController', ['$scope', '$http', '$window', '$location', 'PpWebServices', 'PersistentInfo', function($scope, $http, $window, $location, ppWebServices, pInfo) {
    $("html body").scrollTop(0);
    $scope.pInfo = pInfo;
    // If user obj is not set, try retrieving it
    if (!pInfo.user.Name) {
        ppWebServices.getUser();
        //$location.path('/login');
    } 
    
    // Get vehicle and livestock types
    ppWebServices.getVehicleTypes();
    if (allowAddLivestock) {
        ppWebServices.getLivestockTypes();
    }
    if (allowAddHeavyEquip) {
        ppWebServices.getHvyEquipTypes();
    }
    
    // Config from server. Used to show selects or text boxes for vehicles
    $scope.pInfo.useVehicleDropdowns = useVehicleDropdowns;
    $scope.pInfo.showCurrentLivestock = showCurrentLivestock;
    $scope.pInfo.showCurrentHeavyEquip = showCurrentHeavyEquip;
    $scope.pInfo.allowAddLivestock = allowAddLivestock;
    $scope.pInfo.allowAddHeavyEquip = allowAddHeavyEquip;
    $scope.pInfo.showRealEstateChangesSection = showRealEstateChangesSection;
    
    // Called when clicking the remove button on current vehicle
    $scope.removeCurVehicle = function(item) {
        var confirmed = $window.confirm("Are you sure you want to remove this vehicle?");
        if (confirmed) {
            // Get index of item in the users ppitems list
            var index = $scope.pInfo.user.ppItems.VEHICLE.indexOf(item);
           
            // Remove item from user.ppItems
            $scope.pInfo.user.ppItems.VEHICLE.splice(index, 1);

            // Add to removedItems array
            $scope.pInfo.removedVehicles.push(item);
        }
    };
    
    $scope.removeCurMobileHome = function(item) {
        var confirmed = $window.confirm("Are you sure you want to remove this mobile home?");
        if (confirmed) {
            // Get index of item in the users ppitems list
            var index = $scope.pInfo.user.ppItems.MOBILE.indexOf(item);

            // Remove item from user.ppItems
            $scope.pInfo.user.ppItems.MOBILE.splice(index, 1);

            // Add to removedItems array
            $scope.pInfo.removedMobileHomes.push(item);
        }
    };
    
    $scope.removeCurHvyEquip = function(item) {
        var confirmed = $window.confirm("Are you sure you want to remove this heavy equipment item?");
        if (confirmed) {
            // Get index of item in the users ppitems list
            var index = $scope.pInfo.user.ppItems.HVYEQUIP.indexOf(item);

            // Remove item from user.ppItems
            $scope.pInfo.user.ppItems.HVYEQUIP.splice(index, 1);

            // Add to removedItems array
            $scope.pInfo.removedHvyEquip.push(item);
        }
    };
    
    // Called when the add new vehicle button is clicked
    $scope.addVehicleItem = function() {
        // Adds new item to the end of the array
        pInfo.addedVehicles.push({
            type : '',
            make : '',
            makeOptions : [],
            model : '',
            modelOptions : [],
			body : '',
			bodyOptions : [],
            year : '',
            VIN : '',
			salvage : ''
        });
    };
        
    // Called when vehicle type is selected
    $scope.vehicleTypeChange = function(vehicleItem) {
        // Type changed, clear make and model
        vehicleItem.make = '';
        vehicleItem.model = '';
		vehicleItem.body = '';
        // If we haven't already retrieved the makes for this typeId, request
        // from server
        if (pInfo.vehicleMakes.indexOf(vehicleItem.type.id) === -1) {
            ppWebServices.getVehicleMakesByType(vehicleItem);
        } else {
            // We already retrieved these in the past, use what we already have
            vehicleItem.makeOptions = pInfo.vehicleMakes[vehicleItem.type.id];
        }
    };
    
    // Called when vehicle make is selected
    $scope.vehicleMakeChange = function(vehicleItem) {
        // Make changed, clear model
        vehicleItem.model = '';
        // If we haven't already retrieved the models for this make, request
        // from server
        if (pInfo.vehicleModels.indexOf(vehicleItem.make.STMAKE) === -1) {
            ppWebServices.getVehicleModelsByMake(vehicleItem);
        } else {
            // We already retrieved these in the past, use what we alraedy have
            vehicleItem.modelOptions = pInfo.vehicleModels[vehicleItem.make.STMAKE];
        }
        
    };
	
	 // Called when vehicle model is selected
    $scope.vehicleModelChange = function(vehicleItem) {
        // Make changed, clear body
        vehicleItem.body = '';
        // If we haven't already retrieved the models for this make, request
        // from server
        if (pInfo.vehicleBodys.indexOf(vehicleItem.model.STMODEL) === -1) {
            ppWebServices.getVehicleBodysByModel(vehicleItem);
        } else {
            // We already retrieved these in the past, use what we alraedy have
            vehicleItem.bodyOptions = pInfo.vehicleBodys[vehicleItem.model.STMODEL];
        }
        
    };
    
    // Called when clicking remove button on added vehicle
    $scope.removeAddedVehicle = function(item) {
        var confirmed = $window.confirm("Are you sure you want to remove this vehicle?");
        if (confirmed) {
            // Get index of item in vehicle array
            var index = $scope.pInfo.addedVehicles.indexOf(item);
            // Remove from array
            $scope.pInfo.addedVehicles.splice(index, 1);
        }
    };
    
    // Called when the add new mobile home button is clicked
    $scope.addMobileHomeItem = function() {
        // Adds new item to the end of the array
        pInfo.addedMobileHomes.push({
            year : '',
            desc : '',
            width : '',
            length : '',
            ac : '',
            location : '',
            landowner : ''
        });
    };
    
    // Called when clicking remove button on added mobile home
    $scope.removeAddedMobileHome = function(item) {
        var confirmed = $window.confirm("Are you sure you want to remove this mobile home?");
        if (confirmed) {
            // Get index of item in vehicle array
            var index = $scope.pInfo.addedMobileHomes.indexOf(item);
            // Remove from array
            $scope.pInfo.addedMobileHomes.splice(index, 1);
        }
    };
    
    // Called when the add new livestock button is clicked
    $scope.addLivestockItem = function() {
        // Adds new item to the end of the array
        pInfo.addedLivestock.push({
            type : '',
            qty : ''
        });
    };
    
    // Called when clicking remove button on added livestock
    $scope.removeAddedLivestock = function(item) {
        var confirmed = $window.confirm("Are you sure you want to remove this livestock item?");
        if (confirmed) {
            // Get index of item in vehicle array
            var index = $scope.pInfo.addedLivestock.indexOf(item);
            // Remove from array
            $scope.pInfo.addedLivestock.splice(index, 1);
        }
    };
    
    // Called when the add new heavy equipment button is clicked
    $scope.addHvyEquipItem = function() {
        // Adds new item to the end of the array
        pInfo.addedHeavyEquip.push({
            type : '',
            year : '',
            make : '',
            model : '',
            vin : ''
        });
    };
    
    // Called when clicking remove button on added heavy equipment
    $scope.removeAddedHvyEquip = function(item) {
        var confirmed = $window.confirm("Are you sure you want to remove this heavy equipment item?");
        if (confirmed) {
            // Get index of item in vehicle array
            var index = $scope.pInfo.addedHeavyEquip.indexOf(item);
            // Remove from array
            $scope.pInfo.addedHeavyEquip.splice(index, 1);
        }
    };

    $scope.cancelClick = function() {
        ppWebServices.logout();
        $location.path('/login');
    };
    
   
        
    // called when submitting finalize form
    $scope.finalizeSubmit = function(ppDecForm) {
        // Validate entire form
        pInfo.changes = [];
        if (ppDecForm.$valid) {
            if (pInfo.agreementChecked) {
                // Build changes list
                
                // Add change if moved out of county
                if (pInfo.addrMovedOutOfCounty) {
                    pInfo.changes.push({
                        OwnerID: pInfo.user.OwnerID,
                        TaxYear: pInfo.user.TaxYear,
                        OwnerName: pInfo.origName,
                        changeType: 'OUT_OF_COUNTY',
                        changeDesc: 'Moved out of county on: ' + pInfo.addrMovedOutOfCountyDate
                    });
                }
                
                // Add change record for name change
                if (pInfo.addrNameChanged) {
                    pInfo.changes.push({
                        OwnerID: pInfo.user.OwnerID,
                        TaxYear: pInfo.user.TaxYear,
                        OwnerName: pInfo.origName,
                        changeType: 'NAME_CHANGED',
                        changeDesc: 'Name changed to: ' + pInfo.user.Name
                    });
                }
                
                // Add change record for addr change
                if (pInfo.addrChanged) {
                    pInfo.changes.push({
                        OwnerID: pInfo.user.OwnerID,
                        TaxYear: pInfo.user.TaxYear,
                        OwnerName: pInfo.origName,
                        changeType: 'ADDR_CHANGE',
                        changeDesc: 'New Address: ' + 'Addr1: ' + pInfo.user.Address1 + ' Addr2: ' + pInfo.user.Address2 + ' City: ' + pInfo.user.City +
                                ' State: ' + pInfo.user.State + ' ZIP: ' + pInfo.user.Zip + ' DATE Moved: ' + pInfo.addrMovedOutOfCountyDate
                    });
                }
                
                // Add change record for location change
                if (pInfo.addrLocChanged) {
                    pInfo.changes.push({
                        OwnerID: pInfo.user.OwnerID,
                        TaxYear: pInfo.user.TaxYear,
                        OwnerName: pInfo.origName,
                        changeType: 'LOC_CHANGE',
                        changeDesc: 'New Location: ' + 'Addr1: ' + pInfo.user.Location1 + ' Addr2: ' + pInfo.user.Location2
                    });
                }
                
                // Add change record for lessee change
                if (pInfo.addrLesseeChanged) {
                    pInfo.changes.push({
                        OwnerID: pInfo.user.OwnerID,
                        TaxYear: pInfo.user.TaxYear,
                        OwnerName: pInfo.origName,
                        changeType: 'LESSEE_CHANGE',
                        changeDesc: 'New Lessee Info: ' + 'Lessee: ' + pInfo.user.Lessee
                    });
                }
                
                // Add change records for removed vehicles
                if (pInfo.removedVehicles.length > 0) {
                    for (var i = 0; i < pInfo.removedVehicles.length; i++) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'PP_ITEM_REMOVED',
                            changeDesc: 'Item removed: Type: ' + pInfo.removedVehicles[i].type + ' Year: ' + pInfo.removedVehicles[i].year + '  Desc: ' + pInfo.removedVehicles[i].desc
                        });
                    }
                }
                
                // Add change records for removed mobile homes
                if (pInfo.removedMobileHomes.length > 0) {
                    for (var i = 0; i < pInfo.removedMobileHomes.length; i++) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'PP_ITEM_REMOVED',
                            changeDesc: 'Item removed: Type: Mobile Home  Year: ' + pInfo.removedMobileHomes[i].year + '  Make: ' + 
                                    pInfo.removedMobileHomes[i].make + '  A/C: ' + pInfo.removedMobileHomes[i].ac + '  Width: ' + 
                                    pInfo.removedMobileHomes[i].width + '  Length: ' + pInfo.removedMobileHomes[i].length
                        });
                    }
                }
                
                // Add change records for removed heavy equipment
                if (pInfo.removedHvyEquip.length > 0) {
                    for (var i = 0; i < pInfo.removedHvyEquip.length; i++) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'PP_ITEM_REMOVED',
                            changeDesc: 'Item removed:  Heavy Equipment   Type: ' + pInfo.removedHvyEquip[i].type + ' Year: ' + pInfo.removedHvyEquip[i].year + 
                                    '  Make: ' + pInfo.removedHvyEquip[i].make + '  Model: ' + pInfo.removedHvyEquip[i].model
                        });
                    }
                }
                
                // Add change records if current livestock qty changes
                if (pInfo.user.ppItems.LIVESTOCK.length > 0) {
                    for (var i = 0; i < pInfo.user.ppItems.LIVESTOCK.length; i++) {
                        // Only make change record if qty != origQty
                        if (pInfo.user.ppItems.LIVESTOCK[i].qty != pInfo.user.ppItems.LIVESTOCK[i].origQty) {
                            // Diff is qty entered - origQty
                            //var qtyDiff = pInfo.user.ppItems.LIVESTOCK[i].qty - pInfo.user.ppItems.LIVESTOCK[i].origQty;
                            pInfo.changes.push({
                                OwnerID: pInfo.user.OwnerID,
                                TaxYear: pInfo.user.TaxYear,
                                OwnerName: pInfo.origName,
                                changeType: 'PP_ITEM_CHANGED',
                                changeDesc: 'Item changed: Livestock   Type: ' + pInfo.user.ppItems.LIVESTOCK[i].type + '  Qty Changed from ' + 
                                        pInfo.user.ppItems.LIVESTOCK[i].origQty + ' to ' + pInfo.user.ppItems.LIVESTOCK[i].qty
                            });
                        }
                    }
                }                    
                
                // Add change records for added vehicles
                if (pInfo.addedVehicles.length > 0) {
                    for (var i = 0; i < pInfo.addedVehicles.length; i++) {
                        // Make and model can either be object or string
                        // depending on if we're using dropdowns or not
                        // If obj, get STMAKE value
                        var make = typeof pInfo.addedVehicles[i].make == 'string' ? pInfo.addedVehicles[i].make : pInfo.addedVehicles[i].make.STMAKE;
                        var model = typeof pInfo.addedVehicles[i].model == 'string' ? pInfo.addedVehicles[i].model : pInfo.addedVehicles[i].model.STMODEL;
						var body = typeof pInfo.addedVehicles[i].body == 'string' ? pInfo.addedVehicles[i].body : pInfo.addedVehicles[i].body.STBODY;
						//var salvage = typeof pInfo.addedVehicles[i].salvage == 'string' ? pInfo.addedVehicles[i].salvage : pInfo.addedVehicles[i].salvage.STBODY;
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'PP_ITEM_ADDED',
                            changeDesc: 'Item added: Vehicle   Type: ' + pInfo.addedVehicles[i].type.desc + '  Make: ' + make + '  Model: ' + model + '  Body: ' + body + 
                                    '  Year: ' + pInfo.addedVehicles[i].year + '  SALVAGE: ' + pInfo.addedVehicles[i].salvage + '  VIN: ' + pInfo.addedVehicles[i].VIN
                        });
                    }
                }
                
                // Add change records for added mobile homes
                if (pInfo.addedMobileHomes.length > 0) {
                    for (var i = 0; i < pInfo.addedMobileHomes.length; i++) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'PP_ITEM_ADDED',
                            changeDesc: 'Item added: Type: Mobile Home  Year: ' + pInfo.addedMobileHomes[i].year + '  Desc: ' + pInfo.addedMobileHomes[i].desc + 
                                    '  Width: ' + pInfo.addedMobileHomes[i].width + 'ft  Length: ' + pInfo.addedMobileHomes[i].length + 'ft  A/C?: ' +
                                    pInfo.addedMobileHomes[i].ac + '  Location: ' + pInfo.addedMobileHomes[i].location + '  Land Owner Name: ' + 
                                    pInfo.addedMobileHomes[i].landowner
                        });
                    }
                }
                
                // Add change records for added livestock
                if (pInfo.addedLivestock.length > 0) {
                    for (var i = 0; i < pInfo.addedLivestock.length; i++) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'PP_ITEM_ADDED',
                            changeDesc: 'Item added: Livestock   Type: ' + pInfo.addedLivestock[i].type.desc + '  Qty: ' + pInfo.addedLivestock[i].qty
                        });
                    }
                }
                
                // Add change records for added heavy equipment
                if (pInfo.addedHeavyEquip.length > 0) {
                    for (var i = 0; i < pInfo.addedHeavyEquip.length; i++) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'PP_ITEM_ADDED',
                            changeDesc: 'Item added: Heavy Equipment   Type: ' + pInfo.addedHeavyEquip[i].type.desc + '  Year: ' + pInfo.addedHeavyEquip[i].year + '  Make: ' + pInfo.addedHeavyEquip[i].make + '  Model: ' + pInfo.addedHeavyEquip[i].model
                        });
                    }
                }
                
                // add change record if vin was added to current vehicle item
                for (var i = 0; i < pInfo.user.ppItems.VEHICLE.length; i++) {
                    var ppItem = pInfo.user.ppItems.VEHICLE[i];
                    // If the vehicle is flagged as needing a VIN, and a VIN
                    // was actually entered
                    if (ppItem.needsVin && ppItem.vin) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'VIN_ADDED',
                            changeDesc: 'Vehicle VIN added: ' + ' item: Type: ' + ppItem.type + '  Year: ' + ppItem.year + '  Desc: ' + ppItem.desc + '  VIN: ' + ppItem.vin
                        });
                    }
                }
                
                // add change record if vin was added to current heavy equipment item
                for (var i = 0; i < pInfo.user.ppItems.HVYEQUIP.length; i++) {
                    var ppItem = pInfo.user.ppItems.HVYEQUIP[i];
                    // If the vehicle is flagged as needing a VIN, and a VIN
                    // was actually entered
                    if (ppItem.needsVin && ppItem.vin) {
                        pInfo.changes.push({
                            OwnerID: pInfo.user.OwnerID,
                            TaxYear: pInfo.user.TaxYear,
                            OwnerName: pInfo.origName,
                            changeType: 'VIN_ADDED',
                            changeDesc: 'Heavy Equipment VIN added: ' + ' item: Type: ' + ppItem.type + '  Year: ' + ppItem.year + '  Make: ' + ppItem.make + '  Model: ' + ppItem.model
                        });
                    }
                }
                
                // Add change record if user had real estate changes
                if (pInfo.realEstateChangesChecked) {
                    pInfo.changes.push({
                        OwnerID: pInfo.user.OwnerID,
                        TaxYear: pInfo.user.TaxYear,
                        OwnerName: pInfo.origName,
                        changeType: 'REAL_ESTATE_CHANGES',
                        changeDesc: pInfo.realEstateChanges.type + ' - completed on: ' + pInfo.realEstateChanges.completionDate
                    });
                }

                // Add change record if there were no changes at all
                if (pInfo.changes.length === 0) {
                    pInfo.changes.push({
                        OwnerID: pInfo.user.OwnerID,
                        TaxYear: pInfo.user.TaxYear,
                        OwnerName: pInfo.origName,
                        changeType: 'NO_CHANGE',
                        changeDesc: 'No changes were made to this account'
                    });
                }
                
                //$location.path('/done');
                //alert(JSON.stringify(pInfo.changes, null, 4));
                $("#loadingDialog").dialog('open');
                ppWebServices.logChanges(pInfo.changes, pInfo.userphone, pInfo.agreementSignature, pInfo.useremail);
                 
                
            } else {
                alert('Form incomplete. Please accept the agreement.');
            }
        } else {
            alert('Form incomplete. Please fill out any fields with a red border.');
        }
    };
}]);

// Controller for done route
ppApp.controller('DoneController', ['$scope', '$http', '$window', '$location', 'PpWebServices', 'PersistentInfo', '$filter', function($scope, $http, $window, $location, ppWebServices, pInfo, $filter) {
    $("html body").scrollTop(0);
    // Save acctNum and Date
    $scope.acctNum = pInfo.user.OwnerID;
    $scope.date = $filter('date')(new Date(), 'yyyy-MM-dd');
    $scope.email = pInfo.useremail;
    // Clear user obj since they are done with the form
    pInfo.user = [];
}]);