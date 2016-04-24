@extends('master/index')
@section('meta')
    <meta name="description" content="{{ strlen($image->image_description) > 2 ? $image->image_description : $image->title.' '.siteSettings('description') }}">
    <meta name="keywords" content="{{ strlen($image->tags) > 2 ? $image->tags : $image->title }}">
    <meta property="og:title" content="{{ $image->title }} - {{ siteSettings('siteName') }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ route('image', ['id' => $image->id, 'slug' => $image->slug]) }}"/>
    <meta property="og:description" content="{{ strlen($image->image_description) > 2 ? $image->image_description : $image->title.' '.siteSettings('description') }}"/>
    <meta property="og:image" content="{{ Resize::image($image,'mainImage') }}"/>
    <meta name="author" content="{{ $image->user->fullname }}">
@endsection
@section('content')
    <h1 class="content-heading">{{ $image->title }}</h1>
    <div class="main-image">
        @if($next)
            <div class="controlArrow controlArrow-prev ">
                <a href="{{ route('image', ['id' => $next->id, 'slug' => $next->slug]) }}" class="fa fa-chevron-left"></a>
            </div>
        @endif
        @if($previous)
            <div class="controlArrow controlArrow-next">
                <a href="{{ route('image', ['id' => $previous->id, 'slug' => $previous->slug]) }}" class="fa fa-chevron-right"></a>
            </div>
        @endif
        <p>
            <a href="{{ Resize::image($image,'mainImage') }}" class="image">
                <img src="{{ Resize::image($image,'mainImage') }}" alt="{{ $image->title }}" class="mainImage img-thumbnail"/>
            </a>
        </p>
    </div>
    <!--.main-image-->
    <div class="clearfix">
        <div class="row">
            <div class="col-md-8">
                <h3 class="content-heading">
                    {{ t('Description') }}
                    <span class="pull-right">
               <div class="btn-group  btn-group-xs">
                   @if(checkFavorite($image->id) == true)
                       <button type="button" class="btn btn-danger favoritebtn" id="{{ $image->id }}"><i class="fa fa-heart"></i> {{ t('Un-Favorite') }}</button>
                   @else
                       <button type="button" class="btn btn-success favoritebtn" id="{{ $image->id }}"><i class="fa fa-heart"></i> {{ t('Favorite') }}</button>
                   @endif
                   <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                       <i class="fa fa-plus fa-fw"></i> {{ t('More') }}
                       <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                       @if(siteSettings('allowDownloadOriginal') == 1 || siteSettings('allowDownloadOriginal') == 'leaveToUser' && $image->allow_download == 1)
                           <li>
                               <a href="{{ route('images.download', ['any' => Crypt::encrypt($image->id)]) }}"><i class="fa fa-download fa-fw"></i> {{ t('Download Original') }}</a>
                           </li>
                       @endif
                       <li><a href="{{ route('images.report', ['id' => $image->id, 'slug' => $image->slug]) }}"><i class="fa fa-ban fa-fw"></i> {{ t('Report') }}</a></li>
                       @if(auth()->check() == true && auth()->user()->id == $image->user_id)
                           <li><a href="{{ route('images.edit', ['id' => $image->id, 'slug' => $image->slug]) }}"><i class="fa fa-edit fa-fw"></i> {{ t('Edit') }}</a></li>
                           <li><a href="{{ route('images.delete', ['id' => $image->id, 'slug' => $image->slug]) }}"><i class="fa fa-remove fa-fw"></i> {{ t('Delete') }}</a></li>
                       @endif
                       @if(auth()->check() && auth()->user()->permission == 'admin')
                           <li><a href="{{ route('admin.images.edit', [$image->id]) }}"><i class="fa fa-adjust fa-fw"></i> Edit From Admin Panel</a></li>
                       @endif
                   </ul>
                   <!-- end of dropdown menu-->
               </div>
            </span>
                </h3>
                <p>{!! nl2br(\App\Artvenue\Helpers\Smilies::parse($image->image_description))  !!}</p>
            </div>
            <div class="col-md-4">
                <h3 class="content-heading">{{ t('Details') }}</h3>
                <div class="image-status">
                    <ul class="list-inline">
                        <li><i class="fa fa-eye"></i> {{ $image->views }}</li>
                        <li><i class="fa fa-heart"></i> {{ $image->favorites->count() }}</li>
                        <li><i class="fa fa-comments"></i> {{ $image->comments->count() }}</li>
                        <li><i class="fa fa-download"></i> {{ $image->downloads }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--.clearfix-->
    @include('image/comment')
@endsection
@section('sidebar')
    @include('image/sidebar')
@endsection