<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactRequest extends Request
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
            'type'=>'required',
            'phone'=>['required','regex:/\d{3}|\d{7}|\d{10}/'],
            'alertTypes'=>'required'
        ];
    }
    public function messages(){
        return[
            'name.required'=>'Contact Name is required',
            'type.required'=>'Contact Type is required',
            'phone.required'=>'Phone Number is required',
            'phone.regex'=>'Invalid Phone Number',
            'alertTypes.required'=>'Select one or more Alert Types'
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
