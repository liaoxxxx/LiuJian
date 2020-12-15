<?php
namespace app\api\controller\user;

use app\models\system\SystemUserLevel;
use app\models\user\User;
use app\models\user\UserLevelMenu;
use app\models\system\SystemUserTask;
use app\models\user\UserLevel;
use app\models\user\UserRecharge;
use app\Request;
use common\services\UtilService;

/**
 * 会员等级类
 * Class UserLevelController
 * @package app\api\controller\user
 */
class UserLevelController
{

    /**
     * 检测用户是否可以成为会员
     * @param Request $request
     * @return mixed
     */
    public function detection(Request $request)
    {
        return app('json')->successful(UserLevel::setLevelComplete($request->uid()));
    }

    /**
     * 会员等级列表
     * @param Request $request
     * @return mixed
     */
    public function grade(Request $request)
    {
        return app('json')->successful(SystemUserLevel::getLevelList($request->uid()));
    }

    /**
     * 会员充值
     * @param Request $request
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function recharge(Request $request)
    {
        list($level_menu_id) = UtilService::postMore([['level_menu_id',0]], $request, true);
        $userinfo = User::find($request->uid());
        $level_menu = UserLevelMenu::where(['id'=>$level_menu_id,'is_del'=>0])->find()->toArray();
        if (!$level_menu) return app('json')->fail('未找到该套餐');
        $rechargeOrder = UserRecharge::addRecharge($request->uid(),$level_menu['price'],'level');
        if(!$rechargeOrder) return app('json')->fail('充值订单生成失败!');
        //查询会员记录
        $user_level = UserLevel::where(['uid'=>$request->uid(),'is_del'=>0])->find();
        $month_time = $level_menu['month']*24*3600;
        if ($user_level) {//若存在则续费
            if ($user_level['valid_time']>time()){
                $data = ['valid_time'=>$user_level['valid_time']+$month_time];
            } else {
                $data = ['valid_time'=>time()+$month_time];
            }
            if (!UserLevel::update($data, ['uid' => $request->uid()])) return app('json')->fail('充值订单生成失败!');
        } else {
            $data=[
                'status'=>1,
                'grade'=>1,
                'uid'=>$request->uid(),
                'add_time'=>time(),
                'level_id'=>1,
                'discount'=>99,
                'valid_time'=>$month_time+time(),
            ];
            $data['mark']='尊敬的用户'.$userinfo['nickname'].'在'.date('Y-m-d H:i:s',time()).'成为了会员';
            if (!UserLevel::create($data)) return app('json')->fail('充值订单生成失败!');
        }
        try{
            return app('json')->successful(UserRecharge::jsPay($rechargeOrder));
        }catch (\Exception $e){
            return app('json')->fail($e->getMessage());
        }

    }

    /**
     * 充值套餐
     * @param Request $request
     * @return mixed
     */
    public function rechargeMenu(Request $request)
    {
        return app('json')->successful(UserLevelMenu::getLevelMenu($request->uid()));
    }

    /**
     * 获取等级任务
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function task(Request $request, $id)
    {
        return app('json')->successful(SystemUserTask::getTashList($id,$request->uid()));
    }

}