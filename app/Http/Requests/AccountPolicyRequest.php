<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AccountPolicyRequest extends Request
{
    use \App\Traits\ResponseTrait;
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
            'name'=>'required',
            'role_id'=>'required',
            'failedLoginAttempts'=>'required',
            'lockoutDuration'=>'required',
            'threshold'=>'required',
            'reset'=>'required'
        ];
    }
    public function messages(){
        return[
            'name.required'=>'Name is required',
            'role_id.required'=>'User Group is required',
            'failedLoginAttempts.required'=>'Failed Login Attempts is required',
            'lockoutDuration'=>'Lokout Duration is required',
            'threshold.required'=>'Threshold is Required',
            'reset.required'=>'Reset Duration is Required'
        ];
    }
    
    public function response(array $errors)
    {
        if ($this->ajax() || $this->wantsJson()) {
            
            $response = $this->buildResponse();
            
            $messages = collect($errors)->flatten()->all();

            if(count($messages)> 0){
                $response->error = true;
                $response->messages = $messages;  
                $response->type='danger';
                $response->title='<h3>Validation Error</h3>';
            }
            
            return response()->json($response,200);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
