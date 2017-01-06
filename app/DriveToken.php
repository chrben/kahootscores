<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriveToken extends Model
{
    protected $fillable = [
        "access_token",
        "token_type",
        "expires_in",
        "created",
    ];
    public function getJson()
    {
        return json_encode([
            "access_token" => $this->access_token,
            "token_type" => $this->token_type,
            "expires_in" => $this->expires_in,
            "created" => $this->created,
        ]);
    }
}
