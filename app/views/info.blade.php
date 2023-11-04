<div id="mainAppBox" class="ui-corner-all" c-load ng-controller="InfoController">
    <form name="ppDecForm" id="ppDecForm">
        <h1>Address Information:</h1>
        <div class="ppSection">
            <p>
                Please review the following information for accuracy. Make any changes necessary to reflect your status as of January 1st, 2022
            </p>
            <div class="lineItem">
                <input type="checkbox" ng-model="pInfo.addrMovedOutOfCounty" /> I no longer live in or have personal property in {{$countyname}}
            </div>
            <div ng-show="pInfo.addrMovedOutOfCounty">
                <div class="lineItem inputItem">
                    <label>Name: </label>@{{pInfo.user.Name}}</p>
                </div>
                <div class="lineItem inputItem">
                    <label>Date moved:</label>
                    <input type="text" class="datePicker ui-corner-all ui-spinner" ng-model="pInfo.addrMovedOutOfCountyDate" ng-required="pInfo.addrMovedOutOfCounty" />
                </div>
                <p>
                    Please let your local assessor know you've moved to their county.
                </p>
                
            </div>
            <div ng-show="!pInfo.addrMovedOutOfCounty">
                <div class="lineItem">
                    <input type="checkbox" ng-model="pInfo.addrNameChanged" /> My name has changed
                </div>
                <div class="lineItem inputItem">
                    <label>Name: </label><input type="text" maxlength="50" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrNameChanged" ng-model="pInfo.user.Name" ng-required="!pInfo.addrMovedOutOfCounty && pInfo.addrNameChanged" />
                </div>
                <div class="lineItem">
                    <input type="checkbox" ng-model="pInfo.addrChanged" /> My address has changed
                </div>
                <div class="lineItem inputItem">
                    <label>Address1: </label><input type="text" maxlength="50" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrChanged" ng-model="pInfo.user.Address1" ng-required="!pInfo.addrMovedOutOfCounty && pInfo.addrChanged" />
                </div>
                <div class="lineItem inputItem">    
                    <label>Address2: </label><input type="text" maxlength="50" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrChanged" ng-model="pInfo.user.Address2" />
                </div>
                <div class="lineItem inputItem">      
                    <label>City: </label><input type="text" maxlength="50" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrChanged" ng-model="pInfo.user.City" ng-required="!pInfo.addrMovedOutOfCounty && pInfo.addrChanged" />
                </div>
                <div class="lineItem inputItem">      
                    <label>State: </label><input type="text" maxlength="2" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrChanged" ng-model="pInfo.user.State" ng-required="!pInfo.addrMovedOutOfCounty && pInfo.addrChanged" />
                </div>
                <div class="lineItem inputItem">      
                    <label>ZIP: </label><input type="text" maxlength="10" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrChanged" ng-model="pInfo.user.Zip" ng-required="!pInfo.addrMovedOutOfCounty && pInfo.addrChanged" />
                </div>
                <div ng-show="pInfo.addrChanged">
                    <div class="lineItem inputItem">
                        <label>Date moved:</label>
                        <input type="text" class="datePicker ui-corner-all ui-spinner" ng-model="pInfo.addrDateMoved" ng-required="pInfo.addrChanged" />
                    </div>
                </div>
                <div class="lineItem">
                    <input type="checkbox" ng-model="pInfo.addrLocChanged" /> My property location has changed
                </div>
                <div class="lineItem inputItem">
                    <label>Location Addr1: </label><input type="text" maxlength="50" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrLocChanged" ng-model="pInfo.user.Location1" ng-required="!pInfo.addrMovedOutOfCounty && pInfo.addrLocChanged" />
                </div>
                <div class="lineItem inputItem">
                    <label>Location Addr2: </label><input type="text" maxlength="50" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrLocChanged" ng-model="pInfo.user.Location2" />
                </div>
                <div class="lineItem">
                    <input type="checkbox" ng-model="pInfo.addrLesseeChanged" /> Lessee information has changed
                </div>
                <div class="lineItem inputItem">
                    <label>Lessee: </label><input type="text" maxlength="50" class="ui-corner-all ui-spinner" ng-disabled="!pInfo.addrLesseeChanged" ng-model="pInfo.user.Lessee" ng-required="!pInfo.addrMovedOutOfCounty && pInfo.addrLesseeChanged" />
                </div>
            </div>
        </div>
        <hr ng-show="!pInfo.addrMovedOutOfCounty">
        <h1 ng-show="!pInfo.addrMovedOutOfCounty">Personal Property Information:</h1>
        <div class="ppSection" ng-show="!pInfo.addrMovedOutOfCounty">
            <p class="strong">
                Assessments declared after March 1st will be subject to penalty per RSMO 137.345 shown on table below:
            </p>
            <table id="penaltyTable">
                <tr>
                    <th>Assessed<br/>Value</th>
                    <th>Penalty</th>
                    <th>Assessed<br/>Value</th>
                    <th>Penalty</th>
                    <th>Assessed<br/>Value</th>
                    <th>Penalty</th>
                </tr>
                <tr>
                    <td>0 - 1,000</td>
                    <td class="penaltyAmt">$15</td>
                    <td>4,001 - 5,000</td>
                    <td class="penaltyAmt">$55</td>
                    <td>7,001 - 8,000</td>
                    <td class="penaltyAmt">$85</td>
                </tr>
                <tr>
                    <td>1,001 - 2,000</td>
                    <td class="penaltyAmt">$25</td>
                    <td>5,001 - 6,000</td>
                    <td class="penaltyAmt">$65</td>
                    <td>8,001 - 9,000</td>
                    <td class="penaltyAmt">$95</td>
                </tr>
                <tr>
                    <td>2,001 - 3,000</td>
                    <td class="penaltyAmt">$35</td>
                    <td>6,001 - 7,000</td>
                    <td class="penaltyAmt">$75</td>
                    <td>9,001 &amp; Above</td>
                    <td class="penaltyAmt">$105</td>
                </tr>
                <tr>
                    <td>3,001 - 4,000</td>
                    <td class="penaltyAmt">$45</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <h2>Current Personal Property</h2>
            <div class="ppSection">
                <p>
                    These are the personal property items our records indicate that you already own. If you no longer own any of these items, 
                    please remove them by clicking the X on the right side of the row.
                </p>
                <h3>Vehicles</h3>
                <div class="ppSection">
                    <span ng-show="pInfo.user.ppItems.VEHICLE.length == 0">NONE</span>
                    <table class="ppTable" ng-show="pInfo.user.ppItems.VEHICLE.length > 0">
                        <tr>
                            <th>
                                TYPE
                            </th>
                            <th>
                                YEAR
                            </th>
                            <th>
                                DESCRIPTION
                            </th>
							<th>
                                SALVAGE TITLE
                            </th>
                            <th>
                                VIN
                            </th>
                            <th class="removeBox">
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.user.ppItems.VEHICLE">
                            <td>
                                @{{ppItem.type}}
                            </td>
                            <td>
                                @{{ppItem.year}}
                            </td>
                            <td>
                                @{{ppItem.desc}}
                            </td>
							<td>
                                @{{ppItem.salvage}}
                            </td>
                            <td>
                                <input ng-show="ppItem.needsVin" type="text" class="ui-corner-all ui-spinner vinInput" ng-model="ppItem.vin" />
                                <label ng-show="!ppItem.needsVin">@{{ppItem.vin}}</label>
                            </td>
                            <td class="removeBox" ng-click="removeCurVehicle(ppItem)">
                                X
                            </td>
                        </tr>
                    </table>
                </div>
                <h3>Mobile Homes</h3>
                <div class="ppSection">
                    <span ng-show="pInfo.user.ppItems.MOBILE.length == 0">NONE</span>
                    <table class="ppTable" ng-show="pInfo.user.ppItems.MOBILE.length > 0">
                        <tr>
                            <th>
                                YEAR
                            </th>
                            <th>
                                MAKE
                            </th>
                            <th>
                                A/C?
                            </th>
                            <th>
                                W (ft)
                            </th>
                            <th>
                                L (ft)
                            </th>
                            <th class="removeBox">
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.user.ppItems.MOBILE">
                            <td>
                                @{{ppItem.year}}
                            </td>
                            <td>
                                @{{ppItem.make}}
                            </td>
                            <td>
                                @{{ppItem.ac}}
                            </td>
                            <td>
                                @{{ppItem.width}}
                            </td>
                            <td>
                                @{{ppItem.length}}
                            </td>
                            <td class="removeBox" ng-click="removeCurMobileHome(ppItem)">
                                X
                            </td>
                        </tr>
                    </table>
                </div>
                <h3 ng-show="pInfo.showCurrentHeavyEquip">Heavy Equipment</h3>
                <div class="ppSection" ng-show="pInfo.showCurrentHeavyEquip">
                    <span ng-show="pInfo.user.ppItems.HVYEQUIP.length == 0">NONE</span>
                    <table class="ppTable" ng-show="pInfo.user.ppItems.HVYEQUIP.length > 0">
                        <tr>
                            <th>
                                TYPE
                            </th>
                            <th>
                                YEAR
                            </th>
                            <th>
                                MAKE
                            </th>
                            <th>
                                MODEL
                            </th>
                            <th>
                                VIN
                            </th>
                            <th class="removeBox">
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.user.ppItems.HVYEQUIP">
                            <td>
                                @{{ppItem.type}}
                            </td>
                            <td>
                                @{{ppItem.year}}
                            </td>
                            <td>
                                @{{ppItem.make}}
                            </td>
                            <td>
                                @{{ppItem.model}}
                            </td>
                            <td>
                                <input ng-show="ppItem.needsVin" type="text" class="ui-corner-all ui-spinner vinInput" ng-model="ppItem.vin" />
                                <label ng-show="!ppItem.needsVin">@{{ppItem.vin}}</label>
                            </td>
                            <td class="removeBox" ng-click="removeCurHvyEquip(ppItem)">
                                X
                            </td>
                        </tr>
                    </table>
                </div>
                <h3 ng-show="pInfo.showCurrentLivestock">Livestock</h3>
                <div class="ppSection" ng-show="pInfo.showCurrentLivestock">
                    <span ng-show="pInfo.user.ppItems.LIVESTOCK.length == 0">NONE</span>
                    <span ng-show="pInfo.user.ppItems.LIVESTOCK.length > 0">* Please make sure the qty for each livestock type is correct. If it's not, please enter the new qty.</span>
                    <table class="ppTable" ng-show="pInfo.user.ppItems.LIVESTOCK.length > 0">
                        <tr>
                            <th>
                                TYPE
                            </th>
                            <th>
                                QTY
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.user.ppItems.LIVESTOCK">
                            <td>
                                @{{ppItem.type}}
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner vinInput" required ng-model="ppItem.qty" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <h2>New Personal Property</h2>
            <div class="ppSection">
                <p>
                    If you have any new personal property, add it below by clicking on the green button with the "+" symbol
                </p>
                <h3>Vehicles</h3>
                <div class="ppSection">
                    <table class="ppTable">
                        <tr>
                            <th width="70">
                                TYPE
                            </th>
                            <th width="40">
                                YEAR
                            </th>
                            <th width="90">
                                MAKE
                            </th>
                            <th width="90">
                               MODEL
                            </th>
							<th width="50">
                               SERIES/BODY
                            </th>
							<th width="20">
                               SALVAGE TITLE?
                            </th>
                            <th>
                                VIN
                            </th>
                            <th class="removeBox">
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.addedVehicles">
                            <td>
                                <select ng-model="ppItem.type" ng-change="vehicleTypeChange(ppItem)" required ng-options="type.desc for type in pInfo.vehicleTypes"> 
                                </select>
                                <!-- <select ng-model="ppItem.type" ng-change="vehicleTypeChange(ppItem)" required ng-options="(type.id + '|' + type.desc) as type.desc for type in pInfo.ppTypes"> 
                                </select>-->
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.year" required >
                            </td>
                            <td>
								<select ng-model="ppItem.make" ng-change="vehicleMakeChange(ppItem)" required ng-options="make.STMAKE for make in ppItem.makeOptions"> 
                                </select>
                                <!--<select ng-model="ppItem.make" ng-change="vehicleMakeChange(ppItem)" required ng-options="make.STMAKE for make in ppItem.makeOptions" ng-show="ppItem.type && pInfo.useVehicleDropdowns"> 
                                </select>
                                <!--<input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.make" required ng-show="!pInfo.useVehicleDropdowns" >-->
                            </td>
                            <td>
								<select ng-model="ppItem.model" ng-change="vehicleModelChange(ppItem)" required ng-options="model.STMODEL for model in ppItem.modelOptions"> 
                                </select>
                                <!--<select ng-model="ppItem.model" required ng-options="model.STMODEL for model in ppItem.modelOptions" ng-show="ppItem.make && pInfo.useVehicleDropdowns"> 
                                </select>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.model" required ng-show="!pInfo.useVehicleDropdowns" >-->
                            </td>
							<td>
								<select ng-model="ppItem.body" required ng-options="body.STBODY for body in ppItem.bodyOptions"> 
                                </select>
								<!--<select ng-model="ppItem.body" ng-change="vehicleBodyChange(ppItem)" required ng-options="body.STBODY for body in ppItem.bodyOptions"> 
                                </select>-->
                                <!--<select ng-model="ppItem.body" required ng-options="model.STBODY for body in ppItem.bodyOptions" ng-show="ppItem.body && pInfo.useVehicleDropdowns"> 
                                </select>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.body" required ng-show="!pInfo.useVehicleDropdowns" >-->
                            </td>
							<td>
                                 <select ng-model="ppItem.salvage" required> 
									<option value="YES">YES</option>
                                    <option value="NO">NO</option>
								</select>
                            </td>
						
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.VIN" >
                            </td>
                            <td class="removeBox" ng-click="removeAddedVehicle(ppItem)">
                                X
                            </td>
                        </tr>
                    </table>
                    <div class="addPPItem ui-corner-all" ng-click="addVehicleItem()">
                        +
                    </div>
                </div>
                    
                <h3>Mobile Homes</h3>
                <div class="ppSection">
                    <table class="ppTable">
                        <tr>
                            <th width="70">
                                YEAR
                            </th>
                            <th width="170">
                                TYPE/DESC
                            </th>
                            <th width="45">
                                W (ft)
                            </th>
                            <th width="45">
                               L (ft)
                            </th>
                            <th width="55">
                                A/C?
                            </th>
                            <th>
                                LOCATION
                            </th>
                            <th>
                                LAND OWNER NAME
                            </th>
                            <th class="removeBox">
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.addedMobileHomes">
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.year" required >
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.desc" required >
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.width" required >
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.length" required >
                            </td>
                            <td>
                                <select ng-model="ppItem.ac" required>
                                    <option value="true">YES</option>
                                    <option value="true">NO</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.location" required >
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.landowner" required >
                            </td>
                            <td class="removeBox" ng-click="removeAddedMobileHome(ppItem)">
                                X
                            </td>
                        </tr>
                    </table>
                    <div class="addPPItem ui-corner-all" ng-click="addMobileHomeItem()">
                        +
                    </div>
                </div>
                    
                <h3 ng-show="pInfo.allowAddLivestock">Livestock</h3>
                <div class="ppSection" ng-show="pInfo.allowAddLivestock">
                    <table class="ppTable">
                        <tr>
                            <th width="90">
                                TYPE
                            </th>
                            <th>
                                QTY
                            </th>
                            <th class="removeBox">
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.addedLivestock">
                            <td>
                                <select ng-model="ppItem.type" required ng-options="type.desc for type in pInfo.livestockTypes"> 
                                </select>
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.qty" required >
                            </td>
                            <td class="removeBox" ng-click="removeAddedLivestock(ppItem)">
                                X
                            </td>
                        </tr>
                    </table>
                    <div class="addPPItem ui-corner-all" ng-click="addLivestockItem()">
                        +
                    </div>
                </div>
                
                <h3 ng-show="pInfo.allowAddHeavyEquip">Heavy Equipment</h3>
                <div class="ppSection" ng-show="pInfo.allowAddHeavyEquip">
                    <table class="ppTable">
                        <tr>
                            <th width="100">
                                TYPE/DESC
                            </th>
                            <th width="70">
                                YEAR
                            </th>
                            <th>
                                MAKE
                            </th>
                            <th>
                                MODEL
                            </th>
                            <th width="300">
                                VIN
                            </th>
                            <th class="removeBox">
                            </th>
                        </tr>
                        <tr ng-repeat="ppItem in pInfo.addedHeavyEquip">
                            <td>
                                <select ng-model="ppItem.type" required ng-options="type.desc for type in pInfo.hvyEquipTypes"> 
                                </select>
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.year" required >
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.make" required >
                            </td>
                            <td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.model" required >
                            </td><td>
                                <input type="text" class="ui-corner-all ui-spinner" ng-model="ppItem.vin" required >
                            </td>
                            <td class="removeBox" ng-click="removeAddedHvyEquip()">
                                X
                            </td>
                        </tr>
                    </table>
                    <div class="addPPItem ui-corner-all" ng-click="addHvyEquipItem()">
                        +
                    </div>
                </div>
            </div>
        </div>
        <hr ng-show="!pInfo.addrMovedOutOfCounty && pInfo.showRealEstateChangesSection">
        <h1 ng-show="!pInfo.addrMovedOutOfCounty  && pInfo.showRealEstateChangesSection">Real Estate Changes:</h1>
        <div class="ppSection" ng-show="!pInfo.addrMovedOutOfCounty && pInfo.showRealEstateChangesSection">
            <div class="lineItem">
                <input type="checkbox" ng-model="pInfo.realEstateChangesChecked"> There has been new construction or improvements made to my real estate.
            </div>
            <div class="lineItem inputItem" ng-show="pInfo.realEstateChangesChecked">
                <label>Type of construction:</label>
                <input type="text" class="ui-corner-all ui-spinner" ng-model="pInfo.realEstateChanges.type" ng-required="pInfo.realEstateChangesChecked" />
            </div>
            <div class="lineItem, inputItem" ng-show="pInfo.realEstateChangesChecked">
                <label>Date of completion:</label>
                <input type="text" class="datePicker ui-corner-all ui-spinner" ng-model="pInfo.realEstateChanges.completionDate" ng-required="pInfo.realEstateChangesChecked" />
            </div>
        </div>
        <hr>
        <h1>E-Mail Confirmation:</h1>
        <div class="ppSection">
            <p>
                If you would like to receive a confirmation, please enter your E-Mail address below:
            </p>
            <div class="lineItem inputItem">
                <label>E-Mail:</label>
                <input type="text" class="ui-corner-all ui-spinner" ng-model="pInfo.useremail" />
            </div>
            <!--<div class="lineItem, inputItem" ng-show="pInfo.realEstateChangesChecked">
                <label>Date of completion:</label>
                <input type="text" class="datePicker ui-corner-all ui-spinner" ng-model="pInfo.realEstateChanges.completionDate" ng-required="pInfo.realEstateChangesChecked" />
            </div>-->
        </div>
        <hr>
        <div class="lineItem">
            <input type="checkbox" ng-model="pInfo.agreementChecked" required> I do hereby certify that the foregoing list contains a true and correct statement of all the tangible personal property 
            made taxable by the laws of the state of Missouri, which I owned or which I had under my charge or management on the first day of January, {{date('Y')}}. 
            I further certify that I have not sent or taken or caused to be sent or take any property out of this state to avoid taxation.
        </div>
        <div class="lineItem inputItem">
            <label>Electronic Signature:</label>
            <input type="text" class="ui-corner-all ui-spinner" ng-model="pInfo.agreementSignature" required /> Enter the name of the person filling out this form
        </div>
        <div class="lineItem inputItem">
            <label for="phone">Phone #:</label>
            <input id="phone" type="text" class="ui-corner-all ui-spinner" value="@{{pInfo.userphone}}" ng-model="pInfo.userphone" required />  Ex: XXX-XXX-XXXX
        </div>
        <br>
        <br>
        <input type="button" value="Submit" ng-click="finalizeSubmit(ppDecForm)" ng-disabled="pInfo.finalizeSubmitDisabled || !pInfo.agreementChecked" />
        <input type="button" ng-click="cancelClick()" value="Cancel Without Saving" />
    </form>
</div>
