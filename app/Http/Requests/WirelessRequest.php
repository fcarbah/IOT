<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class WirelessRequest extends Request
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
            'ssid'=>'required',
            'password'=>'required_unless:authType,'.\WirelessAuthenticationTypes::Open.','.\WirelessAuthenticationTypes::MAC,
            'authType'=>'required',
        ];
    }
    public function messages(){
        return[
            'ssid.required'=>'SSID is required',
            'password.required_unless'=>'Password is required',
            'authType.required'=>'Encryption Type is required'
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
