<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Http\Requests\Image;

use App\Artvenue\Repository\ImageRepositoryInterface;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class EditRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(ImageRepositoryInterface $image)
    {
        if ( ! auth()->check()) {
            return false;
        }

        $image = $image->getById($this->route('id'));

        if ( ! $image || auth()->user()->id !== $image->user_id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET': {
                return [];
            }
            case 'POST': {
                return [
                    'title'    => ['required', 'max:200'],
                    'category' => ['required', 'exists:categories,id']
                ];
            }
        }
    }

    public function forbiddenResponse()
    {
        return redirect()->route('gallery')->with('flashError', t('You are not allowed'));
    }
}
