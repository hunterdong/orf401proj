<div class="col-md-3">
    <h3 class="content-heading">{{ t('Share This') }} {{ siteSettings('siteName') }}</h3>
    <div class="clearfix">
        <div class="more-from-site">
            @include('master/share')
        </div>
    </div>

    <h3 class="content-heading">{{ t('Color Palette') }}</h3>
    <div class="colorPalettes clearfix">
    </div>

    <div class="clearfix">
        <h3 class="content-heading">{{ t('Author') }}</h3>
        <div class="col-md-12">
            <div class="row">
                <a href="{{ route('user', ['username' => $image->user->username]) }}" class="thumbnail pull-left">
                    <img src="{{ Resize::avatar($image->user,'avatar') }}" alt="{{ $image->user->fullname }}">
                </a>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <p><strong><a href="{{ route('user', ['username' => $image->user->username]) }}">{{ $image->user->fullname }}</a></strong></p>
                    <p>{{ str_limit($image->user->about_me) }}</p>
                    @if(auth()->check() == false)
                        <a href="{{ route('login') }}" class="btn btn-info btn-xs">{{ t('Follow Me') }}</a>
                    @else
                        @if(auth()->user()->id == $image->user->id)
                            <a class="btn btn-success btn-xs" href="{{ route('users.settings') }}">{{ t('Edit Profile') }}</a>
                        @else
                            <button class="btn btn-info btn-xs replyfollow follow" id="{{ $image->user->id }}">{{ checkFollow($image->user->id) ? t('Un Follow') : t('Follow Me') }}</button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('image/exif')

    @if($image->tags)
        <h3 class="content-heading">{{ t('Tags') }}</h3>
        <ul class="list-inline taglist">
            @foreach(explode(',',$image->tags) as $tag)
                <li><a href="{{ route('tags',$tag) }}" class="tag"><span class="label label-info">{{ $tag }}</span></a></li>
            @endforeach
        </ul>
    @endif

    <h3 class="content-heading">{{ t('More From') }} {{ siteSettings('siteName') }}</h3>
    <div class="clearfix">
        <div class="more-from-site">
            @foreach(moreFromSite() as $img)
                <a href="{{ route('image', ['id' => $img->id, 'slug' => $img->slug]) }}"><img src="{{ Resize::image($img, 'sidebarImage') }}" alt="{{ $img->title }}"/></a>
            @endforeach
        </div>
    </div>

    @if($image->favorites->count() >= 1)
        <h3 class="content-heading">{{ t('Favorites') }}
            <small class="pull-right">{{ $image->favorites->count() }}</small>
        </h3>
        <div class="clearfix">
            <div class="more-from-site">
                @foreach($image->favorites()->with('user')->take(16)->get() as $user)
                    <a href="{{ route('user',['username' => $user->user->username]) }}"><img src="{{ Resize::avatar($user->user,'avatar') }}" alt="{{ $user->user->fullname }}"/></a>
                @endforeach
            </div>
        </div>
    @endif
</div>