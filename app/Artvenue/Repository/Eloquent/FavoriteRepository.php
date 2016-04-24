<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository\Eloquent;

use App\Artvenue\Models\Favorite;
use App\Artvenue\Models\Image;
use App\Artvenue\Notifier\ImageNotifier;
use App\Artvenue\Repository\FavoriteRepositoryInterface;
use Illuminate\Http\Request;

class FavoriteRepository extends AbstractRepository implements FavoriteRepositoryInterface
{
    /**
     * @param Favorite $model
     * @param Image $images
     * @param ImageNotifier $notifer
     */
    public function __construct(Favorite $model, Image $images, ImageNotifier $notifer)
    {
        $this->model = $model;
        $this->images = $images;
        $this->notification = $notifer;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function favorite(Request $request)
    {
        $favorite = $this->model->whereImageId($request->get('id'))->whereUserId($request->user()->id);
        if ($favorite->count() >= 1) {
            $favorite->delete();

            return t('Un-Favorited');
        }
        $favorite = $this->getNew();
        $favorite->user_id = $request->user()->id;
        $favorite->image_id = $request->get('id');
        $favorite->save();
        $this->notification->favorite($favorite->image, $request->user());

        return t('Favorited');
    }
}
