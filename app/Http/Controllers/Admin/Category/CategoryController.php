<?php

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers\Admin\Category;

use App\Artvenue\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CategoryController extends Controller
{
    public function index()
    {
        $title = 'Categories';

        return view('admin.settings.category', compact('title'));
    }

    /**
     * @return mixed
     */
    public function createCategory(Request $request)
    {
        $this->validate($request, [
            'addnew' => 'required',
        ]);
        $category = new Category();
        $category->name = ucfirst($request->get('addnew'));
        $slug = str_slug($request->get('addnew'));
        if (!$slug) {
            $slug = str_random(9);
        }
        $category->slug = $slug;
        $category->save();
        Artisan::call('cache:clear');
        return redirect()->back()->with('flashSuccess', 'New Category Is Added');
    }

    /**
     * @param Request $request
     */
    public function reorderCategory(Request $request)
    {
        $tree = $request->get('tree');
        foreach ($tree as $k => $v) {
            if ($v['depth'] == -1) {
                continue;
            }
            if ($v['parent_id'] == 'root') {
                $v['parent_id'] = 0;
            }

            $category = Category::whereId($v['item_id'])->first();
            $category->parent_id = $v['parent_id'];
            $category->depth = $v['depth'];
            $category->lft = $v['left'] - 1;
            $category->rgt = $v['right'] - 1;
            $category->save();
        }
        Artisan::call('cache:clear');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCategory(Request $request)
    {
        $this->validate($request, [
            'id'   => ['required'],
            'slug' => ['required', 'alpha_dash'],
            'name' => ['required']
        ]);
        $id = $request->get('id');
        $category = Category::where('id', '=', $id)->with('images')->first();
        $delete = $request->get('delete');
        if ($delete) {
            if ($request->get('shiftCategory')) {
                foreach ($category->images as $image) {
                    $image->category_id = $request->get('shiftCategory');
                    $image->save();
                }
            }
            $category->delete();

            return redirect()->back()->with('flashSuccess', 'Category is now deleted');
        }

        $category->slug = $request->get('slug');
        $category->name = $request->get('name');
        $category->save();
        Artisan::call('cache:clear');
        return redirect()->back()->with('flashSuccess', 'Category is now updated');
    }
}
