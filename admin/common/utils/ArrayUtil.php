<?php


namespace common\utils;




class ArrayUtil
{
    //todo
    /**
     * 对一维数组进行排序  [ map0,map2,map2]
     * @param array $list |  要排序的一维数组
     * @param string $itemKey | itemMap
     * @param int $sortType | 排序类型
     * @return array |  [ map0,map2,map2]
     */
    public static function sortByValue(array $list,string $itemKey,$sortType=SORT_ASC){
        if (count($list)==1){
            return $list;
        }
        //根据字段last_name对数组$data进行降序排列
        $dataColumn = array_column($list,$itemKey);
        array_multisort($dataColumn,$sortType,$list);
        return $list;
    }

}