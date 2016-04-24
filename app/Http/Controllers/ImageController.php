<?php
namespace App\Http\Controllers;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use App\Artvenue\Helpers\ResizeHelper;
use App\Artvenue\Repository\FavoriteRepositoryInterface;
use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Http\Requests\Image\EditRequest;
use App\Http\Requests\Image\Favorite;
use Illuminate\Support\Facades\Crypt;

class ImageController extends Controller
{
    /**
     * @param ImageRepositoryInterface $image
     * @param FavoriteRepositoryInterface $favorite
     */
    public function __construct(ImageRepositoryInterface $image, FavoriteRepositoryInterface $favorite)
    {
        $this->image = $image;
        $this->favorite = $favorite;
    }

    /**
     * Display all details of image with it's comments
     * and replies send it view file.
     *
     * @param      $id
     * @param null $slug
     * @return mixed
     */
    public function getIndex($id, $slug = null)
    {
        $image = $this->image->getById($id);

        if (empty($slug) or $slug != $image->slug) {
            return redirect()->route('image', ['id' => $image->id, 'slug' => $image->slug], 301);
        }

        \Event::fire('App\Artvenue\Events', $image);

        $comments = $image->comments()->with('votes', 'user', 'replies', 'replies.user', 'replies.votes', 'replies.comment.user')->orderBy('created_at', 'desc')->paginate(10);

        $previous = $this->image->findPreviousImage($image);
        $next = $this->image->findNextImage($image);
        $title = ucfirst($image->title);

        return view('image.index', compact('image', 'comments', 'previous', 'next', 'title'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        if ($this->image->delete($id)) {
            return redirect()->route('gallery')->with('flashSuccess', t('Image is deleted permanently'));
        }

        return redirect()->route('gallery')->with('flashError', t('You are not allowed'));
    }

    /**
     * @param EditRequest $request
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getEdit(EditRequest $request)
    {
        $image = $this->image->getById($request->route('id'));

        $title = ucfirst($image->title);

        return view('image.edit', compact('image', 'title'));
    }

    /**
     * @param EditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(EditRequest $request)
    {
        $image = $this->image->update($request);

        return redirect()->route('image', ['id' => $image->id, 'slug' => $image->slug])->with('flashSuccess', t('Image is now updated'));
    }

    /**
     * @param        $id
     * @return mixed
     */
    public function download($id)
    {
        $id = Crypt::decrypt($id);
        $image = $this->image->getById($id);

        if (!$image or (siteSettings('allowDownloadOriginal') == 'leaveToUser' and $image->allow_download != 1)) {
            return redirect()->route('gallery')->with('flashError', t('You are not allowed to download this image'));
        }
        if (auth()->user()->id != $image->user_id) {
            $image->downloads = $image->downloads + 1;
            $image->save();
        }
        $file = new ResizeHelper($image->image_name . '.' . $image->type, 'uploads/images');
        $file = $file->download();

        return response()->download($file, $image->slug . '.' . $image->type, ['content-type' => 'image/jpg'])->deleteFileAfterSend(true);
    }

    /**
     * @param Favorite $request
     * @return string
     */
    public function postFavorite(Favorite $request)
    {
        return $this->favorite->favorite($request);
    }
}
