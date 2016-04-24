<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers;

use App\Artvenue\Helpers\ResizeHelper;
use App\Artvenue\Repository\FollowRepositoryInterface;
use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Artvenue\Repository\UsersRepositoryInterface;
use App\Http\Requests\User\UpdateAvatar;
use App\Http\Requests\User\UpdatePassword;
use App\Http\Requests\User\UpdateProfile;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @param UsersRepositoryInterface  $user
     * @param ImageRepositoryInterface  $images
     * @param FollowRepositoryInterface $follow
     */
    public function __construct(UsersRepositoryInterface $user, ImageRepositoryInterface $images, FollowRepositoryInterface $follow)
    {
        $this->user = $user;
        $this->images = $images;
        $this->follow = $follow;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUser($user)
    {
        $user = $this->user->getByUsername($user);
        $images = $this->user->getUsersImages($user);
        $mostUsedTags = mostTags($images->lists('tags'));
        $title = ucfirst($user->fullname);

        return view('user.index', compact('user', 'title', 'images', 'mostUsedTags'));
    }


    /**
     * @param $user
     * @return mixed
     */
    public function getFavorites($user)
    {
        $user = $this->user->getByUsername($user);
        $images = $this->user->getUsersFavorites($user);
        $mostUsedTags = mostTags($user->images()->lists('tags'));
        $title = $user->fullname;

        return view('user.favorites', compact('user', 'images', 'title', 'mostUsedTags'));

    }

    /**
     * @param $username
     * @return mixed
     */
    public function getFollowers($username)
    {
        $user = $this->user->getUsersFollowers($username);
        $mostUsedTags = mostTags($user->images()->lists('tags'));
        $title = $user->fullname;

        return view('user.followers', compact('user', 'title', 'mostUsedTags'));
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getFollowing($username)
    {
        $user = $this->user->getUsersFollowing($username);
        if ($user->id != auth()->user()->id) {
            return redirect()->route('home');
        }
        $mostUsedTags = mostTags($user->images()->lists('tags'));
        $title = $user->fullname;

        return view('user.following', compact('user', 'title', 'mostUsedTags'));
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getRss($user)
    {
        return $this->user->getUsersRss($user);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $users = $this->user->getTrendingUsers();
        $title = t('Users');

        return view('user.users', compact('users', 'title'));
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        $notifications = $this->user->notifications(auth()->user()->id);
        $title = t('Notifications');

        return view('user.notifications', compact('notifications', 'title'));
    }


    /**
     * @return mixed
     */
    public function getFeeds()
    {
        $images = $this->user->getFeedForUser();
        $title = t('Feeds');

        return view('gallery/index', compact('images', 'title'));
    }

    /**
     * @return string
     */
    public function follow(Request $request)
    {
        return $this->follow->follow($request->get('id'));
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        $user = auth()->user();
        $title = t('Settings');

        return view('user.settings', compact('user', 'title'));
    }

    /**
     * @param UpdateAvatar $request
     * @return mixed
     */
    public function postUpdateAvatar(UpdateAvatar $request)
    {
        $i = new ResizeHelper(auth()->user()->avatar, 'uploads/avatars');
        $i->delete();

        $i = new ResizeHelper($request->file('avatar'), 'uploads/avatars');
        list($name, $type) = $i->saveOriginal();

        $update = auth()->user();
        $update->avatar = sprintf('%s.%s', $name, $type);
        $update->save();

        return redirect()->back()->with('flashSuccess', t('Your avatar is now updated'));
    }


    /**
     * @return mixed
     */
    public function postUpdateProfile(UpdateProfile $request)
    {
        $this->user->updateProfile($request);

        return redirect()->back()->with('flashSuccess', t('Your profile is updated'));
    }

    /**
     * @return mixed
     */
    public function postChangePassword(UpdatePassword $request)
    {
        if ( ! $this->user->updatePassword($request)) {
            return redirect()->back()->with('flashError', t('Old password is not valid'));
        }

        return redirect()->back()->with('flashSuccess', t('Your password is updated'));
    }

    /**
     * @return mixed
     */
    public function postMailSettings(Request $request)
    {
        $this->user->updateMail($request);

        return redirect()->back()->with('flashSuccess', t('Your mail settings are now update'));
    }
}