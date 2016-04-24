<?php return array (
    'app' =>
        array (
            'debug' => true,
            'url' => 'http://localhost',
            'timezone' => 'UTC',
            'locale' => 'en',
            'fallback_locale' => 'en',
            'key' => 'caBUbqUknc9yCkv3rbbs12uooP4I8drt',
            'cipher' => 'AES-256-CBC',
            'log' => 'single',
            'providers' =>
                array (
                    0 => 'Illuminate\\Foundation\\Providers\\ArtisanServiceProvider',
                    1 => 'Illuminate\\Auth\\AuthServiceProvider',
                    2 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
                    3 => 'Illuminate\\Bus\\BusServiceProvider',
                    4 => 'Illuminate\\Cache\\CacheServiceProvider',
                    5 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
                    6 => 'Illuminate\\Routing\\ControllerServiceProvider',
                    7 => 'Illuminate\\Cookie\\CookieServiceProvider',
                    8 => 'Illuminate\\Database\\DatabaseServiceProvider',
                    9 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
                    10 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
                    11 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
                    12 => 'Illuminate\\Hashing\\HashServiceProvider',
                    13 => 'Illuminate\\Mail\\MailServiceProvider',
                    14 => 'Illuminate\\Pagination\\PaginationServiceProvider',
                    15 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
                    16 => 'Illuminate\\Queue\\QueueServiceProvider',
                    17 => 'Illuminate\\Redis\\RedisServiceProvider',
                    18 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
                    19 => 'Illuminate\\Session\\SessionServiceProvider',
                    20 => 'Illuminate\\Translation\\TranslationServiceProvider',
                    21 => 'Illuminate\\Validation\\ValidationServiceProvider',
                    22 => 'Illuminate\\View\\ViewServiceProvider',
                    23 => 'App\\Providers\\AppServiceProvider',
                    24 => 'App\\Providers\\AuthServiceProvider',
                    25 => 'App\\Providers\\EventServiceProvider',
                    26 => 'App\\Providers\\RouteServiceProvider',
                    27 => 'App\\Providers\\CopyServiceProvider',
                    28 => 'Collective\\Html\\HtmlServiceProvider',
                    29 => 'App\\Artvenue\\Providers\\RepositoryServiceProvider',
                    30 => 'Laravel\\Socialite\\SocialiteServiceProvider',
                    31 => 'Intervention\\Image\\ImageServiceProvider',
                    32 => 'App\\Providers\\DropboxServiceProvider',
                    33 => 'Greggilbert\\Recaptcha\\RecaptchaServiceProvider',
                    34 => 'Roumen\\Feed\\FeedServiceProvider',
                    35 => 'Roumen\\Sitemap\\SitemapServiceProvider',
                    36 => 'Yajra\\Datatables\\DatatablesServiceProvider',
                ),
            'aliases' =>
                array (
                    'App' => 'Illuminate\\Support\\Facades\\App',
                    'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
                    'Auth' => 'Illuminate\\Support\\Facades\\Auth',
                    'Blade' => 'Illuminate\\Support\\Facades\\Blade',
                    'Bus' => 'Illuminate\\Support\\Facades\\Bus',
                    'Cache' => 'Illuminate\\Support\\Facades\\Cache',
                    'Config' => 'Illuminate\\Support\\Facades\\Config',
                    'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
                    'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
                    'DB' => 'Illuminate\\Support\\Facades\\DB',
                    'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
                    'Event' => 'Illuminate\\Support\\Facades\\Event',
                    'File' => 'Illuminate\\Support\\Facades\\File',
                    'Gate' => 'Illuminate\\Support\\Facades\\Gate',
                    'Hash' => 'Illuminate\\Support\\Facades\\Hash',
                    'Input' => 'Illuminate\\Support\\Facades\\Input',
                    'Inspiring' => 'Illuminate\\Foundation\\Inspiring',
                    'Lang' => 'Illuminate\\Support\\Facades\\Lang',
                    'Log' => 'Illuminate\\Support\\Facades\\Log',
                    'Mail' => 'Illuminate\\Support\\Facades\\Mail',
                    'Password' => 'Illuminate\\Support\\Facades\\Password',
                    'Queue' => 'Illuminate\\Support\\Facades\\Queue',
                    'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
                    'Redis' => 'Illuminate\\Support\\Facades\\Redis',
                    'Request' => 'Illuminate\\Support\\Facades\\Request',
                    'Response' => 'Illuminate\\Support\\Facades\\Response',
                    'Route' => 'Illuminate\\Support\\Facades\\Route',
                    'Schema' => 'Illuminate\\Support\\Facades\\Schema',
                    'Session' => 'Illuminate\\Support\\Facades\\Session',
                    'Storage' => 'Illuminate\\Support\\Facades\\Storage',
                    'URL' => 'Illuminate\\Support\\Facades\\URL',
                    'Validator' => 'Illuminate\\Support\\Facades\\Validator',
                    'View' => 'Illuminate\\Support\\Facades\\View',
                    'Form' => 'Collective\\Html\\FormFacade',
                    'HTML' => 'Collective\\Html\\HtmlFacade',
                    'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
                    'ImageResize' => 'Intervention\\Image\\Facades\\Image',
                    'Recaptcha' => 'Greggilbert\\Recaptcha\\Facades\\Recaptcha',
                    'Resize' => 'App\\Artvenue\\Helpers\\Resize',
                    'Feed' => 'Roumen\\Feed\\Facades\\Feed',
                    'Datatables' => 'yajra\\Datatables\\Datatables',
                ),
        ),
    'auth' =>
        array (
            'driver' => 'eloquent',
            'model' => 'App\\Artvenue\\Models\\User',
            'table' => 'users',
            'password' =>
                array (
                    'email' => 'emails.auth.reminder',
                    'table' => 'password_resets',
                    'expire' => 60,
                ),
        ),
    'broadcasting' =>
        array (
            'default' => 'pusher',
            'connections' =>
                array (
                    'pusher' =>
                        array (
                            'driver' => 'pusher',
                            'key' => NULL,
                            'secret' => NULL,
                            'app_id' => NULL,
                        ),
                    'redis' =>
                        array (
                            'driver' => 'redis',
                            'connection' => 'default',
                        ),
                    'log' =>
                        array (
                            'driver' => 'log',
                        ),
                ),
        ),
    'cache' =>
        array (
            'default' => 'file',
            'stores' =>
                array (
                    'apc' =>
                        array (
                            'driver' => 'apc',
                        ),
                    'array' =>
                        array (
                            'driver' => 'array',
                        ),
                    'database' =>
                        array (
                            'driver' => 'database',
                            'table' => 'cache',
                            'connection' => NULL,
                        ),
                    'file' =>
                        array (
                            'driver' => 'file',
                            'path' => storage_path('framework/cache'),
                        ),
                    'image' =>
                        array (
                            'driver' => 'file',
                            'path' => storage_path('app/image'),
                        ),
                    'memcached' =>
                        array (
                            'driver' => 'memcached',
                            'servers' =>
                                array (
                                    0 =>
                                        array (
                                            'host' => '127.0.0.1',
                                            'port' => 11211,
                                            'weight' => 100,
                                        ),
                                ),
                        ),
                    'redis' =>
                        array (
                            'driver' => 'redis',
                            'connection' => 'default',
                        ),
                ),
            'prefix' => 'artvenue',
        ),
    'compile' =>
        array (
            'files' =>
                array (
                ),
            'providers' =>
                array (
                ),
        ),
    'database' =>
        array (
            'fetch' => 8,
            'default' => 'mysql',
            'connections' =>
                array (
                    'sqlite' =>
                        array (
                            'driver' => 'sqlite',
                            'database' => storage_path('database.sqlite'),
                            'prefix' => '',
                        ),
                    'mysql' =>
                        array (
                            'driver'    => 'mysql',
                            'host'      => env('DB_HOST'),
                            'database'  => env('DB_DATABASE'),
                            'username'  => env('DB_USERNAME'),
                            'password'  => env('DB_PASSWORD'),
                            'charset'   => 'utf8',
                            'collation' => 'utf8_unicode_ci',
                            'prefix'    => '',
                            'strict'    => false,
                        ),
                    'pgsql' =>
                        array (
                            'driver' => 'pgsql',
                            'host' => 'localhost',
                            'database' => 'av',
                            'username' => 'homestead',
                            'password' => 'secret',
                            'charset' => 'utf8',
                            'prefix' => '',
                            'schema' => 'public',
                        ),
                    'sqlsrv' =>
                        array (
                            'driver' => 'sqlsrv',
                            'host' => 'localhost',
                            'database' => 'av',
                            'username' => 'homestead',
                            'password' => 'secret',
                            'charset' => 'utf8',
                            'prefix' => '',
                        ),
                ),
            'migrations' => 'migrations',
            'redis' =>
                array (
                    'cluster' => false,
                    'default' =>
                        array (
                            'host' => '127.0.0.1',
                            'port' => 6379,
                            'database' => 0,
                        ),
                ),
        ),
    'filesystems' =>
        array (
            'default' => 'local',
            'cloud' => 's3',
            'disks' =>
                array (
                    'local' =>
                        array (
                            'driver' => 'local',
                            'root' => public_path(),
                        ),
                    'ftp' =>
                        array (
                            'driver' => 'ftp',
                            'host' => 'ftp.example.com',
                            'username' => 'your-username',
                            'password' => 'your-password',
                        ),
                    's3' =>
                        array (
                            'driver' => 's3',
                            'key' => 'your-key',
                            'secret' => 'your-secret',
                            'region' => 'your-region',
                            'bucket' => 'your-bucket',
                            'distribution_url' => NULL,
                        ),
                    'rackspace' =>
                        array (
                            'driver' => 'rackspace',
                            'username' => 'your-username',
                            'key' => 'your-key',
                            'container' => 'your-container',
                            'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
                            'region' => 'IAD',
                            'url_type' => 'publicURL',
                        ),
                    'dropbox' =>
                        array (
                            'driver' => 'dropbox',
                            'clientIdentifier' => NULL,
                            'accessToken' => NULL,
                            'storageFolder' => 'Public/artvenue',
                            'userId' => NULL,
                        ),
                    'copy' =>
                        array (
                            'driver' => 'copy',
                            'consumerKey' => NULL,
                            'consumerSecret' => NULL,
                            'accessToken' => NULL,
                            'tokenSecret' => NULL,
                        ),
                ),
        ),
    'image' =>
        array (
            'driver' => 'gd',
        ),
    'mail' =>
        array (
            'driver' => 'smtp',
            'host' => 'mailtrap.io',
            'port' => '2525',
            'from' =>
                array (
                    'address' => NULL,
                    'name' => NULL,
                ),
            'encryption' => 'tls',
            'username' => NULL,
            'password' => NULL,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        ),
    'queue' =>
        array (
            'default' => 'sync',
            'connections' =>
                array (
                    'sync' =>
                        array (
                            'driver' => 'sync',
                        ),
                    'database' =>
                        array (
                            'driver' => 'database',
                            'table' => 'jobs',
                            'queue' => 'default',
                            'expire' => 60,
                        ),
                    'beanstalkd' =>
                        array (
                            'driver' => 'beanstalkd',
                            'host' => 'localhost',
                            'queue' => 'default',
                            'ttr' => 60,
                        ),
                    'sqs' =>
                        array (
                            'driver' => 'sqs',
                            'key' => 'your-public-key',
                            'secret' => 'your-secret-key',
                            'queue' => 'your-queue-url',
                            'region' => 'us-east-1',
                        ),
                    'iron' =>
                        array (
                            'driver' => 'iron',
                            'host' => 'mq-aws-us-east-1.iron.io',
                            'token' => 'your-token',
                            'project' => 'your-project-id',
                            'queue' => 'your-queue-name',
                            'encrypt' => true,
                        ),
                    'redis' =>
                        array (
                            'driver' => 'redis',
                            'connection' => 'default',
                            'queue' => 'default',
                            'expire' => 60,
                        ),
                ),
            'failed' =>
                array (
                    'database' => 'mysql',
                    'table' => 'failed_jobs',
                ),
        ),
    'recaptcha' =>
        array (
            'public_key' => '',
            'private_key' => '',
            'template' => '',
            'driver' => 'curl',
            'options' =>
                array (
                    'curl_timeout' => 1,
                ),
            'version' => 2,
        ),
    'services' =>
        array (
            'mailgun' =>
                array (
                    'domain' => '',
                    'secret' => '',
                ),
            'mandrill' =>
                array (
                    'secret' => '',
                ),
            'ses' =>
                array (
                    'key' => '',
                    'secret' => '',
                    'region' => 'us-east-1',
                ),
            'stripe' =>
                array (
                    'model' => 'App\\User',
                    'key' => '',
                    'secret' => '',
                ),
            'facebook' =>
                array (
                    'client_id' => '',
                    'client_secret' => '',
                    'redirect' => 'http://av.dev/auth/facebook/callback',
                ),
            'google' =>
                array (
                    'client_id' => '',
                    'client_secret' => '',
                    'redirect' => 'http://av.dev/auth/google/callback',
                ),
            'twitter' =>
                array (
                    'client_id' => '',
                    'client_secret' => '',
                    'redirect' => 'http://av.dev/auth/twitter/callback',
                ),
        ),
    'session' =>
        array (
            'driver' => 'file',
            'lifetime' => 120,
            'expire_on_close' => false,
            'encrypt' => true,
            'files' => storage_path('framework/sessions'),
            'connection' => NULL,
            'table' => 'sessions',
            'lottery' =>
                array (
                    0 => 2,
                    1 => 100,
                ),
            'cookie' => 'artvenue_session',
            'path' => '/',
            'domain' => NULL,
            'secure' => false,
        ),
    'smilies' =>
        array (
            'path' => '../../static/smilies/',
            'images' =>
                array (
                    ':mrgreen:' =>
                        array (
                            0 => 'icon_mrgreen.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':neutral:' =>
                        array (
                            0 => 'icon_neutral.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':twisted:' =>
                        array (
                            0 => 'icon_twisted.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':shock:' =>
                        array (
                            0 => 'icon_eek.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':smile:' =>
                        array (
                            0 => 'icon_smile.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':???:' =>
                        array (
                            0 => 'icon_confused.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':cool:' =>
                        array (
                            0 => 'icon_cool.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':evil:' =>
                        array (
                            0 => 'icon_evil.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':grin:' =>
                        array (
                            0 => 'icon_biggrin.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':oops:' =>
                        array (
                            0 => 'icon_redface.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':razz:' =>
                        array (
                            0 => 'icon_razz.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':roll:' =>
                        array (
                            0 => 'icon_rolleyes.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':wink:' =>
                        array (
                            0 => 'icon_wink.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':cry:' =>
                        array (
                            0 => 'icon_cry.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':eek:' =>
                        array (
                            0 => 'icon_surprised.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':lol:' =>
                        array (
                            0 => 'icon_lol.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':mad:' =>
                        array (
                            0 => 'icon_mad.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':sad:' =>
                        array (
                            0 => 'icon_sad.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    '8-)' =>
                        array (
                            0 => 'icon_cool.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    '8-O' =>
                        array (
                            0 => 'icon_eek.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-(' =>
                        array (
                            0 => 'icon_sad.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-)' =>
                        array (
                            0 => 'icon_smile.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-?' =>
                        array (
                            0 => 'icon_confused.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-D' =>
                        array (
                            0 => 'icon_biggrin.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-P' =>
                        array (
                            0 => 'icon_razz.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-o' =>
                        array (
                            0 => 'icon_surprised.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-x' =>
                        array (
                            0 => 'icon_mad.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':-|' =>
                        array (
                            0 => 'icon_neutral.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ';-)' =>
                        array (
                            0 => 'icon_wink.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    '8)' =>
                        array (
                            0 => 'icon_cool.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    '8O' =>
                        array (
                            0 => 'icon_eek.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':(' =>
                        array (
                            0 => 'icon_sad.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':)' =>
                        array (
                            0 => 'icon_smile.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':?' =>
                        array (
                            0 => 'icon_confused.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':D' =>
                        array (
                            0 => 'icon_biggrin.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':P' =>
                        array (
                            0 => 'icon_razz.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':o' =>
                        array (
                            0 => 'icon_surprised.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':x' =>
                        array (
                            0 => 'icon_mad.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':|' =>
                        array (
                            0 => 'icon_neutral.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ';)' =>
                        array (
                            0 => 'icon_wink.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':!:' =>
                        array (
                            0 => 'icon_exclaim.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                    ':?:' =>
                        array (
                            0 => 'icon_question.gif',
                            1 => '18',
                            2 => '18',
                            3 => 'question',
                        ),
                ),
        ),
    'version' =>
        array (
            'version' => '5.0',
        ),
    'view' =>
        array (
            'paths' =>
                array (
                    0 => realpath(base_path('resources/views')),
                ),
            'compiled' => realpath(storage_path('framework/views')),
        ),
    'sitemap' =>
        array (
            'use_cache' => false,
            'cache_key' => 'Laravel.Sitemap.http://localhost',
            'cache_duration' => 3600,
            'escaping' => true,
        ),
);