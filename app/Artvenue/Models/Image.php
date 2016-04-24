<?php
namespace App\Artvenue\Models;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'images';
    /**
     * @var bool
     */
    protected $softDelete = true;

    /**
     * @var array
     */
    protected $dates = ['deleted_at', 'featured_at'];

    /**
     * @return mixed
     */
    public static function scopeApproved()
    {
        return static::whereNotNull('approved_at');
    }

    /**
     * @param $value
     * @return string
     */
    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'image_id');
    }

    /**
     * @return mixed
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'image_id');
    }

    /**
     * @return mixed
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return mixed
     */
    public function info()
    {
        return $this->hasOne(ImageInfo::class, 'image_id');
    }
}
