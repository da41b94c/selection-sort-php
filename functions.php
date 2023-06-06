<?php

function findMin( $list )
{
    $minIndex = 0;
    $min = $list[ $minIndex ];
    $size = sizeof( $list );
    for( $i = 1; $i < $size; $i++ )
    {
        if( $list[ $i ] < $min )
        {
            $minIndex = $i;
            $min = $list[ $minIndex ];          
        }
    }
    return $minIndex;   
}

function selectionSort( $list )
{
    $result = [];
    foreach( $list as $val )
    {
        $minIndex = findMin( $list );
        array_push( $result, $list[ $minIndex ] );
        array_splice( $list, $minIndex, 1 );        
    }
    return $result; 
}
