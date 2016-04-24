<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Http\Requests\Comment;

use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Http\Requests\Request;

class CreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param ImageRepositoryInterface $image
     * @return bool
     */
    public function authorize(ImageRepositoryInterface $image)
    {
        $image = $image->getById($this->route('id'))->first();
        if (!$image) {
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
            'comment' => ['required', 'min:2']
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forbiddenResponse()
    {
        return redirect()->route('login')->with('flashError', t('You need to Login first'));
    }
}
