<?php
namespace App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Contracts\Encryption\DecryptException;

class A0_Initial extends Controller{
    public function masterParam(){
        $optOnload['status']    =   false;
        $optOnload['data']      =   null;
        $opt_extra_content['token'] =   null;
        $opt_extra_content['onOpen'] =   true;
        $opt_extra_content['onLoad'] =  (object) $optOnload;
        $prm['token']          =   null;
        $prm['callback']       =   null;
        $prm['content']        =   null;
        $prm['time']           =   Carbon::now();
        $prm['extra_content']  =   (object) $opt_extra_content;
        $params                =   (object) $prm;
        return $params;
    }
    public function processorRequest($request){
        $initParm = $this->masterParam();
        $initParm->extra_content->onOpen= false;
        $params = $initParm;
        try {
            if (gettype($request->message) == "string") {
                $message    =   json_decode($request->message);
                if (gettype($message->extra_content) == 'object') {
                // ============================== Begin ================================
                    if (isset($request->message->extra_content->token)){
                        $params->extra_content->token = $request->message->extra_content->token;
                    }
                // ============================== end ==================================
                }
            }
            return $params;
        } 
        catch (\Throwable $th) {
            return json_encode('error'.$th->getMessage());
        }
    }
    function identificationUser($request){
        $User                       = app("App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage\F0_User")->Index($request);
        $response = $User;
        return $response;
    }
    function execTrxMessageDetails($data,$request=null){
            $masterParam = $this->masterParam();
            $masterParam->extra_content->onOpen= false;
            $masterParam->extra_content->token=Crypt::encrypt($data->users->username);;
            $file                           = $data->code_trx_message;
            $stage = app("App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage\\".$file)->Index($data,$request);
            $masterParam->messageID                = $data->trx_message->id;
            $masterParam->content                   = $stage->content;
        return $masterParam;
    }
    function Sending($data){
        $data->messageID = null;
        return $data;
    }
    function getMessage($request){
        $data = json_decode($request->message);
        $token = $data->extra_content->token;
        $decrypt    = Crypt::decrypt($token);
        $dataUser   = DB::table('users as u')->where('u.username',$decrypt)->first();
        $msgDetails = DB::table('trx_message_details as tmd')->where('tmd.user_id',$dataUser->id)->where('tmd.id',$dataUser->trx_msg_details_id)->first();
        $trxMsg     = DB::table('trx_message as tm')->where('tm.id',$msgDetails->trx_msg_id)->first();
        $msg        = DB::table('message as m')->where('m.msg_id',$trxMsg->id)->get();
        $response = $msg;
        return $response;
    }
    public function initUser(){
        $token      = Crypt::decrypt($data->extra_content->token);
        $dataUser   = DB::table('users as u')->where('u.username',$token)->first();
        $msgDetails = DB::table('trx_message_details as dtl')
                                    ->where('dtl.user_id',$dataUser->id)
                                    ->where('status',1)
                                    ->orderBy('index','asc')
                                    ->first();
        $CodeMessage =   DB::table('code_trx_message')->where('id',$execTrxMesgDtl_first->code_trx_msg_id)->first();
        $usr['users'] = $dataUser;
        $usr['msgDetails'] = $msgDetails;
        $usr['CodeMessage'] = $CodeMessage;
    }
}