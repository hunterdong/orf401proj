<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository\Eloquent;

use App\Artvenue\Helpers\ResizeHelper;
use App\Artvenue\Models\Category;
use App\Artvenue\Models\Image;
use App\Artvenue\Notifier\ImageNotifier;
use App\Artvenue\Repository\FavoriteRepositoryInterface;
use App\Artvenue\Repository\ImageRepositoryInterface;
use Auth;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Support\Facades\Cache;

class ImageRepository implements ImageRepositoryInterface
{
    /**
     * @param Image $images
     * @param ImageNotifier $notice
     * @param Category $category
     * @param FavoriteRepositoryInterface $favorite
     */
    public function __construct(Image $images, ImageNotifier $notice, Category $category, FavoriteRepositoryInterface $favorite)
    {
        $this->model = $images;
        $this->category = $category;
        $this->notification = $notice;
        $this->favorite = $favorite;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->posts()->where('id', $id)->with('user', 'comments', 'favorites', 'info')->firstOrFail();
    }

    /**
     * @param null $category
     * @param null $timeframe
     * @return Post
     */
    private function posts($category = null, $timeframe = null)
    {
        $posts = $this->model->approved();

        if ($category = $this->category->select('id')->whereSlug($category)->first()) {
            $posts = $posts->whereCategoryId($category->id);
        }
        if ($this->resolveTime($timeframe)) {
            $posts = $posts->whereBetween('approved_at', $this->resolveTime($timeframe));
        }

        return $posts;
    }

    /**
     * @param $time
     * @return string
     */
    private function resolveTime($time)
    {
        switch ($time) {
            case 'today':
                $time = [Carbon::now()->subHours(24)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            case 'week':
                $time = [Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            case 'month':
                $time = [Carbon::now()->subDays(30)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            case 'year':
                $time = [Carbon::now()->subDays(365)->toDateTimeString(), Carbon::now()->toDateTimeString()];
                break;
            default:
                $time = null;
        }

        return $time;
    }

    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function getLatest($category = null, $timeframe = null)
    {
        $images = $this->posts($category, $timeframe)->orderBy('approved_at', 'desc')->with('user', 'comments', 'favorites');

        return $images->paginate(perPage());
    }

    /**
     * @param null $category
     * @param null $timeframe
     *
     * @return mixed
     */
    public function getFeatured($category = null, $timeframe = null)
    {
        $images = $this->posts($category, $timeframe)->whereNotNull('featured_at')->orderBy('featured_at', 'dec')->with('user', 'comments', 'favorites');

        return $images->paginate(perPage());
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function update($input)
    {
        if ($input->get('tags')) {
            $tags = implode(',', $input->get('tags'));
        } else {
            $tags = null;
        }
        $slug = str_slug($input->get('title'));
        if (!$slug) {
            $slug = str_random(8);
        }
        $image = $this->model->whereId($input->route('id'))->first();
        $image->title = $input->get('title');
        $image->slug = $slug;
        $image->image_description = $input->get('description');
        $image->category_id = $input->get('category');
        $image->tags = $tags;
        $image->save();

        return $image;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $image = $this->model->whereId($id)->first();
        if ($image and $image->user->id == auth()->user()->id) {
            $delete = new ResizeHelper(sprintf('%s.%s', $image->image_name, $image->type), 'uploads/images');
            $delete->delete();
            $image->favorites()->delete();
            $comments = $image->comments()->get();
            foreach ($comments as $comment) {
                $comment->votes()->delete();
                $replies = $comment->replies()->get();
                foreach ($replies as $reply) {
                    $reply->votes()->delete();
                    $reply->delete();
                }
                $comment->delete();
            }
            $image->info()->delete();
            $image->delete();
            Cache::forget('moreFromSite');

            return true;
        }

        return false;
    }

    /**
     * @param      $tag
     * @return mixed
     */
    public function getByTags($tag)
    {
        $images = $this->posts()->where('tags', 'LIKE', '%' . $tag . '%')->orderBy('approved_at', 'desc')->with('user');

        return $images->paginate(perPage());
    }

    /**
     * @param $image
     * @return mixed
     */
    public function incrementViews($image)
    {
        $image->views = $image->views + 1;
        $image->timestamps = false;
        $image->save(['updated_at' => false]);

        return $image;
    }

    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostCommented($category = null, $timeframe = null)
    {
        $images = $this->posts($category, $timeframe)->with('user', 'comments', 'favorites')->approved()->join('comments', 'comments.image_id', '=', 'images.id')
            ->select('images.*', DB::raw('count(comments.image_id) as cmts'))
            ->groupBy('images.id')->with('user', 'comments', 'favorites')->orderBy('cmts', 'desc')
            ->paginate(perPage());;

        return $images;
    }

    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function popular($category = null, $timeframe = null)
    {
        $images = $this->posts($category, $timeframe)
            ->leftJoin('comments', 'comments.image_id', '=', 'images.id')
            ->leftJoin('favorites', 'favorites.image_id', '=', 'images.id')
            ->select('images.*', DB::raw('count(comments.image_id)*5 + images.views as popular'))
            ->groupBy('images.id')->with('user', 'comments', 'favorites')->orderBy('popular', 'desc')
            ->paginate(perPage());

        return $images;
    }

    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostFavorited($category = null, $timeframe = null)
    {
        $images = $this->posts($category, $timeframe)->join('favorites', 'favorites.image_id', '=', 'images.id')
            ->select('images.*', DB::raw('count(favorites.image_id) as favs'))
            ->groupBy('images.id')->with('user', 'comments', 'favorites')->orderBy('favs', 'desc')
            ->paginate(perPage());

        return $images;
    }

    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostDownloaded($category = null, $timeframe = null)
    {
        $images = $this->posts($category, $timeframe)->orderBy('downloads', 'desc')->with('user', 'comments', 'favorites')->paginate(perPage());

        return $images;
    }

    /**
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function mostViewed($category = null, $timeframe = null)
    {
        $images = $this->posts($category, $timeframe)->orderBy('views', 'desc')->with('user', 'comments', 'favorites')->paginate(perPage());

        return $images;
    }

    /**
     * @param      $search
     * @param null $category
     * @param null $timeframe
     * @return mixed
     */
    public function search($search, $category = null, $timeframe = null)
    {
        $extends = explode(' ', $search);
        if ($category) {
            $categoryId = $this->category->whereSlug($category)->first();
        }
        $images = $this->posts($category, $timeframe)->where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('tags', 'LIKE', '%' . $search . '%')
            ->whereNull('deleted_at')->whereNotNull('approved_at')->orderBy('approved_at', 'desc');

        foreach ($extends as $extend) {
            if (isset($categoryId)) {
                $images->whereCategoryId($categoryId)->Where('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->whereCategoryId($categoryId)->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->whereCategoryId($categoryId)->orWhere('image_description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');
            } else {
                $images->orWhere('tags', 'LIKE', '%' . $extend . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->orWhere('title', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at')
                    ->orWhere('image_description', 'LIKE', '%' . $search . '%')->whereNotNull('approved_at')->whereNull('deleted_at');
            }
        }

        return $images = $images->with('user', 'comments', 'favorites')->whereNotNull('approved_at')->whereNull('deleted_at')->paginate(perPage());
    }

    /**
     * @param Image $image
     * @return mixed
     */
    public function findNextImage(Image $image)
    {
        $next = $this->model->where('approved_at', '>=', $image->approved_at)
            ->whereNotIn('id', [$image->id])
            ->orderBy('approved_at', 'asc')
            ->first(['id', 'slug', 'title']);

        return $next;
    }

    /**
     * @param Image $image
     * @return mixed
     */
    public function findPreviousImage(Image $image)
    {
        $prev = $this->model->where('approved_at', '<=', $image->approved_at)
            ->whereNotIn('id', [$image->id])
            ->orderBy('approved_at', 'desc')
            ->first(['id', 'slug', 'title']);

        return $prev;
    }
}
