<div id="loginBox" class="ui-corner-all" c-load ng-controller="LoginController">
    Please log in by entering the account # and PIN located on the personal property assessment sheet that you received in the mail.
    <br/>
    <br/>
    <span class="strong">{{{ $accttypescaveat }}}</span>
    <br/>
    <br/>
    <form name="loginForm" novalidate>
        <div class="formRow">
            <label for="acctNum">Account #:</label>
            <input id="acctNum" type="text" class="ui-corner-all ui-spinner" value="@{{pInfo.loginInfo.OwnerID}}" ng-model="pInfo.loginInfo.OwnerID" required />
        </div>
        <div class="formRow">
            <label for="pin">PIN:</label>
            <input id="pin" type="text" class="ui-corner-all ui-spinner" value="@{{pInfo.loginInfo.PIN}}" ng-model="pInfo.loginInfo.PIN" required />
        </div>
        <input type="submit" value="Log In" ng-click="login(loginForm)">
    </form>
    <br/>
    <br/>
    <span class="strong">** If you file online, please do not return the assessment sheet through the mail. Thank you. **</span> 
</div>

