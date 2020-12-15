<?php

namespace app\api\controller\invoice;

use app\admin\model\system\SystemAttachment;
use app\models\routine\RoutineCode;
use app\models\routine\RoutineQrcode;
use app\models\store\StoreOrder;
use app\models\user\User;
use app\models\invoice\Invoice;
use app\models\user\UserExtract;
use app\Request;
use common\services\GroupDataService;
use common\services\SystemConfigService;
use common\services\UploadService;
use common\services\UtilService;

/**
 * 发票类
 * Class InvoiceController
 * @package app\api\controller\user
 */
class InvoiceController
{
    /**
     * 发票列表
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        list($page, $limit, $type) = UtilService::getMore([
            ['page', 0], ['limit', 0], ['type', 0]
        ], $request, true);
        return app('json')->successful(Invoice::userInvoice($request->uid(), $page, $limit, $type));
    }


}