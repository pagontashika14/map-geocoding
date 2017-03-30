@extends('layouts.app')

@section('content')
    <ggmap style="width: 100%; height: 100%;"
    	@click="mapClick"
    ></ggmap>
    <address-point style="width: 320px; height: 440px;"
    	:addresses="addresses"
    	:lat="lat"
    	:lng="lng"
    ></address-point>
@endsection

@push('scripts')
    <script>
    	var GOOGLE_GEOCODING_URL = "{{ config('constants.GOOGLE_GEOCODING_URL') }}";
    	var GOOGLE_MAP_API_KEY = "{{ config('constants.GOOGLE_MAP_API_KEY') }}";
    </script>
@endpush