@extends('admin.master.index')
@section('content')
    <div class="row">
    <div class="col-md-3">
        <a href="{{ route('image',['id' => $image->id, 'slug' => $image->slug]) }}" target="_blank"><img src="{{ Resize::image($image,'gallery') }}" class="thumbnail img-responsive"></a>
        <div class="form-group">
            <button class="btn btn-danger clearImageCache" data-image="{{ $image->id }}"><i class="ion ion-nuclear"></i> Clear Image Cache and Thumbnails</button>
        </div>
        <ul class="list-group">
            <a href="#" class="list-group-item disabled">
                Image Statics
            </a>
            <li class="list-group-item"><strong>Uploader</strong> <a href="{{ route('user', [$image->user->username]) }}">{{ $image->user->fullname }}</a></li>
            <li class="list-group-item"><strong>Views</strong> {{ $image->views }}</li>
            <li class="list-group-item"><strong>Comments</strong> {{ $image->comments->count() }}</li>
            <li class="list-group-item"><strong>Favorites</strong> {{ $image->favorites->count() }}</li>
            <li class="list-group-item"><strong>Downloads</strong>  {{ $image->downloads }}</li>
            <li class="list-group-item"><strong>Uploaded At</strong> {{ $image->created_at->diffForHumans() }} </li>
            <li class="list-group-item"><strong>Last Updated</strong> {{ $image->updated_at->diffForHumans() }} </li>
            <li class="list-group-item"><strong>Featured At</strong> {{ $image->featured_at  == null ? 'Not Featured' : $image->featured_at->diffForHumans() }} </li>
        </ul>
    </div>
    <div class="col-md-9">
        {!! Form::open() !!}
        <div class="form-group">
            {!! Form::label('title', 'Title') !!}
            {!! Form::text('title', $image->title, ['class' => 'form-control input-lg', 'placeholder' => 'Title of Image']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Description') !!}
            {!! Form::textarea('description', $image->image_description, ['class' => 'form-control input-lg', 'placeholder' => 'Description']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('category', t('Category')) !!}
            <select name="category" class="form-control input-lg" required>
                <option value="{{ $image->category->id }}">{{ ucfirst($image->category->name) }}</option>
                <option>--------</option>
                @foreach(siteCategories() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group form-group-lg">
            {!! Form::label('tags', 'Tags') !!}
            <select class="form-control input-lg tagging" multiple="multiple" name="tags[]">
                @foreach(explode(',',$image->tags) as $tag)
                    @if($tag)
                        <option selected="selected">{{ $tag }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            {!! Form::label('is_adult', 'Is Adult') !!}
            <select class="form-control input-lg" name="is_adult">
                <option value="{{ $image->is_adult }}">{{ $image->is_adult ? 'Yes' : 'No'}}</option>
                <option>------</option>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group">
            {!! Form::label('featured_at', 'Is Featured Image') !!}
            {!! Form::checkbox('featured_at', 1, (bool)$image->featured_at) !!}
        </div>

        <div class="form-group">
            {!! Form::label('delete', 'Delete this image') !!}
            {!! Form::checkbox('delete', 1) !!}
        </div>
        {!! Form::submit('Update', ['class' => 'btn btn-success btn-lg']) !!}
        {!! Form::close() !!}
    </div>
    </div>
@endsection
@section('extra-js')
    <script>
        $(function(){
            $(".tagging").select2({
                theme: "bootstrap",
                minimumInputLength: 3,
                maximumSelectionLength: {{ (int)siteSettings('tagsLimit') }},
                tags: true,
                tokenSeparators: [","]
            })
        });
    </script>
@endsection