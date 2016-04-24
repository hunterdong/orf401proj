<?php
namespace App\Artvenue\Helpers;

use App\Artvenue\Models\User;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class Resize
{
    protected static $sizes = [
        'gallery'       => [
            'dir'       => 'uploads/images',
            'width'     => 280,
            'height'    => 280,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'mainImage'     => [
            'dir'       => 'uploads/images',
            'width'     => 1140,
            'height'    => 1140,
            'method'    => 'resize',
            'watermark' => true,
        ],
        'featuredImage' => [
            'dir'       => 'uploads/images',
            'width'     => 280,
            'height'    => 280,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'sidebarImage'  => [
            'dir'       => 'uploads/images',
            'width'     => 80,
            'height'    => 80,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'coverImage'    => [
            'dir'       => 'uploads/images',
            'width'     => 1920,
            'height'    => '1080',
            'method'    => 'fit',
            'watermark' => false,
        ],
        'listingImage'  => [
            'dir'       => 'uploads/images',
            'width'     => 117,
            'height'    => 117,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'avatar'        => [
            'dir'       => 'uploads/avatars',
            'width'     => 80,
            'height'    => 80,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'mainAvatar'    => [
            'dir'       => 'uploads/avatars',
            'width'     => 263,
            'height'    => 263,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'listingAvatar' => [
            'dir'       => 'uploads/avatars',
            'width'     => 117,
            'height'    => 117,
            'method'    => 'fit',
            'watermark' => false,
        ],
    ];


    /**
     * @param null $file
     * @param string $recipe
     * @param bool $isAdult
     */
    public function __construct($file = null, $recipe = 'avatar', $isAdult = false)
    {
        $this->_setImage($file, $recipe, $isAdult);
    }

    /**
     * @param $file
     * @param null $recipe
     * @param $isAdult
     */
    protected function _setImage($file, $recipe = null, $isAdult)
    {
        $recipe = self::$sizes[$recipe];
        if (auth()->check() == false && $isAdult == true) {
            $this->file = 'adult.jpeg';
            $this->dir = 'uploads/assets';
        } else {
            $this->file = $file;
            $this->dir = $recipe['dir'];
        }
        $this->width = $recipe['width'];
        $this->height = $recipe['height'];
        $this->method = $recipe['method'];
        $this->watermark = $recipe['watermark'];
    }

    /**
     * @return array
     */
    public static function getSizes()
    {
        return self::$sizes;
    }

    /**
     * @param $user
     * @param null $recipe
     * @return Resize|string
     */
    public static function avatar($user, $recipe = null)
    {
        if ($user->avatar == null || $user->avatar == 'user') {
            $avatar = new Resize($user, $recipe);

            return $avatar->process();
        }
        $image = new Resize($user->avatar, $recipe);

        return $image->process();
    }

    /**
     * @return string
     */
    protected function process()
    {
        if ($this->file instanceof User) {
            if ($this->file->avatar == null || $this->file->avatar == 'user') {
                return get_gravatar($this->file->email, $this->width);
            }
        }
        $resize = new ResizeHelper($this->file, $this->dir, $this->width, $this->height, $this->method, $this->watermark);

        return $resize->resize();
    }

    /**
     * @param $image
     * @param null $recipe
     * @return string
     */
    public static function image($image, $recipe = null)
    {
        $image = new Resize(sprintf('%s.%s', $image->image_name, $image->type), $recipe, $image->is_adult);

        return $image->process();
    }

    /**
     * @param $func
     * @param $args
     * @return $this
     */
    public function __call($func, $args)
    {
        $reflection = new \ReflectionClass(get_class($this));
        $methodName = '_' . $func;

        if ($reflection->hasMethod($methodName)) {
            $method = $reflection->getMethod($methodName);

            if ($method->getNumberOfRequiredParameters() > count($args)) {
                throw new \InvalidArgumentException('Not enough arguments given for ' . $func);
            }
            call_user_func_array([$this, $methodName], $args);

            return $this;
        }

        throw new \BadFunctionCallException('Invalid method: ' . $func);
    }

    /**
     * @param null $width
     * @param null $height
     * @return $this
     */
    protected function _size($width = null, $height = null)
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * @param null $dir
     * @return $this
     */
    protected function _dir($dir = null)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * @return $this
     */
    protected function _watermark()
    {
        $this->watermark = true;

        return $this;
    }
}
