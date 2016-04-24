<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers\Admin;

use App\Artvenue\Models\Image;
use App\Artvenue\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function getIndex()
    {
        $startDate = Carbon::now()->subDays(30);
        $signup = User::where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->get([
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as value')
            ])
            ->toJSON();

        $images = Image::where('approved_at', '>=', $startDate)
            ->groupBy('date')
            ->get([
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as value')
            ])->toJson();

        $title = 'Dashboard';

        return view('admin/sitedetails/index', compact('signup', 'images', 'title'));
    }
}
