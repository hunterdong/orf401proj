<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository\Eloquent;

use App\Artvenue\Models\Follow;
use App\Artvenue\Models\User;
use App\Artvenue\Notifier\FollowNotifier;
use App\Artvenue\Repository\FollowRepositoryInterface;
use App\Artvenue\Repository\UsersRepositoryInterface;

class FollowRepository extends AbstractRepository implements FollowRepositoryInterface
{

    /**
     * @param FollowNotifier $notice
     * @param Follow $model
     * @param UsersRepositoryInterface $user
     */
    public function __construct(FollowNotifier $notice, Follow $model, UsersRepositoryInterface $user)
    {
        $this->model = $model;
        $this->notifications = $notice;
        $this->user = $user;
    }

    /**
     * @param $id
     * @return string
     */
    public function follow($id)
    {
        if (auth()->user()->id == $id || !$this->user->getById($id)) {
            return t("Can't follow");
        }

        /**
         * Check if following,
         * If following then un-follow
         * else follow
         */
        $isFollowing = $this->model->whereUserId(auth()->user()->id)->whereFollowId($id);
        if ($isFollowing->count() >= 1) {
            $isFollowing->delete();

            return t('Un-Followed');
        }
        $follow = $this->getNew();
        $follow->user_id = auth()->user()->id;
        $follow->follow_id = $id;
        $follow->save();
        // Send notice to user who is getting followed
        $this->notifications->follow(User::find($id), auth()->user());

        return t('Following');
    }
}