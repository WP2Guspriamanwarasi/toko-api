<?php 
 
namespace App\Http\Controllers;

use App\Models\login;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller

{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $member =Member::query()->firstWhere(['email' => $email]);
        if($member==null) {
            return $this->responseHasil(400,false, 'Email tidak ditemukan');

        }
        if(!Hash::chech($password, $member->password)) {
            return $this->responseHasil(400, false, 'Password tidak Valid');

        }
        $login = Login::Create([

            'member_id' => $member->id,
            'auth_key' => $this->RandomString(),

        ]);
        if(!$login) {
            return $this->responseHasil(401, false, 'Unauthorized');
        }
        $data =[
            'token' => $login->auth_key,
            'user' =>[
                'id' => $member->email,
                'email' => $member->email,

            ]
        ];
        return $this->responseHasil(200, true,$data);
    }

    private function RandomString($length  = 100)
    {
        $karakkter=
'';
        $panjang_karakter = strlen($karakkter);
        $str= '';
        for($i =0; $i<$length;$i++){
            $str = $karakkter[rand(0,$panjang_karakter-1)];
        }
        return $str;

    }
    


}



?>