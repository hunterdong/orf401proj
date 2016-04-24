@extends('master/index')
@section('custom')
<!-- Main page -->

<div id="content-wrapper" class="snap-content">
    <!-- Progress bar -->
    <div class="container progress-holder">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="progress-rate">--%</div>
                <div class="progress">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                        <span class="sr-only">--% Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Drag zone -->
    @if($limitReached == true)
    <div class="container text-center start-zone">
        <p>{{ t('You have reached daily upload limit') }}</p>
    </div>
    @else
    <div class="container text-center start-zone">
        <form id="fileupload" action="{{ route('images.upload') }}" method="POST" enctype="multipart/form-data" accept="image/gif, image/jpeg, image/jpg, image/png">
            <input type="file" id="input-browse" class="block-hide" multiple="multiple" name="files">
            <p>{{ t('Drag photos from your computer in here. Select maximum 10 images') }}</p>
            <div class="help-block">{{ t('Maximum file size allowed is') }} {{ maxUploadSize()*1024 < (int)siteSettings('maxImageSize')*(1024) ? maxUploadSize() : (int)siteSettings('maxImageSize') }}MB (o.O)</div>
            <p>{{ t('You can also') }} <a class="browse_files" href="#">{{ t('browse') }}</a> {{ t('for photos to upload') }}.</p>
        </form>
    </div>
    @endif

    <div class="uploader block-hide">
        <!-- Preview zone -->
        <div class="container preview-zone">
            <img src="{{ asset('static/img/mask-tran.png') }}"/>
        </div>

        <!-- Thumbnail zone -->
        <div class="container thumbnail-zone">
            <div class="row">
                <div class="col-md-9">
                    <ul class="thumbnails-holder nav nav-tabs" id="myTab"></ul>
                </div>

                <div class="actions col-md-3 text-right">
                    <button type="button" class="btn btn-danger btn-remove"><i class="glyphicon glyphicon-trash"></i> remove</button>
                    <button type="button" class="btn btn-primary btn-upload"><i class="glyphicon glyphicon-open"></i> upload</button>
                    <p class="help-block"><span class="readyphoto"></span>/<span class="totalphoto"></span> photos are ready</p>
                </div>
            </div>
        </div>

        <!-- Edit zone -->
        <div class="container-fluid tab-content edit-zone-holder"></div>

    </div>
</div>
@if($limitReached == false)
<script type="text/x-tmpl" id="tmpl-uploadThumbnail">
			<li class="{%=o.active?'active':'' %}">
				<a href="#photo-{%=o.index %}" class="upload-thumbnail photo_{%=o.index %} {%= o.hasGPS?'ready':'' %}">
					<i class="glyphicon glyphicon-ok-sign" title="has location information"></i>
					<img src="{%=o.imageSrc %}" width="64" height="auto" alt="{%=o.filename %}"/>
				</a>
			</li>
		</script>

<script type="text/x-tmpl" id="tmpl-uploadEditor">
			<div class="container edit-zone tab-pane {%= o.active?'active':''%}" id="photo-{%=o.count%}" data-cid="{%=o.count %}">
				<form role="form">
					<div class="row">
						<div class="form-group col-md-8">
							<label for="photo[title]" class="label-main">{{t('Title')}}</label>
							<input type="text" class="form-control photo-title instant-edit input-lg" name="photo[title]" placeholder="Untitled Image" value="{%=o.title?o.title.replace(/\.\w{3,4}$/gi, ''):'Untitled Image'%}">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 exif">
							<label class="label-main">{{ t('EXIF DATA') }}</label>

							<div class="row">
								<label class="col-md-5 control-label">{{t('Camera')}}</label>
								<input type="text" class="col-md-7 input-sm instant-edit" name="photo[exif][camera]" placeholder="Set Camera" value="{%=o.Model?o.Model:''%}"/>
							</div>

							<div class="row">
								<label class="col-md-5 control-label">{{t('Lens')}}</label>
								<input type="text" class="col-md-7 input-sm instant-edit" name="photo[exif][lens]" placeholder="Set Lens" value="{%=o.Lens?o.Lens:''%}"/>
							</div>

							<div class="row">
								<label class="col-md-5 control-label">{{t('Focal Length')}}</label>
								<input type="text" class="col-md-7 input-sm instant-edit" name="photo[exif][focalLength]" placeholder="Set Focal Length" value="{%=o.FocalLength?(o.FocalLength + 'mm'):''%}"/>
							</div>

							<div class="row">
								<label class="col-md-5 control-label">{{ t('Shutter Speed') }}</label>
								{%
									var shutterSpeed = '';
									if (o.ExposureTime){
										if (o.ExposureTime < 1){
											shutterSpeed = '1/' + Math.round(1/o.ExposureTime);
										} else{
											shutterSpeed = o.ExposureTime;
										}
									}
								%}
								<input type="text" class="col-md-7 input-sm instant-edit" name="photo[exif][shutterspeed]" placeholder="Set Shutter Speed" value="{%= shutterSpeed %}"/>
							</div>

							<div class="row">
								<label class="col-md-5 control-label">{{t('Aperture')}}</label>
								<input type="text" class="col-md-7 input-sm instant-edit" name="photo[exif][aperture]" placeholder="Set Aperture" value="{%=o.FNumber?('F' + o.FNumber):''%}"/>
							</div>

							<div class="row">
								<label class="col-md-5 control-label">{{t('ISO')}}</label>
								<input type="text" class="col-md-7 input-sm instant-edit" name="photo[exif][iso]" placeholder="Set ISO" value="{%=o.ISOSpeedRatings?o.ISOSpeedRatings:''%}"/>
							</div>

							<div class="row">
								<label class="col-md-5 col-xs-2 control-label">{{ t('Taken') }}</label>
								{%
									var dateTimeOriginal = o.DateTimeOriginal || o.DateTime || '';
									if (dateTimeOriginal){
										var time = dateTimeOriginal;
										time = time.split(' ');
										if(time != time[0]) {
											odate = time[0].replace(/\:/gi, '/');
											otime = time[1].substring(0, 5);
											dateTimeOriginal = odate + ' ' + otime;
										} else {
											dateTimeOriginal = '';
										}
									}
								%}

								<div class="col-md-1 col-xs-1"><i class="glyphicon glyphicon-calendar"></i></div>
								<input type="text" class="col-md-3 col-xs-3 input-sm instant-edit datepicker" name="photo[exif][taken][0]" placeholder="Set Date" value="{%=dateTimeOriginal?odate:''%}" readonly="true"/>
								<div class="col-md-1 col-xs-1 col-xs-1"><i class="glyphicon glyphicon-time"></i></div>
								<input type="text" class="col-md-2 col-xs-2 input-sm instant-edit timepicker" name="photo[exif][taken][1]" placeholder="Set Time" value="{%=dateTimeOriginal?otime:''%}"/>
							</div>

							<div class="form-group">
								<label class="label-main">{{ t('Category') }}</label>
								{!! Form::select('photo[category]', array_pluck(siteCategories(), 'name', 'id'),1,['class' => 'form-control', 'required' => 'required']) !!}
							</div>

							@if(siteSettings('allowDownloadOriginal') == 'leaveToUser')
							<div class="form-group">
								<label class="label-main">{{ t('Allow Download') }}</label>
								{!! Form::select('photo[allow_download]',array(1 => 'Yes', 0 => 'No'),1,array('class' => 'form-control', 'required' => 'required')) !!}
							</div>
							@endif
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="label-main">{{ t('Description') }}</label>
								<textarea name="photo[description]" class="form-control" rows="7"></textarea>
							</div>

							<div class="form-group">
								<label class="label-main">{{t('Is Adult')}}</label>
								<select class="form-control" name="photo[adult]">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select>
							</div>

							<div class="form-group">
								<label class="label-main">{{t('Tags')}}</label>
								<select name="tags[]" class="form-control tagging" placeholder="Tag to make your photo easier to find..." multiple="multiple"></select>
							</div>

						</div>
						<div class="col-md-4">
							<label class="label-main">{{t('Map')}}</label>
							<div class="map row">
								<div class="map-canvas col-md-12">
								</div>
								<input type="text" class="form-control map-search" placeholder="Search for location..." style="width:80%"/>
								<input type="hidden" class="photoLat" name="photo[latitude]" value="{%=o.GPSLatitude?o.GPSLatitude:''%}"/>
								<input type="hidden" class="photoLon" name="photo[longitude]" value="{%=o.GPSLongitude?o.GPSLongitude:''%}"/>
							 </div>
						</div>
					</div>
				</form>
			</div>
		</script>
		<script>
		var av_limit = {
		    image_size : {{ maxUploadSize()*1000000 < (int)siteSettings('maxImageSize')*(1000000) ? maxUploadSize()*1000000 : (int)siteSettings('maxImageSize')*(1000000) }},
		    auto_approve: {{ (int)siteSettings('autoApprove') }},
		    text : {
		        @if((int)siteSettings('autoApprove') == 1)
		            upload_completed: "{{ t('Upload Completed') }}",
		        @else
                    upload_completed: "{{ t('Upload Completed and waiting for approval') }}",
		        @endif
		        upload_failed: "{{ t('Sorry file upload failed') }}",
		        remove_photo: "{{ t('Are you sure to remove this photo') }}"
		    },
		    files_limit: {{ ((int)limitPerDay() - (int)$numberOfUploadByUser) >= 10 ? 10 : (int)limitPerDay() - $numberOfUploadByUser }},
		    redirect_url : '{{ route('user', ['username' => auth()->user()->username]) }}',
		    tags: {{ (int)siteSettings('tagsLimit') }}
		}
		</script>
@endif
@endsection
@section('gmaps')
	{!! HTML::script('//maps.google.com/maps/api/js?sensor=false&libraries=places&language=en') !!}
@endsection