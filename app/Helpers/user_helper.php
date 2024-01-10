<?php

if (!function_exists('isGuest')) {
    function isGuest()
    {
        $ses = session();
        if (!$ses->has('id')) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('AuthUser')) {
    function AuthUser()
    {
        if (!isGuest()) {
            $ses = session();
            $obj = new stdClass();
            $obj->id = $ses->id;
            $obj->nama = $ses->nama;
            $obj->level = $ses->level;
            $obj->level_nama = $ses->level_nama;
            $obj->status = $ses->user_status == 1 ? 'Aktif' : 'Tidak Aktif';
            $obj->login_at = $ses->login_at;
            return $obj;
        }
        return null;
    }
}
