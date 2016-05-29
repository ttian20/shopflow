<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class ShopController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $pageno = $_GET['p'] ? $_GET['p'] : 1;
        $pagesize = 10;

        $shopMdl = D('Shop');
        $filter = array('passport_id' => $this->passport['id']);
        $count = $shopMdl->getCount($filter);
        $shops = $shopMdl->getList($filter, $pagesize, $pageno);
        $utils = new \Common\Lib\Utils();
        $pagination = $utils->pagination($count, $pagesize);

        $this->assign('shops', $shops);
        $this->assign('page', $pagination);
        $this->display();
    }
}
