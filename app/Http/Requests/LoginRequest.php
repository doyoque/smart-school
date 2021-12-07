<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class LoginRequest extends FormRequest
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
            'username' => ['required'],
            'password' => ['required', 'min:8'],
            'school_id' => ['required', 'numeric'],
        ];
    }
    /**
     * Handling login with username & email
     *
     * Get the needed authorization credentials form request.
     *
     * @return array
     * @throws Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCredentials()
    {
        $username = $this->get('username');

        if ($this->isEmail($username)) {
            return [
                'email' => $username,
                'password' => $this->get('password'),
            ];
        }

        return $this->only('username', 'password');
    }

    /**
     * Validate if provided parameter is valid email.
     *
     * @param string $param
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function isEmail($param)
    {
        $factory = $this->container->make(ValidationFactory::class);

        return !$factory->make(
            ['username' => $param],
            ['username' => 'email'],
        )->fails();
    }
}
