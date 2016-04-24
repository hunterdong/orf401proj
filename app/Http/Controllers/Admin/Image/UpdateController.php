<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers\Admin\Image;

use App\Artvenue\Helpers\Resize;
use App\Artvenue\Helpers\ResizeHelper;
use App\Artvenue\Models\Category;
use App\Artvenue\Models\Image;
use App\Artvenue\Models\ImageInfo;
use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    /**
     * @param ImageRepositoryInterface $image
     */
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->image = $image;
    }

    /**
     * @param $id
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $image = Image::whereId($id)->with('user', 'comments', 'comments.replies', 'favorites', 'info')->firstOrFail();
        $title = 'Editing image';

        return view('admin.image.edit', compact('image', 'title'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function approve(Request $request)
    {
        $image = Image::whereId($request->get('id'))->first();
        if (!$image) {
            return 'Error';
        }
        if ($request->get('approve') == 1) {
            $image->approved_at = Carbon::now();
            $image->save();

            return 'Approved';
        }
        if ($request->get('approve') == 0) {
            $delete = new ResizeHelper(sprintf('%s.%s', $image->image_name, $image->type), 'uploads/images');
            $delete->delete();
            $image->delete();

            return 'Deleted';
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(Request $request)
    {
        $image = Image::whereId($request->route('id'))->firstOrFail();
        if ($request->get('delete')) {
            $delete = new ResizeHelper(sprintf('%s.%s', $image->image_name, $image->type), 'uploads/images');
            $delete->delete();
            $image->favorites()->delete();
            $comments = $image->comments()->get();
            foreach ($comments as $comment) {
                $comment->votes()->delete();
                foreach ($comment->reply()->get() as $reply) {
                    $reply->votes()->delete();
                    $reply->delete();
                }
                $comment->delete();
            }
            $image->info()->delete();
            $image->delete();

            return redirect()->route('admin.images')->with('flashSuccess', 'Image is now deleted permanently');
        }
        if (Category::whereId($request->get('category'))->count() != 1) {
            return redirect()->back()->with('flashError', t('Invalid category'));
        }

        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        } else {
            $tags = null;
        }
        $slug = str_slug($request->get('title'));
        if (!$slug) {
            $slug = str_random(8);
        }
        $image->title = $request->get('title');
        $image->slug = $slug;
        $image->is_adult = $request->get('is_adult');
        $image->image_description = $request->get('description');
        $image->category_id = $request->get('category');
        $image->tags = $tags;

        if ($request->get('featured_at') && $image->featured_at == null) {
            $image->featured_at = Carbon::now();
        } elseif ($request->get('featured_at') == null && $image->featured_at) {
            $image->featured_at = null;
        }

        $image->save();

        return redirect()->back()->with('flashSuccess', 'Image is now updated');
    }


    /**
     * @param Request $request
     * @return string
     */
    public function clearCache(Request $request)
    {
        $image = Image::whereId($request->get('id'))->firstOrFail();
        $cache = new ResizeHelper(sprintf('%s.%s', $image->image_name, $image->type), 'uploads/images');
        $cache->clearCache();

        return 'Cache is cleared, reload the page';
    }

    /**
     * @param Request $request
     */
    public function postBulkUpload(Request $request)
    {
        $file = $request->file('files')[0];
        $info = $request->get('photo');

        $save = new ResizeHelper($file, 'uploads/images');
        list($imageName, $mimetype) = $save->saveOriginal();

        $tags = null;
        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        }

        $description = null;

        $title = str_replace(['.jpg', '.jpeg', '.png', '.gif'], '', $file->getClientOriginalName());
        $slug = str_slug($title);
        if (!$slug) {
            $slug = str_random(9);
        }

        switch (siteSettings('allowDownloadOriginal')) {
            case 'leaveToUser':
                $allowDownload = (int)$info['allow_download'];
                break;
            case 0:
            case '0':
                $allowDownload = 0;
                break;
            default:
                $allowDownload = 1;
        }

        sleep(1);
        $approved_at = Carbon::now();
        $image = new Image();
        $image->user_id = $request->user()->id;
        $image->image_name = $imageName;
        $image->title = $title;
        $image->slug = $slug;
        $image->category_id = $request->get('category_id');
        $image->type = $mimetype;
        $image->tags = $tags;
        $image->image_description = $description;
        $image->allow_download = $allowDownload;
        $image->is_adult = $request->get('is_adult');
        $image->approved_at = $approved_at;
        $image->save();

        isset($info['exif']) ? $exif = $info['exif'] : $exif = null;

        $taken_at = null;
        if (isset($exif['taken']) && strlen($exif['taken'][0]) > 0) {
            $date = @explode('/', $exif['taken'][0]);
            $time = @explode(':', $exif['taken'][1]);
            $taken_at = @Carbon::create($date[0], $date[1], $date[2], $time[0], $time[1], 00)->toDateTimeString();
        }

        $latitude = null;
        $longitude = null;
        if (isset($info['latitude']) && isset($info['longitude'])) {
            if (!empty($info['latitude']) && !empty($info['longitude']) && $info['latitude'] != '' && $info['longitude'] != '') {
                $latitude = doubleval($info['latitude']);
                $longitude = doubleval($info['longitude']);
            }
        }

        $exif = [
            'camera'        => (isset($exif['camera']) && strlen($exif['camera']) > 0 ? $exif['camera'] : null),
            'lens'          => (isset($exif['lens']) && strlen($exif['lens']) > 0 ? $exif['lens'] : null),
            'focal_length'  => (isset($exif['focalLength']) && strlen($exif['focalLength']) > 0 ? $exif['focalLength'] : null),
            'shutter_speed' => (isset($exif['shutterspeed']) && strlen($exif['shutterspeed']) > 0 ? $exif['shutterspeed'] : null),
            'aperture'      => (isset($exif['aperture']) && strlen($exif['aperture']) > 0 ? $exif['aperture'] : null),
            'iso'           => (isset($exif['iso']) && strlen($exif['iso']) > 0 ? $exif['iso'] : null),
            'taken_at'      => $taken_at,
            'latitude'      => $latitude,
            'longitude'     => $longitude,
        ];

        $info = new ImageInfo($exif);
        $image->info()->create($exif);


        return [
            'files' => [
                0 => [
                    'success'      => 'Uploaded',
                    'successSlug'  => route('image', ['id' => $image->id, 'slug' => $image->slug]),
                    'successTitle' => ucfirst($image->title),
                    'thumbnail'    => Resize::image($image, 'gallery')
                ]
            ]
        ];
    }
}
