<?php

namespace CVS\Http\Requests;

use Auth;
use CVS\Http\Requests\Request;

class MessagingSendEmailRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->organizer;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();

        return [
            'sendingType' => 'required',
            'message.object' => 'required',
            'message.content' => 'required'
        ];
    }

    public function sanitize()
    {
        $inputs = $this->all();

        if (isset($inputs['sendingType']) && !empty($inputs['sendingType'])) {
            if ($inputs['sendingType'] == 'toGroups' && isset($inputs['selection']['groups']) && is_array($inputs['selection']['groups'])) {
                $groupsGrouped = [];
                foreach($inputs['selection']['groups'] as $group)
                    $groupsGrouped = array_merge($groupsGrouped, $group);

                $inputs['recipients'] = array_keys(array_flip($groupsGrouped));

            } else if ($inputs['sendingType'] == 'toUsers') {
                $inputs['recipients'] = $inputs['selection']['users'];
            }

            unset($inputs['selection']);
            unset($inputs['sendingType']);
        }

        $this->replace($inputs);
    }
}
