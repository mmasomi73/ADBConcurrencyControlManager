<?php

use Carbon\Carbon;


function randomString($length = 6)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $length; $i++) {
        $randstring .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randstring;
}

function getRowNumber($paginationElem, &$counter)
{
    return (($paginationElem->currentPage() - 1) * $paginationElem->perPage()) + ++$counter;
}

function getProfile($user = null)
{
    $profiles = [
        'cute1.png',
        'cute2.png',
        'cute3.png',
        'cute5.png',
        'cute4.png',
        'cute10.png',
        'cute6.png',
        'cute7.png',
        'cute8.png',
        'cute9.png',
        'cute11.png',
        'cute12.png',
        'cute13.png',
        'cute14.png',
        'cute15.png',
        'cute16.png',
        'cute17.png',
        'cute18.png',
        'cute19.png',
        'cute20.png',
        'cute21.png',
        'cute22.png',
        'cute23.png',
        'cute24.png',
        'cute25.png',
        'cute26.png',
        'cute27.png',
        'cute28.png',
        'cute29.png',
        'cute30.png',
        'cute31.png'];
    if (!empty($user)){
        return 'cms/uploads/avatar/'.$profiles[$user];
    }
    return 'cms/uploads/avatar/'.$profiles[array_rand($profiles)];
}

function exFormatter($execution){
    $execution = str_replace(';', '', $execution);
    $re = '/[c a A]{1}\([1-7]{1}\)|[w r l u]{2}\([ 1-7]{1},[x y z w v]?\)|[w r]{1}\([ 1-7]{1},[x y z w v]?\)|[a]{1}\[[1-7]\]|[w r]_lock\([1-7]{1},[x y z w v]{1}\)/mi';
    preg_match_all($re, $execution, $matches, PREG_SET_ORDER, 0);

    $result = [];

    foreach ($matches as $match) {
        $match = $match[0];
        if (strpos($match, '(') !== false){
            $match = str_replace(')', '', $match);
            $opr = explode('(',$match);
            if (count($opr) > 1){
                if (strtolower($opr[0]) == 'rl' || strtolower($opr[0]) == 'r_lock'){
                    $result[] = 'rl('.$opr[1].')';
                }elseif (strtolower($opr[0]) == 'wl' || strtolower($opr[0]) == 'w_lock'){
                    $result[] = 'wl('.$opr[1].')';
                }elseif (strtolower($opr[0]) == 'w' || strtolower($opr[0]) == 'w'){
                    $result[] = 'w('.$opr[1].')';
                }elseif (strtolower($opr[0]) == 'r' || strtolower($opr[0]) == 'r'){
                    $result[] = 'r('.$opr[1].')';
                }elseif ($opr[0] == 'a'){
                    $result[] = 'a('.$opr[1].')';
                }elseif ($opr[0] == 'A'){
                    $result[] = 'A['.$opr[1].']';
                }elseif ($opr[0] == 'c'){
                    $result[] = 'c('.$opr[1].')';
                }
            }
        }
        elseif (strpos($match, '[') !== false){
            $match = str_replace(']', '', $match);
            $opr = explode('[',$match);
            if (count($opr) > 1){
                if ($opr[0] == 'a'){
                    $result[] = 'a['.$opr[1].']';
                }elseif ($opr[0] == 'A'){
                    $result[] = 'A['.$opr[1].']';
                }
            }
        }
        else $result[] = $match;

    }
     return implode('',$result);
}
