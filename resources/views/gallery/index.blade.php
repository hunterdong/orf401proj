@extends('master/index')
@section('title')
    @if(Input::get('category')){{ $title }} {{ t('in category') }} {{ getCategoryName(Input::get('category')) }}@else{{ $title }}@endif
@endsection
@section('content')
    <h1 class="content-heading">{{ $title }} @if(Input::get('category')){{ t('in category') }} {{ getCategoryName(Input::get('category')) }}@endif</h1>
    @include('gallery/util-list')
    <div class="gallery" id="galley">
        @foreach($images->chunk(3) as $img)
            <div class="row">
                @foreach($img as $image)
                    @if($image->user and $image->approved_at)
                        <div class="col-md-4 col-sm-4 gallery-display">
                            @if($image->featured_at)
                                <div class="right-ribbon">
                                    {{ t('Featured') }}
                                </div>
                            @endif
                            <figure>
                                <a href="{{ route('image', ['id' => $image->id, 'slug' => $image->slug]) }}">
                                    <img data-original="{{ Resize::image($image, 'gallery')  }}" alt="{{ str_limit($image->title,30) }}" class="display-image">
                                </a>
                                <a href="{{ route('image', ['id' => $image->id, 'slug' => $image->slug]) }}" class="figcaption">
                                    <h3>{{ str_limit($image->title, 40) }}</h3>
                                    <span>{{ str_limit($image->image_description, 80) }}</span>
                                </a>
                            </figure>
                            <!--figure-->
                            <div class="box-detail">
                                <h5 class="heading"><a href="{{ route('image', ['id' => $image->id, 'slug' => $image->slug]) }}">{{ str_limit($image->title,15) }}</a></h5>
                                <ul class="list-inline gallery-details">
                                    <li><a href="{{ route('user', ['username' => $image->user->username]) }}">{{ $image->user->fullname }}</a></li>
                                    <li class="pull-right"><i class="fa fa-eye"></i> {{ $image->views }} <i class="fa fa-heart"></i> {{ $image->favorites->count() }} <i class="fa fa-comments"></i> {{ $image->comments->count() }}
                                        <a class="links" href="{{  Resize::image($image, 'mainImage') }}" title="{{ $image->title }}" data-sub-html="<h4>{{ $image->title }}</h4>">
                                            <i class="fa fa-external-link"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!--.box-detail-->
                        </div>
                        <!--.gallery-display-->
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
@section('pagination')
    <div class="container">
        {!! $images->appends(Input::except('page'))->render() !!}
    </div>
@endsection