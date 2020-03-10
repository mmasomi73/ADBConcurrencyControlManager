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
