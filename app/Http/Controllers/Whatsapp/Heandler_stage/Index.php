<?php
namespace App\Http\Controllers\Whatsapp\Heandler_stage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;

class Index extends Controller
{
    public function index(Request $request){
        $onLoad = $this->_checkOnloadRequest($request);
        if ($onLoad ==  true) {
            return $this->_onLoad($request);
        }
        else{
            return $this->_route_01($request);
        }
    }
    public function _onLoad($request){
        $A0_Initial             = app("App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage\A0_Initial");
        $dataRequest            = $A0_Initial->masterParam();
        $getMessage             = $A0_Initial->getMessage($request);
        $dataRequest->extra_content->onOpen= false;
        $dataRequest->extra_content->onLoad->status = false;
        $test[] = $getMessage;
        // $dataRequest->extra_content->onLoad->data=$getMessage;
        $dataRequest->extra_content->onLoad->data=$test;
        return json_encode($dataRequest);

    }
    public function _route_01($request){
        $A0_Initial             = app("App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage\A0_Initial");
        $dataRequest            = $A0_Initial->processorRequest($request);
        $identificationUser     = $A0_Initial->identificationUser($dataRequest);
        $execTrxMessageDetails  = $A0_Initial->execTrxMessageDetails($identificationUser,$request);
        $sending                = $A0_Initial->Sending($execTrxMessageDetails);
        return json_encode($sending);
    }
    public function onOpen(Request $request){
        $A0_Initial             = app("App\Http\Controllers\Whatsapp\Heandler_stage\Collection_stage\A0_Initial");
        $dataRequest            = $A0_Initial->masterParam();
        return json_encode($dataRequest);
    }
    public function _checkOnloadRequest($request){
        $result = false;
        if (gettype($request->message) == "string") {
            $message    =   json_decode($request->message);
            if (gettype($message->extra_content) == 'object') {
                if ($message->extra_content->onLoad->status == true ){
                    $result = true;
                }
            }
        }
        return $result;
    }
}
