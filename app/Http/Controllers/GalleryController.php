<?php
namespace App\Http\Controllers;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Http\Controllers;
use Illuminate\Http\Request;


class GalleryController extends Controller
{

    /**
     * @param ImageRepositoryInterface $images
     */
    public function __construct(ImageRepositoryInterface $images)
    {
        $this->images = $images;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        $images = $this->images->getLatest($request->get('category'), $request->get('timeframe'));
        $title = t('Latest Images');

        return view('gallery/index', compact('images', 'title'));
    }

    /**
     * @return mixed
     */
    public function featured(Request $request)
    {
        $images = $this->images->getFeatured($request->get('category'), $request->get('timeframe'));
        $title = t('Featured Images');

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostCommented(Request $request)
    {
        $images = $this->images->mostCommented($request->get('category'), $request->get('timeframe'));
        $title = t('Most Commented');

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostFavorited(Request $request)
    {
        $images = $this->images->mostFavorited($request->get('category'), $request->get('timeframe'));
        $title = t('Most Favorites');

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostDownloaded(Request $request)
    {
        $images = $this->images->mostDownloaded($request->get('category'), $request->get('timeframe'));
        $title = t('Popular');

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostPopular(Request $request)
    {
        $images = $this->images->popular($request->get('category'), $request->get('timeframe'));
        $title = t('Popular');

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * @return mixed
     */
    public function mostViewed(Request $request)
    {
        $images = $this->images->mostViewed($request->get('category'), $request->get('timeframe'));
        $title = t('Most Viewed');

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * @param $tag
     * @return mixed
     */
    public function getByTags($tag)
    {
        $images = $this->images->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged With'), ucfirst($tag));

        return view('gallery.index', compact('images', 'title'));
    }


    /**
     * @return mixed
     */
    public function search(Request $request)
    {
        $this->validate($request, ['q' => 'required']);

        $images = $this->images->search($request->get('q'), $request->get('category'), $request->get('timeframe'));

        $title = sprintf('%s %s', t('Searching for'), ucfirst($request->get('q')));

        return view('gallery.index', compact('title', 'images'));
    }
}