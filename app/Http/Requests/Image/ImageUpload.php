<?php

namespace App\Http\Requests\Image;

use App\Http\Requests\Request;
use Carbon\Carbon;

class ImageUpload extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $numberOfUploadByUser = auth()->user()->images()->where('created_at', '>=', Carbon::now()->subDays(1)->toDateTimeString())->count();
        if ((int)$numberOfUploadByUser >= (int)limitPerDay()) {
            return false;
        }

        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'files'          => ['required', 'image', 'mimes:jpeg,jpg,bmp,png,gif', 'max:' . (int)siteSettings('maxImageSize') * 1000],
            'photo.title'    => ['required', 'max:200'],
            'photo.category' => ['required', 'exists:categories,id'],
            'photo.adult'    => ['required', 'boolean']
        ];
    }
}
