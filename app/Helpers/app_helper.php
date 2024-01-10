<?php
if(!function_exists('ds')) {
    function ds($data,$exit=true)
    {
        echo "<pre>"; 
        print_r($data); 
        echo "</pre>"; 
        if($exit){
            exit;
        }
    }
}
if(!function_exists('jsonFormat')) {
    function jsonFormat($status,$msg=NULL,$data=null,$code=NULL){
        $return=[
            'status'=>$status,
            'msg'=>$msg
        ];
        if($data!=NULL){
            $return['data']=$data;
        }
        if($code!=NULL){
            $return['code']=$code;
        }
        return $return;
    }
}   