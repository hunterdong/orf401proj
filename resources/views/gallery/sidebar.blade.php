<div class="clearfix">
    <h3 class="content-heading">{{ t('Share This') }}</h3>
    @include('master/share')
</div>
<div class="clearfix">
    <h3 class="content-heading">{{ t('Featured Image') }}</h3>

    <div class="imagesFromUser">
        @foreach(getFeaturedImage(1) as $featuredImage)
            <a href="{{ route('image', ['id' => $featuredImage->id, 'slug' => $featuredImage->slug]) }}" class="pull-left userimage">
                <img src="{{ Resize::image($featuredImage, 'featuredImage') }}"
                     alt="{{ $featuredImage->title }}" class="img-thumbnail">
            </a>
        @endforeach
    </div>
</div>
@if(getFeaturedUser()->count() >= 1)
    <div class="clearfix">
        <h3 class="content-heading">{{ t('Featured User') }}</h3>

        <div class="imagesFromUser">
            @foreach(getFeaturedUser() as $featuredUser)
                <div class="col-md-12">
                    <div class="row">
                        <a href="{{ route('user', ['username' => $featuredUser->username]) }}" class="thumbnail pull-left">
                            <img src="{{ Resize::avatar($featuredUser, 'avatar') }}" alt="{{ $featuredUser->fullname }}">
                        </a>

                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <p><strong><a href="{{ route('user', ['username' => $featuredUser->username]) }}">{{ $featuredUser->fullname }}</a></strong></p>
                            <p>{{ str_limit($featuredUser->about_me) }}</p>
                            @if(auth()->check() == false)
                                <a href="{{ route('login') }}" class="btn btn-info btn-xs">{{ t('Follow Me') }}</a>
                            @else
                                @if(auth()->user()->id == $featuredUser->id)
                                    <a class="btn btn-success btn-xs" href="{{ route('users.settings') }}">{{ t('Edit Profile') }}</a>
                                @else
                                    <button class="btn btn-info btn-xs replyfollow follow" id="{{ $featuredUser->id }}">{{ checkFollow($featuredUser->id) ? t('Un Follow') : t('Follow Me') }}</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
<div class="clearfix">
    <h3 class="content-heading">{{ t('More From') }} {{ siteSettings('siteName') }}</h3>

    <div class="more-from-site">
        @foreach(moreFromSite() as $sidebarImage)
            <a href="{{ route('image', ['id' => $sidebarImage->id, 'slug' => $sidebarImage->slug]) }}"><img src="{{ Resize::image($sidebarImage,'sidebarImage') }}" alt="{{ $sidebarImage->title }}"/></a>
        @endforeach
    </div>
</div>