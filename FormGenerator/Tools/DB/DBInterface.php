<?php
/**
 * @author selcukmart
 * 3.02.2022
 * 14:09
 */

namespace FormGenerator\Tools\DB;


interface DBInterface
{
    public static function getInstance();

    public static function getRow($id,$table): array;

    public static function rowCount($query): int;

    public static function query($sql);

    public static function select($where_arr, $table);

    public static function getAllFieldOptions($table):array;
}