<div class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">{{ siteSettings('siteName') }}</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('gallery') }}"><i class="fa fa-picture-o fa-fw"></i> {{ t('Gallery') }}</a></li>
                <li><a href="{{ route('users') }}"><i class="fa fa-users fa-fw"></i> {{ t('Users') }}</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list fa-fw"></i> {{ t('Categories') }}<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        @foreach(siteCategories() as $category)
                            <li><a href="{{ route('category', ['slug' => $category->slug]) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fire fa-fw"></i> {{ t('Popular') }}<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('images.popular') }}">{{ t('Popular') }}</a></li>
                        <li><a href="{{ route('images.featured') }}">{{ t('Featured') }}</a></li>
                        <li><a href="{{ route('images.most.viewed') }}">{{ t('Most Viewed') }}</a></li>
                        <li><a href="{{ route('images.most.commented') }}">{{ t('Most Commented') }}</a></li>
                        <li><a href="{{ route('images.most.favorites') }}">{{ t('Most Favorites') }}</a></li>
                        <li><a href="{{ route('images.most.downloads') }}">{{ t('Most Downloads') }}</a></li>
                    </ul>
                </li>
            </ul>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <form class="navbar-form" role="search" method="GET" action="{{ route('search') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ t('Search') }} " name="q" id="srch-term">

                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <ul class="nav navbar-nav navbar-right">
                @if(auth()->check() == false)
                    <li><a href="{{ route('login') }}"><i class="fa fa-sign-in fa-fw"></i> {{ t('Login') }}</a></li>
                    <li><a href="{{ route('registration') }}"><i class="fa fa-plus fa-fw"></i> {{ t('Register') }}</a></li>
                @else
                    <li><a href="{{ route('images.upload') }}"><i class="fa fa-upload fa-fw"></i> {{ t('Upload') }}</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php $notifications = (auth()->user()->notifications()->whereIsRead(0)->count()) ?>
                            @if($notifications)
                                <span class="badge badge-danger">{{ $notifications }}</span>
                            @endif&nbsp;
                            <i class="fa fa-user fa-fw"></i> {{ \Illuminate\Support\Str::words(auth()->user()->fullname, 1, '') }} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('users.feeds') }}">{{ t('Feeds') }}</a></li>
                            <li><a href="{{ route('notifications') }}">{{ t('Notifications') }}
                                    @if($notifications)
                                        <span class="badge badge-danger">{{ $notifications }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('user', ['username' => auth()->user()->username]) }}">{{ t('My Profile') }}</a></li>
                            <li><a href="{{ route('users.settings') }}">{{ t('Profile Settings') }}</a></li>
                            <li><a href="{{ route('logout') }}">{{ t('Logout') }}</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>