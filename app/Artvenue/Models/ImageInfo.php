<?php
namespace App\Artvenue\Models;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageInfo extends Model
{

    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'image_info';
    /**
     * @var array
     */
    protected $fillable = ['camera', 'lens', 'focal_length', 'shutter_speed', 'aperture', 'is_adult', 'iso', 'taken_at', 'latitude', 'longitude'];

    /**
     * @return array
     */
    public function getDates()
    {
        return ['created_at', 'updated_at', 'deleted_at', 'taken_at'];
    }

    /**
     * @return mixed
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}