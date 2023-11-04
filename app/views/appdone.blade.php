<div id="mainAppBox" class="ui-corner-all" c-load ng-controller="DoneController">
    <p>
        Thank you!
    </p>
    <p ng-show="email">
        A confirmation email has been sent to @{{email}}
    </p>
    <p>
        Please print this page for your records.
    </p>
    <br/>
    <br/>
    Acct#: @{{acctNum}}
    <br/>
    Completed online file form on: @{{date}}
</div>
