@if($image->info)
    @if($image->info->camera || $image->info->lens || $image->info->focal_length || $image->info->shutter_speed || $image->info->aperture || $image->info->iso || $image->info->longitude || $image->taken_at)
        <h3 class="content-heading">{{ t('EXIF Data') }}</h3>
    @endif
    <div class="clearfix exif">
        @if(isset($image->info->camera))
            <p><strong>{{ t('Model') }} </strong>{{ $image->info->camera }}</p>
        @endif
        @if(isset($image->info->lens))
            <p><strong>{{ t('Lens') }} </strong>{{ $image->info->lens }}</p>
        @endif
        @if(isset($image->info->focal_length))
            <p><strong>{{ t('Focal Length') }} </strong>{{ $image->info->focal_length }}</p>
        @endif
        @if(isset($image->info->shutter_speed))
            <p><strong>{{ t('Shutter Speed') }} </strong>{{ $image->info->shutter_speed }}</p>
        @endif
        @if(isset($image->info->aperture))
            <p><strong>{{ t('Aperture') }} </strong>{{ $image->info->aperture }}</p>
        @endif
        @if(isset($image->info->iso))
            <p><strong>{{ t('ISO') }} </strong>{{ $image->info->iso }}</p>
        @endif
        @if(isset($image->info->taken_at))
            <p><strong>{{ t('Taken At') }} </strong>{{ $image->info->taken_at->toDayDateTimeString() }}</p>
        @endif
        @if(isset($image->info->longitude) && isset($image->info->latitude))
            <div id="gmaps" style="height:250px;"></div>
        @endif
    </div>
@endif

@section('extrafooter')
    @if(isset($image->info->longitude) && isset($image->info->latitude))
        {!! HTML::script('//maps.google.com/maps/api/js?sensor=false&libraries=places&language=en') !!}
        {!! HTML::script('static/js/gmaps.min.js')  !!}
        <script type="text/javascript">
            var map;
            $(function () {
                map = new GMaps({
                    div: '#gmaps',
                    lat: {{ $image->info->latitude }},
                    lng: {{ $image->info->longitude }},
                    zoom: 5
                });
                map.addMarker({
                    lat: {{ $image->info->latitude }},
                    lng: {{ $image->info->longitude }}
                });
            });
        </script>
    @endif
@endsection
