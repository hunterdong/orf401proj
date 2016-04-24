<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Models;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{

    protected $table = 'categories';

    public function images()
    {
        return $this->hasMany(Image::class, 'category_id');
    }
}