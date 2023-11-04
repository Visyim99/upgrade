<meta charset="UTF-8">
<title>{{ $title }}</title>
<!--[if lte IE 8]>
    {{ HTML::script('js/lib/json2.js') }}
<![endif]-->
<script>
    var baseUrl = "{{url('/')}}";
    var countyName = "{{$countyname}}";
    var countyPhone = "{{$countyPhone}}";
    var acctTypesCaveat = "{{$acctTypesCaveat}}";
    var useVehicleDropdowns = "{{$usevehicledropdowns}}";
    var showCurrentLivestock = "{{$showCurrentLivestock}}";
    var showCurrentHeavyEquip = "{{$showCurrentHeavyEquip}}";
    var allowAddLivestock = "{{$allowAddLivestock}}";
    var allowAddHeavyEquip = "{{$allowAddHeavyEquip}}";
    var showRealEstateChangesSection = "{{$showRealEstateChangesSection}}";
</script>
<!--{{ HTML::style('css/jquery-ui-1.10.3/cupertino/jquery-ui-1.10.3.custom.min.css') }}-->
{{ HTML::style('css/jquery-ui-1.10.3/smoothness/jquery-ui.min.css') }}
{{ HTML::style('css/jquery-ui-1.10.3/smoothness/jquery-ui.structure.min.css') }}
{{ HTML::style('css/jquery-ui-1.10.3/smoothness/jquery-ui.theme.min.css') }}
{{ HTML::style('css/styles.css') }}
{{ HTML::script('js/lib/jquery-1.10.2/jquery-1.10.2.min.js') }}
{{ HTML::script('js/lib/jquery-ui-1.10.3/jquery-ui-1.10.3.custom.min.js') }}
{{ HTML::script('js/lib/angularjs-1.2.3/angular.min.js') }}
{{ HTML::script('js/lib/angularjs-1.2.3/angular-route.min.js') }}
<!-- Adding timestamp as a parameter to the request for the main js file 
makes it where the file is downloaded to the client every time. This avoids
clients loading an older cached version instead of seeing the new version with
updates -->
{{ HTML::script('js/ppapp.js?' . time()) }}
@if ($canonicalUrl)
    <link rel="canonical" href="{{ $canonicalUrl }}" />
@endif
<meta name="description" content="{{ $metaDescription }}" />
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />