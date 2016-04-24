<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository\Eloquent;

use App\Artvenue\Helpers\Resize;
use App\Artvenue\Mailers\UserMailer;
use App\Artvenue\Models\Image;
use App\Artvenue\Models\User;
use App\Artvenue\Repository\UsersRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UsersRepository extends AbstractRepository implements UsersRepositoryInterface
{
    /**
     * @param UserMailer $mailer
     * @param User $user
     * @param Image $images
     */
    public function __construct(UserMailer $mailer, User $user, Image $images)
    {
        $this->model = $user;
        $this->mailer = $mailer;
        $this->images = $images;
    }

    /**
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $user = $this->model->whereId($id)->firstOrFail();

        return $user;
    }

    /**
     * @param $username
     * @return bool
     */
    public function getByUsername($username)
    {
        $user = $this->model->where('username', $username)->with('followers.user')->firstOrFail();

        return $user;
    }

    /**
     * @return mixed
     */
    public function getAllUsers()
    {
        return $this->model->confirmed()->with('latestImages', 'comments')->paginate(perPage());
    }

    /**
     * @return mixed
     */
    public function getTrendingUsers()
    {
        return $this->model->confirmed()
            ->leftJoin('comments', 'comments.user_id', '=', 'users.id')
            ->leftJoin('images', 'images.user_id', '=', 'users.id')
            ->select('users.*', DB::raw('count(comments.user_id)*5 + count(images.user_id)*2 as popular'))
            ->groupBy('users.id')->with('images', 'latestImages', 'comments')->orderBy('popular', 'desc')
            ->paginate(perPage());
    }


    /**
     * @param User $user
     * @internal param $username
     * @return mixed
     */
    public function getUsersFavorites(User $user)
    {
        $images = $user->favorites()->lists('image_id');
        if (!$images) {
            $images = [null];
        }

        return $this->images->approved()->whereIn('id', $images)->orderBy('approved_at', 'desc')->paginate(perPage());
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getUsersFollowers($username)
    {
        return $this->model->whereUsername($username)->with('followers')->first();
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getUsersFollowing($username)
    {
        return $this->model->whereUsername($username)->with('following.followingUser')->first();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getUsersImages(User $user)
    {
        return $this->images->approved()->whereUserId($user->id)->with('comments', 'favorites', 'user', 'category')->orderBy('approved_at', 'desc')->paginate(perPage());
    }

    /**
     * @param $request
     * @return bool
     */
    public function createNew($request)
    {
        $activationCode = sha1(str_random(11) . (time() * rand(2, 2000)));

        $this->model->username = $request->get('username');
        $this->model->fullname = $request->get('fullname');
        $this->model->gender = $request->get('gender');
        $this->model->email = $request->get('email');
        $this->model->password = bcrypt($request->get('password'));
        $this->model->email_confirmation = $activationCode;
        $this->model->save();

        $this->mailer->activation($this->model, $activationCode);

        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function notifications($id)
    {
        $user = $this->model->whereId($id)->with('notifications')->first();
        $notices = $user->notifications()->orderBy('created_at', 'desc')->paginate(perPage());
        foreach ($notices as $notice) {
            if (!$notice->is_read) {
                $notice->is_read = 1;
                $notice->save();
            }
        }

        return $notices;
    }

    /**
     * @param $username
     * @param $activationCode
     * @return bool
     */
    public function activate($username, $activationCode)
    {
        $user = $this->model->whereUsername($username)->first();
        if ($user && $user->email_confirmation === $activationCode) {
            $user->confirmed_at = Carbon::now();
            $user->save();

            return $user;
        }

        return false;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function registerViaSocial($request)
    {
        $this->model->username = $request->get('username');
        $this->model->password = bcrypt($request->get('password'));
        $this->model->fullname = $request->session()->get($request->route('provider') . '_register')->getName();
        if (isset($request->session()->get($request->route('provider') . '_register')->user['gender'])) {
            $this->model->gender = strtolower($request->session()->get($request->route('provider') . '_register')->user['gender']);
        }
        if ($request->session()->has('facebook_register')) {
            $this->model->email = $request->session()->get($request->route('provider') . '_register')->getEmail();
            $this->model->fbid = $request->session()->get('facebook_register')->getId();
            Session::forget('facebook_register');
        }
        if ($request->session()->has('google_register')) {
            $this->model->email = $request->session()->get($request->route('provider') . '_register')->getEmail();
            $this->model->gid = $request->session()->get('google_register')->getId();
            Session::forget('google_register');
        }
        if ($request->session()->has('twitter_register') and session('user_email')) {
            $activationCode = sha1(str_random(11) . (time() * rand(2, 2000)));
            $this->model->email = session('user_email');
            $this->model->twid = $request->session()->get('twitter_register')->getId();
            $this->model->email_confirmation = $activationCode;
            $this->model->save();
            $this->mailer->activation($this->model, $activationCode);
            Session::forget('twitter_register');

            return true;
        }
        $this->model->confirmed_at = Carbon::now();
        $this->model->save();
        auth()->loginUsingId($this->model->id);

        return $this->model;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $user->fullname = $request->get('fullname');
        $user->dob = $request->get('dob');
        $user->gender = $request->get('gender');
        $user->country = $request->get('country');
        $user->about_me = $request->get('about_me') ? $request->get('about_me') : null;
        $user->blogurl = $request->get('blogurl') ? $request->get('blogurl') : null;
        $user->fb_link = $request->get('fb_link') ? $request->get('fb_link') : null;
        $user->tw_link = $request->get('tw_link') ? $request->get('tw_link') : null;
        $user->save();

        return $user;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function updateMail(Request $request)
    {
        $user = $request->user();

        $user->email_comment = $request->get('email_comment') ? 1 : 0;
        $user->email_reply = $request->get('email_reply') ? 1 : 0;
        $user->email_favorite = $request->get('email_favorite') ? 1 : 0;
        $user->email_follow = $request->get('email_follow') ? 1 : 0;
        $user->save();

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function updatePassword(Request $request)
    {
        if (Hash::check($request->get('currentpassword'), $request->user()->password)) {
            $user = auth()->user();
            $user->password = bcrypt($request->get('password'));
            $user->save();

            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getFeedForUser()
    {
        $following = auth()->user()->following()->lists('follow_id');
        if (!$following) {
            $following = [null];
        }

        return $this->images->whereIn('user_id', $following)->orderBy('approved_at', 'desc')->paginate(perPage());
    }


    /**
     * @param $username
     * @return mixed
     */
    public function getUsersRss($username)
    {
        $user = User::whereUsername($username)->first();
        $images = Image::approved()->whereUserId($user->id)->orderBy('created_at', 'desc')->take(60)->get();

        $feed = app()->make('feed');
        $feed->title = siteSettings('siteName') . '/user/' . $user->username;
        $feed->description = siteSettings('siteName') . '/user/' . $user->username;
        $feed->link = route('user', ['username' => $user->username]);
        $feed->lang = 'en';

        foreach ($images as $post) {
            $desc = '<a href="' . route('image', ['id' => $post->id, 'slug' => $post->slug]) . '"><img src="' . Resize::image($post, 'mainImage') . '" /></a><br/><br/>
                <h2><a href="' . route('image', ['id' => $post->id, 'slug' => $post->slug]) . '">' . $post->title . '</a>
                by
                <a href="' . route('user', ['username' => $post->user->username]) . '">' . $user->fullname . '</a>
                ( <a href="' . route('user', ['username' => $post->user->username]) . '">' . $user->username . '</a> )
                </h2>' . $post->image_description;
            $feed->add(ucfirst($post->title), $user->fullname, route('image', ['id' => $post->id, 'slug' => $post->slug]), $post->created_at, $desc);
        }

        return $feed->render('atom');
    }
}
