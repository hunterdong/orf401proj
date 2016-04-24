<?php
namespace App\Artvenue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class Favorite extends Model
{

    protected $table = 'favorites';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}