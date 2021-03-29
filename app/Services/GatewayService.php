<?php namespace App\Services;

use GatewayClient\Gateway;

class  GatewayService
{

    public function __construct()
    {
        Gateway::$registerAddress = '172.17.0.1:1240';
    }

    
    public function bindUid($clientId,$userId)
    {
        //client_id/user_id
        Gateway::bindUid($clientId, $userId);

    }

    public function joinGroup($clientId,$groupId)
    {

        //client_id/group_id
        Gateway::joinGroup($clientId, $groupId);

    }

    public function sendToUid($userId,$message)
    {

        //user_id/[]
        Gateway::sendToUid($userId, json_encode($message));

    }

    public function sendToGroup($groupId,$message,$clientId = null)
    {
        
        \Log::info('groupId:'.$groupId, $message);
        //group_id/[]/user_id(当前用户不推送)
        Gateway::sendToGroup($groupId, json_encode($message),$clientId);
    }

    public function getClientIdByUid($userId)
    {
        return Gateway::getClientIdByUid($userId);
    }

    public function isUidOnline($userId)
    {
        //user_id
        return Gateway::isUidOnline($userId);
    }

}