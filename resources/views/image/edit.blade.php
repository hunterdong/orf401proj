@extends('master/index')

@section('content')
    <h1 class="content-heading">{{ t('Editing Image') }}</h1>
    {!! Form::open() !!}
    <div class="form-group">
        {!! Form::label('title', t('Title')) !!}
        {!!  Form::text('title',$image->title,['class'=>'form-control input-lg','required'=>'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('description', t('Description')) !!}
        {!! Form::textarea('description',$image->image_description,['class'=>'form-control input-lg'])  !!}
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
        {!! Form::submit('Update Image', ['class'=>'btn btn-success btn-lg'])  !!}
    </div>
    {!! Form::close()  !!}
@endsection

@section('extrafooter')
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

@section('sidebar')
    @include('image/sidebar')
@endsection