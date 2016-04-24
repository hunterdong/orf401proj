<?php
namespace App\Http\Controllers;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use App\Artvenue\Helpers\Resize;
use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Http\Controllers;
use Roumen\Feed\Facades\Feed;


class TagsController extends Controller
{

    /**
     * @param ImageRepositoryInterface $images
     */
    public function __construct(ImageRepositoryInterface $images)
    {
        $this->images = $images;
    }

    /**
     * @param $tag
     * @return mixed
     */
    public function getIndex($tag)
    {
        $images = $this->images->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged with'), ucfirst($tag));

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * @param $tag
     * @return mixed
     */
    public function getRss($tag)
    {

        $images = $this->images->getByTags($tag);
        $feed = Feed::make();
        $feed->title = siteSettings('siteName') . '/tag/' . $tag;
        $feed->description = siteSettings('siteName') . '/tag/' . $tag;
        $feed->link = url('tag/' . $tag);
        $feed->lang = 'en';
        foreach ($images as $post) {
            // set item's title, author, url, pubdate and description
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