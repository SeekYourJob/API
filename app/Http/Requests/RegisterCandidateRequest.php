<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 1/24/2016
 * Time: 7:26 PM
 */
namespace CVS\Http\Requests;

use CVS\Http\Requests\Request;

class RegisterCandidateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'required',
            'user.email' => 'required',
            'user.password' => 'required',
            'user.firstname' => 'required',
            'user.lastname' => 'required',
            'candidate' => 'required',
            'candidate.grade' => 'required'
        ];
    }
}
