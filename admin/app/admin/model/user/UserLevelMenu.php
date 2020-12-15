<?php
namespace app\admin\model\user;

use app\admin\model\system\SystemUserLevel;
use common\traits\ModelTrait;
use common\basic\BaseModel;

/**
 * 会员续费套餐 model
 * Class UserLevelMenu
 * @package app\admin\model\user
 */
class UserLevelMenu extends BaseModel
{

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'user_level_menu';

    use ModelTrait;

    public static function setWhere($where,$alias='',$userAlias='u.',$model=null)
    {
        $model=is_null($model) ? new self() : $model;
        if($alias){
            $model=$model->alias($alias);
            $alias.='.';
        }
        if(isset($where['nickname']) && $where['nickname']!='') $model=$model->where("{$userAlias}nickanme",$where['nickname']);
        if(isset($where['level_id']) && $where['level_id']!='') $model=$model->where("{$alias}level_id",$where['level_id']);
        return $model->where("{$alias}status", 1)->where("{$alias}is_del", 0);
    }
    /*
     * 查询会员续费套餐列表
     * @param array $where
     * */
    public static function getUserlevelMenu($where)
    {
        $data=self::order('month')
            ->field('*')
            ->where('is_del',0)
            ->page((int)$where['page'],(int)$where['limit'])->select();
        $data=count($data) ? $data->toArray() : [];
        if ($data) {
            foreach ($data as $k=>$v) {
                $data[$k]['month'] = $v['month'].'个月';
            }
        }
        $count=self::where('is_del',0)->order('month')->count();
        return compact('data','count');
    }

    /*
     * 清除会员等级
     * @paran int $uid
     * @paran boolean
     * */
    public static function cleanUpLevel($uid)
    {
        self::rollbackTrans();
        $res=false !== self::where('uid', $uid)->update(['is_del'=>1]);
        $res= $res && UserTaskFinish::where('uid', $uid)->delete();
        if($res){
            User::where('uid', $uid)->update(['clean_time'=>time()]);
            self::commitTrans();
            return true;
        }else{
            self::rollbackTrans();
            return self::setErrorInfo('清除失败');
        }
    }

}