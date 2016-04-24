<?php
namespace App\Artvenue\Models;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

    protected $table = 'blogs';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}