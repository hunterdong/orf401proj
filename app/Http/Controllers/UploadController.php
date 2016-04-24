<?php
namespace App\Http\Controllers;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use App\Artvenue\Helpers\ResizeHelper;
use App\Artvenue\Models\Image;
use App\Artvenue\Models\ImageInfo;
use App\Http\Requests\Image\ImageUpload;
use App\Http\Requests\Image\Upload;
use Carbon\Carbon;

class UploadController extends Controller
{
    /**
     * @return mixed
     */
    public function getIndex()
    {
        $title = t('Upload');
        $numberOfUploadByUser = auth()->user()->images()->where('created_at', '>=', Carbon::now()->subDays(1)->toDateTimeString())->count();
        $numberOfUploadByUser >= limitPerDay() ? $limitReached = true : $limitReached = false;

        return view('image.upload', compact('title', 'numberOfUploadByUser', 'limitReached'));
    }

    /**
     * @param ImageUpload $request
     * @return string
     */
    public function postUpload(ImageUpload $request)
    {
        $file = $request->file('files');
        $info = $request->get('photo');

        $file = new ResizeHelper($file, 'uploads/images');
        list($imageName, $mimetype) = $file->saveOriginal();

        $tags = null;
        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        }

        $description = preg_replace('/\R\R+/u', "\n\n", trim($info['description']));
        $slug = str_slug($info['title']);
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
        $approved_at = (int)siteSettings('autoApprove') == 0 ? null : Carbon::now();

        $image = new Image();
        $image->user_id = $request->user()->id;
        $image->image_name = $imageName;
        $image->title = $info['title'];
        $image->slug = $slug;
        $image->category_id = $info['category'];
        $image->type = $mimetype;
        $image->tags = $tags;
        $image->image_description = $description;
        $image->allow_download = $allowDownload;
        $image->is_adult = $info['adult'];
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
    }
}
