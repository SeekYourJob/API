<?php

namespace CVS\Http\Requests;

use CVS\Http\Requests\Request;

class RegisterRecruiterRequest extends Request
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
            'recruiter' => 'required',
            'recruiter.company' => 'required',
            'recruiter.availability' => 'required'
        ];
    }
}
