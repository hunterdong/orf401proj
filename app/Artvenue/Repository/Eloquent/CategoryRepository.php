<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Repository\Eloquent;

use App\Artvenue\Helpers\Resize;
use App\Artvenue\Models\Category;
use App\Artvenue\Repository\CategoryRepositoryInterface;
use App\Artvenue\Repository\ImageRepository;
use App\Artvenue\Repository\ImageRepositoryInterface;
use Illuminate\Support\Facades\URL;

class CategoryRepository implements CategoryRepositoryInterface
{

    /**
     * @var \Category
     */
    protected $model;

    public function __construct(Category $model, ImageRepositoryInterface $images)
    {
        $this->model = $model;
        $this->images = $images;
    }

    public function getBySlug($slug)
    {
        $category = $this->model->whereSlug($slug)->firstOrFail();

        return $category;
    }

    public function getRss(Category $category)
    {
        $images = $this->images->getLatest($category->slug, 60);
        $feed = app()->make('feed');
        $feed->title = siteSettings('siteName') . '/category/' . $category->name;
        $feed->description = siteSettings('siteName') . '/category/' . $category->name;
        $feed->link = URL::to('category/' . $category->slug);
        $feed->lang = 'en';
        foreach ($images as $post) {
            $desc = '<a href="' . route('image', ['id' => $post->id, 'slug' => $post->slug]) . '"><img src="' . Resize::image($post, 'mainImage') . '" /></a><br/><br/>
                <h2><a href="' . route('image', ['id' => $post->id, 'slug' => $post->slug]) . '">' . e($post->title) . '</a>
                by
                <a href="' . route('user', ['username' => $post->user->username]) . '">' . ucfirst($post->user->fullname) . '</a>
                ( <a href="' . route('user', ['username' => $post->user->username]) . '">' . $post->user->username . '</a> )
                </h2>' . $post->image_description;
            $feed->add(ucfirst(e($post->title)), $post->user->fullname, route('image', ['id' => $post->id, 'slug' => $post->slug]), $post->created_at, $desc);
        }

        return $feed->render('atom');
    }
}
