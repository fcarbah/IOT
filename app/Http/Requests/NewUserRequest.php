<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

//use Illuminate\Validation\Rule;

class NewUserRequest extends Request
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
            'username'=>['required','unique:users,username,'.$this->get('id'),/*Rule::unique('users')->ignore($this->get('id')),*/'regex:/^\w+$/'],
            'role_id'=>'required',
            'password'=>'required_without:id',
            'confirmPassword'=>'required_without:id|same:password'
        ];
    }
    public function messages(){
        return[
            'username.required'=>'Userame is required',
            'role_id.required'=>'User Role is required',
            'password.required_without'=>'Password is required',
            'confirmPassword.required_without'=>'Confirm Password is required',
            'confirmPassword.same'=>'Passwords don\'t match'
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
