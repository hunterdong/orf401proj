<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository\Eloquent;

use App\Artvenue\Notifier\ImageNotifier;
use Auth;
use App\Artvenue\Models\Image;
use Post;
use Votes;

class VotesRepository extends AbstractRepository implements VotesRepositoryInterface
{

    /**
     * @param Vote         $model
     * @param ImageNotifier $notifier
     */
    public function  __construct(Vote $model, ImageNotifier $notifier)
    {
        $this->model = $model;
        $this->notifier = $notifier;
    }

    /**
     * @param Images $images
     * @return bool
     */
    public function vote(Image $images)
    {
        $vote = $this->getNew();
        $vote->image_id = $images->id;
        $vote->user_id = auth()->user()->id;
        $vote->save();

        $this->notifier->vote($images, auth()->user());

        return true;
    }
}