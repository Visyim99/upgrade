@extends('layout')

@section('htmlTag')
    <html lang="en" ng-app="ppApp">
@stop

@section('content')
    <div class="centerWrapper">
        <div id="loginBox" class="ui-corner-all" c-load>
            {{$message}}
        </div>
    </div>
@stop
