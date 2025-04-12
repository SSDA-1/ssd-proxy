<?php

namespace Ssda1\proxies\Service;

use Ssda1\proxies\Models\Modem;
use Ssda1\proxies\Models\User;
use Ssda1\proxies\Models\Proxy;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class ExportPortsService
{

    public function exportPorts($apiKey,$ip,$serverId)
    {
        $proxyPortsList = \curl_init($ip . '/api/devices/modem/list');
        \curl_setopt($proxyPortsList, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json', 'Authorization: Token ' . $apiKey));
        $filter = [
            'filter' => []
        ];
        \curl_setopt($proxyPortsList, CURLOPT_POST, 1);
        \curl_setopt($proxyPortsList, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($proxyPortsList, CURLOPT_POSTFIELDS, json_encode($filter, JSON_UNESCAPED_UNICODE));
        // \curl_setopt($proxyPortsList, CURLOPT_RETURNTRANSFER, true);

        $proxyPortsListPreRes = \curl_exec($proxyPortsList);
        \curl_close($proxyPortsList);
        $proxyPortsListRes = json_decode($proxyPortsListPreRes, JSON_UNESCAPED_UNICODE);
        // $FingerListOption = [];
        $testLine = '';
        foreach ($proxyPortsListRes as $key => $port) {
            // $FingerListOption[$val['id']] = $val['name'];
            if (Modem::where('server_id', '=', $serverId)->where('id_kraken', '=', $port['id'])->exists()) {
                // если есть в БД
            }else{
                $testLine = '';//$port['name'].'--'.$port['ifname'].'--'.$port['type'].'--'.$port['active'].'--'.$port['net_mode'].'--'.$port['is_osfp'].'--'.$port['osfp'].'--'.$port['reconnect_type'].'--'.$port['reconnect_interval'].'--'.$port['reconnect_min'].'--'.$port['id'];
                $newModem = new Modem;
                $newModem->name = $port['name'];
                $newModem->ifname = $port['ifname'];
                $newModem->type = $port['type'];
                $newModem->active = $port['active'];
                $newModem->net_mode = $port['net_mode'];
                $newModem->is_osfp = $port['is_osfp'];
                if ($port['is_osfp'] != null) {
                    $newModem->osfp = $port['osfp']['id'];
                }
                $newModem->reconnect_type = $port['reconnect_type'];
                $newModem->reconnect_interval = $port['reconnect_interval'];
                $newModem->reconnect_min = $port['reconnect_min'];
                $newModem->server_id = $serverId;
                // $newModem->users = [];
                $newModem->type_pay = 'general';
                $newModem->max_users = '1';
                $newModem->id_kraken = $port['id'];
                $newModem->save();
            }
        }

        $statusExport = $proxyPortsListRes;

        return $testLine;
    }

    public function exportProxy($apiKey,$ip)
    {
        $proxyList = \curl_init($ip . '/api/proxy/list');
        \curl_setopt($proxyList, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json', 'Authorization: Token ' . $apiKey));
        $filter = [
            'filter' => []
        ];
        \curl_setopt($proxyList, CURLOPT_POST, 1);
        \curl_setopt($proxyList, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($proxyList, CURLOPT_POSTFIELDS, json_encode($filter, JSON_UNESCAPED_UNICODE));
        // \curl_setopt($proxyList, CURLOPT_RETURNTRANSFER, true);

        $proxyListPreRes = \curl_exec($proxyList);
        \curl_close($proxyList);
        $proxyListRes = json_decode($proxyListPreRes, JSON_UNESCAPED_UNICODE);
        // $FingerListOption = [];
        $testLine = '';
        foreach ($proxyListRes['data'] as $key => $proxy) {
            // $FingerListOption[$val['id']] = $val['name'];
            // if ($key == 'data') {
                // $testLine = $proxy;
                if (Modem::where('id_kraken', '=', $proxy['modem']['id'])->exists()) {
                        // если есть в БД
                    // }else{
                    if (!Proxy::where('id_kraken', '=', $proxy['id'])->exists()) {
                        // $testLine = $proxy['active'].'--'.$proxy['type'].'--'.$proxy['auth'].'--'.$proxy['port'].'--'.$proxy['auth_params']['login'].'--'.$proxy['modem']['id'].'--'.$proxy['id']; //.'--'.$proxy['reconnect_type'].'--'.$proxy['reconnect_interval'].'--'.$proxy['reconnect_min'].'--'.$proxy['id'];
                        $testLine = Modem::where('id_kraken', '=', $proxy['modem']['id'])->first()->id;
                        $modemID = Modem::where('id_kraken', '=', $proxy['modem']['id'])->first()->id;

                        $newProxy = new Proxy;
                        $newProxy->active = $proxy['active'];
                        $newProxy->type = $proxy['type'];
                        $newProxy->auth = $proxy['auth'];
                        $newProxy->number_proxy = $proxy['port'];
                        // $newProxy->ifname = null;

                        if (User::where('kraken_username', '=', $proxy['auth_params']['login'])->exists()) {   //kraken_username
                            $newProxy->user_id = User::where('kraken_username', '=', $proxy['auth_params']['login'])->first()->id;
                        }else{
                            $password = Hash::make($proxy['auth_params']['password']);
                            $userNew = new User;
                            $userNew->name = $proxy['auth_params']['login'];
                            $userNew->email = $proxy['auth_params']['login'];
                            $userNew->password = $password;
                            $userNew->kraken_username = $proxy['auth_params']['login'];
                            $userNew->kraken_password = $proxy['auth_params']['password'];
                            $userNew->id_kraken = $proxy['auth_params']['id'];
                            $userNew->save();
                            $userID = $userNew->id;
                            $newProxy->user_id = $userID;
                            $userPass = $proxy['auth_params']['password'];
                            $userEmail = $proxy['auth_params']['login'].'@mail.ru';
                            $userName = $proxy['auth_params']['login'];
                            getUserAddApi($userID, $ip, $apiKey, $userName, $userPass, $userEmail);
                        }
                        // $newProxy->user_id = [];
                        $newProxy->modem_id = $modemID; //тут нужен айди в БД у нас который а не Кракеновский
                        // $newProxy->date_end = $current;
                        $newProxy->id_kraken = $proxy['id'];
                        $newProxy->save();
                    }
                }
        }

        $statusExport = $testLine;

        return $statusExport;
    }

    public function exportUsers($apiKey,$ip)
    {
        $proxyUsersList = \curl_init($ip . '/api/users/list');
        \curl_setopt($proxyUsersList, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json', 'Authorization: Token ' . $apiKey));
        $filter = [
            'pagination' => []
        ];
        \curl_setopt($proxyUsersList, CURLOPT_POST, 1);
        \curl_setopt($proxyUsersList, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($proxyUsersList, CURLOPT_POSTFIELDS, json_encode($filter, JSON_UNESCAPED_UNICODE));
        // \curl_setopt($proxyList, CURLOPT_RETURNTRANSFER, true);

        $proxyUsersListPreRes = \curl_exec($proxyUsersList);
        \curl_close($proxyUsersList);
        $proxyListRes = json_decode($proxyUsersListPreRes, JSON_UNESCAPED_UNICODE);
        // $FingerListOption = [];
        $testLine = '';
        foreach ($proxyUsersListRes as $key => $proxy) {
            // $FingerListOption[$val['id']] = $val['name'];
            if (User::where('id_kraken', '=', $proxy['id'])->exists()) {
                // если есть в БД
            }else{
                // $testLine = $proxy['name'].'--'.$proxy['ifname'].'--'.$proxy['type'].'--'.$proxy['active'].'--'.$proxy['net_mode'].'--'.$proxy['is_osfp'].'--'.$proxy['osfp'].'--'.$proxy['reconnect_type'].'--'.$proxy['reconnect_interval'].'--'.$proxy['reconnect_min'].'--'.$proxy['id'];
                // $newModem = new Modem;
                // $newModem->name = $proxy['name'];
                // $newModem->ifname = $proxy['ifname'];
                // $newModem->type = $proxy['type'];
                // $newModem->active = $proxy['active'];
                // $newModem->net_mode = $proxy['net_mode'];
                // $newModem->is_osfp = $proxy['is_osfp'];
                // if ($proxy['is_osfp'] != null) {
                //     $newModem->osfp = $proxy['osfp']['id'];
                // }
                // $newModem->reconnect_type = $proxy['reconnect_type'];
                // $newModem->reconnect_interval = $proxy['reconnect_interval'];
                // $newModem->reconnect_min = $proxy['reconnect_min'];
                // // $newModem->users = [];
                // $newModem->type_pay = 'private';
                // $newModem->max_users = '1';
                // $newModem->id_kraken = $proxy['id'];
                // $newModem->save();
            }
        }

        $statusExport = $proxyUsersListRes;

        return $testLine;
    }
}
