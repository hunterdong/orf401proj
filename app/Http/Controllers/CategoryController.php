<?php
namespace App\Http\Controllers;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use App\Artvenue\Repository\CategoryRepositoryInterface;
use App\Artvenue\Repository\ImageRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $category;

    public function __construct(CategoryRepositoryInterface $category, ImageRepositoryInterface $images)
    {
        $this->images = $images;
        $this->category = $category;
    }

    /**
     * Display images posted in particular category,
     * check if category exits or not if not then redirect to to gallery
     *
     * @param $category
     * @param Request $request
     * @return mixed
     */
    public function getIndex($category, Request $request)
    {
        $category = $this->category->getBySlug($category);

        $images = $this->images->getLatest($category->slug, $request->get('timeframe'));
        $title = sprintf('%s %s %s', t('Browsing'), ucfirst($category->name), t('Category'));

        return view('gallery.index', compact('images', 'title'));
    }

    /**
     * Generate RSS feed of each category
     *
     * @param $category
     * @return mixed
     */
    public function getRss($category)
    {
        $category = $this->category->getBySlug($category);

        return $this->category->getRss($category);
    }
}
