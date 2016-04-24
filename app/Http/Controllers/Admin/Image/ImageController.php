<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers\Admin\Image;

use App\Artvenue\Helpers\Resize;
use App\Artvenue\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function getIndex(Request $request)
    {
        $title = sprintf('List of %s images', ucfirst($request->get('type')));
        $type = $request->get('type');

        return view('admin.image.index', compact('title', 'type'));
    }

    /**
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function getBulkUpload()
    {
        $title = sprintf('Bulkupload');

        return view('admin.image.bulkupload', compact('title'));
    }

    /**
     * @return mixed
     */
    public function getData(Request $request)
    {
        $images = Image::select([
            'images.*',
            DB::raw('count(favorites.image_id) as favorites'),
            DB::raw('users.fullname as fullname'),
        ])->leftJoin('favorites', 'favorites.image_id', '=', 'images.id')
            ->leftJoin('users', 'users.id', '=', 'images.user_id')
            ->groupBy('images.id');

        switch ($request->get('type')) {
            case 'approved':
                $images->approved();
                break;
            case 'featured':
                $images->whereNotNull('images.featured_at');
                break;
            case 'approvalRequired':
                $images->whereNull('images.approved_at');
                break;
            default:
                $images->approved();
        }

        $datatables = Datatables::of($images);

        if ($request->get('type') == 'approvalRequired') {
            $datatables->addColumn('actions', function ($image) {
                return '<a href="#" class="image-approve btn btn-success" data-approve="' . $image->id . '"><i class="fa fa-check"></i> Approve </a>
                 <a href="' . route('admin.images.edit', [$image->id]) . '" class="btn btn-info" target="_blank"><i class="fa fa-edit"></i> Edit </a>
                <a href="#" class="image-disapprove btn btn-danger" data-disapprove="' . $image->id . '"><i class="fa fa-times"></i> Delete</a>';
            });
        } else {
            $datatables->addColumn('actions', function ($image) {
                return '<a href="' . route('admin.images.edit', [$image->id]) . '" class="btn btn-info" target="_blank"><i class="fa fa-edit"></i> Edit </a>
                <a href="' . route('image', [$image->id, $image->slug]) . '" class="btn btn-success" target="_blank"><i class="fa fa-search"></i> View</a>';
            });
        }

        return $datatables->addColumn('thumbnail', function ($image) {
            return '<img src="' . Resize::image($image, 'gallery') . '" style="width:80px"/>';
        })
            ->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('featured_at', function ($image) {
                if ($image->featured_at === null) {
                    return 'Not Featured';
                }

                return $image->featured_at->diffForHumans();
            })
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->editColumn('title', '{!! str_limit($title, 60) !!}')
            ->make(true);
    }
}
