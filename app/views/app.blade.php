@extends('layout')

@section('htmlTag')
    <html lang="en" id="ng-app" ng-app="ppApp">
@stop

@section('content')
    <div ng-view>
    </div>
@stop
