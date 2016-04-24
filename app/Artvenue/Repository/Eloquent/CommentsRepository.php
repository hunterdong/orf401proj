<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository\Eloquent;

use App\Artvenue\Models\Comment;
use App\Artvenue\Models\CommentsVotes;
use App\Artvenue\Models\Image;
use App\Artvenue\Notifier\ImageNotifier;
use App\Artvenue\Repository\CommentsRepositoryInterface;
use Auth;

class CommentsRepository extends AbstractRepository implements CommentsRepositoryInterface
{
    /**
     * @param Comment $comment
     * @param Image $images
     * @param ImageNotifier $notifications
     * @param CommentsVotes $votes
     */
    public function __construct(Comment $comment, Image $images, ImageNotifier $notifications, CommentsVotes $votes)
    {
        $this->model = $comment;
        $this->images = $images;
        $this->notifications = $notifications;
        $this->votes = $votes;
    }

    /**
     * @param $request
     * @return bool
     */
    public function create($request)
    {
        $comment = $this->getNew();
        $comment->user_id = $request->user()->id;
        $comment->image_id = $request->route('id');
        $comment->comment = preg_replace("/[\r\n]+/", "\n", $request->get('comment'));

        $comment->save();
        if ($request->user()->id != $comment->image->user_id) {
            $this->notifications->comment($comment->image, $request->user(), $request->get('comment'));
        }

        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $commentOwner = $this->model->whereId($id)->first();
        if (!$commentOwner) {
            return false;
        }
        if ($commentOwner->user_id == auth()->user()->id || auth()->user()->id == $commentOwner->image->user->id) {
            $commentOwner->votes()->delete();
            $commentOwner->delete();

            return true;
        }

        return false;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function vote($request)
    {
        $comment = $this->getById($request->get('id'));
        $vote = $comment->votes()->whereUserId($request->user()->id)->first();
        if ($vote !== null) {
            $vote->delete();

            return $comment->votes()->count();
        }
        $vote = $this->votes->newInstance();
        $vote->comment_id = $request->get('id');
        $vote->user_id = $request->user()->id;
        $vote->save();

        return $comment->votes()->count();
    }

    /**
     * @param $id
     * @return bool
     */
    public function getById($id)
    {
        $comment = $this->model->whereId($id)->firstOrFail();

        return $comment;
    }
}
