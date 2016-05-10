<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\StudentsModel;
use App\TeachersModel;
use App\PersonsModel;
use Illuminate\Http\Request;
use App\TokenModel;
use DateTime;

class TokenController extends Controller {

    public function token(Request $request) {

        $username = $request->input('username');
        $type = $this->typeOfUser($username);
        $datenow = (new \DateTime())->format('Y-m-d H:i:s');
        $token = TokenModel::where("USERNAME", "=", $username)->where("expiration", ">", $datenow)->first();
        if ($token == null) {
            $tokenNew = $this->createToken(64, 5, true, true, [$person->EMAIL, $person->ID, $person->NOMBRES]);
            $token = new TokenModel;
            $token->USERNAME = $username;
            $token->TOKEN = $tokenNew;
            $token->expiration = (new DateTime())->modify('+48 hours')->format('Y-m-d H:i:s');
            $token->save();
            $object = array(
                "type" => $type,
                "token" => $tokenNew
            );
        } else {
            $object = array(
                "type" => $type,
                "token" => $token->TOKEN
            );
        }
        return $object;
    }

    public function createToken($len = 64, $output = 5, $standardChars = true, $specialChars = true, $chars = array()) {
        $out = '';
        $len = intval($len);
        $outputMap = array(1 => 2, 2 => 8, 3 => 10, 4 => 16, 5 => 10);
        if (!is_array($chars)) {
            $chars = array_unique(str_split($chars));
        }
        if ($standardChars) {
            $chars = array_merge($chars, range(48, 57), range(65, 90), range(97, 122));
        }
        if ($specialChars) {
            $chars = array_merge($chars, range(33, 47), range(58, 64), range(91, 96), range(123, 126));
        }
        array_walk($chars, function(&$val) {
            if (!is_int($val)) {
                $val = ord($val);
            }
        });
        if (is_int($len)) {
            while ($len) {
                $tmp = ord(openssl_random_pseudo_bytes(1));
                if (in_array($tmp, $chars)) {
                    if (!$output || !in_array($output, range(1, 5)) || $output == 3 || $output == 5) {
                        $out .= ($output == 3) ? $tmp : chr($tmp);
                    } else {
                        $based = base_convert($tmp, 10, $outputMap[$output]);
                        $out .= ((($output == 1) ? '00' : (($output == 4) ? '0x' : '')) . (($output == 2) ? sprintf('%03d', $based) : $based));
                    }
                    $len--;
                }
            }
        }
        return (empty($out)) ? false : $out;
    }

    public function typeOfUser($username) {
        $person = StudentsModel::where("ID", "=", $username)->first();
        if ($person) {
            return "student";
        }
        $person = TeachersModel::where("ID", "=", $username)->first();
        if ($person) {
            return "teacher";
        }
        $person = PersonsModel::where("ID", "=", $username)->first();
        if ($person) {
            return "Other";
        }
        return "Undefined";
    }

}
