<?php

use Carbon\Carbon;


function randomString($length = 6)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $length; $i++) {
        $randstring .= $characters[rand(0, strlen($characters)-1)];
    }
    return $randstring;
}

function getRowNumber($paginationElem, &$counter){
	return (($paginationElem->currentPage() - 1 ) * $paginationElem->perPage() )+ ++$counter;
}
