<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class TokenModel extends Model {

    protected $table = 'apptoken';

    public function createToken($username, $generatedToken) {
        $token = new TokenModel;
        $token->USERNAME = $username;
        $token->TOKEN = $generatedToken;
        $token->expiration = (new DateTime())->modify('+48 hours')->format('Y-m-d H:i:s');
        $token->save();

        return $token;
    }

}
