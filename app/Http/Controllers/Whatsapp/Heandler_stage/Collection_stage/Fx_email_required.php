<?php
namespace App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class F1_email_required extends Controller
{
    public function index($request){
        $content['id']              = $this->generateId();
        $content['id_look']         = 'Gesang Prasojo';
        $content['content']         = 'Hallo Sayang siapa di situ ?';
        $content['extra_content']   =  $request;
        $content['time']            = '888888';
        $content['code_message']    = 'new_client_stranger_01_wa';
        $content['connection']      =  arra('latest_connection'=>)
        return $content;
    }
    public function generateId(){
        $date = new DateTime(); // current time object
        $id_user = "stranger_".$date->getTimestamp(); // getting Timestamp
        // $date->format('U = Y-m-d H:i:s');
        return $id_user;
    }
}