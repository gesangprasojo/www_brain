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

class F0_User extends Controller
{
    public function Index($data){
        if ($data->extra_content->token == null) {
            return $this->newUser();
        }
    }
    public function newUser(){
        $users                      = $this->create_user();
        $trx_message                = $this->create_trx_message();
        $dataReturn                 =   array(
            "user"=>$users,
            "trx_message"=>$trx_message
        );
        $trx_message_details    = $this->trx_message_details((object) $dataReturn);
        $usr['users']               = $users;
        $usr['trx_message']         = $trx_message;
        $usr['code_trx_message']    = (DB::table('code_trx_message')->where('id',$trx_message_details->code_trx_msg_id)->first())->code_stage;
        $response = (object) $usr;
        return $response;
    }
    public function generateId(){
        $objDateTime = Carbon::now();
        $id_user = $objDateTime; // getting Timestamp
        return $id_user;
    }
    public function create_user(){
            $dataAfterInsert = DB::table('users')->insertGetId(
                array(
                    'email'        =>  null,
                    'username'     =>  null,
                    'password'     =>  null,
                    'token'        =>  null,
                    'status'       =>  1
                )
            );
            $username = "usr_".$dataAfterInsert."_".$this->generateId();
            $dataAfterUpdate = DB::table('users')
                ->where('users.id',$dataAfterInsert)
                ->update(
                    array(
                        "username"=>$username,
                        "status"=>1
                    )
                );
            $dataUser = DB::table('users')->where('users.id',$dataAfterInsert)->first();
            return $dataUser;
    }
    public function create_trx_message(){
        $dataAfterInsert = DB::table('trx_message')->insertGetId(
            array(
                "trx_code"=>null,
                "status"=>0
            )
        );
        $trx_code ="msg_".$dataAfterInsert."_".$this->generateId();
        $dataAfterUpdate = DB::table('trx_message')
        ->where('trx_message.id',$dataAfterInsert)
        ->update(
            array(
                "trx_code"=>$trx_code,
                "status"=>1
            )
        );
        $data_trx_message = DB::table('trx_message')->where('trx_message.id',$dataAfterInsert)->first();
        return $data_trx_message;
    }
     public function trx_message_details($data){
        $trx_message_details = null;
        $trxMessageId  = $data->trx_message->id;
        $userId        = $data->user->id;
        $listRouteMessage  = DB::table('code_trx_message as ctm')->orderBy('ctm.index','asc')->get();
        $i=0;
        foreach ($listRouteMessage as $r){
            $i++;
            DB::table('trx_message_details')->insertGetId(
                array(
                    'code_trx_msg_id' =>  $r->id,
                    'index' =>  $i,
                    'trx_msg_id'      =>  $trxMessageId,
                    'user_id'         =>  $userId,
                    'status'          =>  1
                )
            );
        }
        $trx_message_details    = DB::table('trx_message_details as trx_details')
                                ->where('trx_details.trx_msg_id',$trxMessageId)
                                ->where('trx_details.user_id',$userId)
                                ->orderBy('trx_details.index','asc')
                                ->first();
        DB::table('users')->where('id',$userId)->update(["trx_msg_details_id"=>$trx_message_details->id]);
        return $trx_message_details;
     }
     public function message($trxmessageId,$content){
        $messageId      =   $trxmessageId;
        $user           =   1;
        $insertGetId    = DB::table('message')->insertGetId(
            array(
                "msg_id"            => $messageId,
                "content"           => $content,
                "status"            => 1,
                "created_by"        => $user
            )
        );
        $message = DB::table('message')->where('message.id',$insertGetId)->first();
       return  $message;
     }
    }