@extends('layout')

@section('htmlTag')
    <html lang="en" ng-app="ppApp">
@stop

@section('content')
    <div id="mainAppBox" class="ui-corner-all">
        <div id="loginBox" class="ui-corner-all" c-load>
            You are using an older version of Internet Explorer which isn't supported. 
            Please <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">update Internet Explorer</a> 
            or <a href="https://www.google.com/intl/en/chrome/browser/">download Google Chrome</a> 
            or <a href="http://www.mozilla.org/en-US/firefox/new/">download Firefox</a>. Thank you.
        </div>
    </div>
@stop
