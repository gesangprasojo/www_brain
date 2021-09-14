<?php
namespace App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
// $encrypted = Crypt::encrypt($username);
// $decrypted = Crypt::decrypt($encrypted);
use Illuminate\Contracts\Encryption\DecryptException;

class Fa0_welcome extends Controller{
    public function Index($data){
        DB::table('trx_message_details as dtl')
        ->where('dtl.user_id',$data->users->id)
        ->where('status',1)
        ->orderBy('index','asc')
        ->limit(1)
        ->update(["status"=>2]);

        DB::table('message')->insert(
            array(
                'msg_id'        => $data->trx_message->id,
                'content'       => 'test',
                'created_by'    => 1,
                'status'        => 1
            )
        );
        $dataset['content'] = json_encode($data);
        $dataset = (object) $dataset;
        return $dataset;
    }
}