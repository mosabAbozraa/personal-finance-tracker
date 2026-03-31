<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\CssSelector\Parser\Token;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'First name'    => $this->first_name,
            'Last name'     => $this->last_name,
            'Email address' => $this->email,
            'Avatar'        => $this->avatar,
            'Token'         => $this->additional['token']
        ];
    }
}
