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

    public function getinfo() {
        if (!$_GET['id']) {
            $this->error('请选择店铺');
        }
        $id = trim($_GET['id']);
        $shopMdl = D('Shop');
        $filter = array('id' => $id);
        $shop = $shopMdl->getRow($filter);
        $this->success(array('shop' => $shop));
    }

    public function add() {
        $necessaryArgs = array('shop_name', 'shop_type', 'shop_url', 'sender_name', 'sender_mobile', 'sender_province', 'sender_city', 'sender_district', 'sender_address', 'sender_code');
        $p = I('post.');
        foreach ($necessaryArgs as $necessaryArg) {
            if (!in_array($necessaryArg, $p)) {
                $this->error('缺少参数');
            }
        }

        $now = time();
        $data = array(
            'passport_id' => $this->passport['id'],
            'shop_name' => trim($p['shop_name']),
            'shop_type' => trim($p['shop_type']),
            'shop_url' => trim($p['shop_url']),
            'sender_name' => trim($p['sender_name']),
            'sender_mobile' => trim($p['sender_mobile']),
            'sender_province' => trim($p['sender_province']),
            'sender_city' => trim($p['sender_city']),
            'sender_district' => trim($p['sender_district']),
            'sender_address' => trim($p['sender_address']),
            'sender_code' => trim($p['sender_code']),
            'created_at' => $now,
            'updated_at' => $now,
        );

        $shopMdl = D('Shop');
        $result = $shopMdl->add($data);
        if ($result) {
            $this->success(array('redirect' => '/home/shop'));
        }
        else {
            $this->error('绑定店铺失败');
        }
    }

    public function edit() {
        $necessaryArgs = array('id', 'shop_name', 'shop_url', 'sender_name', 'sender_mobile', 'sender_province', 'sender_city', 'sender_district', 'sender_address', 'sender_code');
        $p = I('post.');
        foreach ($necessaryArgs as $necessaryArg) {
            if (!in_array($necessaryArg, $p)) {
                $this->error('缺少参数');
            }
        }
        
        $shopMdl = D('Shop');
        $shop = $shopMdl->getRow(array('id' => trim($p['id']), 'passport_id' => $tihs->passport['id']));
        if (!$shop) {
            $this->error('没有权限');
        }

        $now = time();
        $data = array(
            'id' => trim($p['id']),
            'shop_name' => trim($p['shop_name']),
            'shop_url' => trim($p['shop_url']),
            'sender_name' => trim($p['sender_name']),
            'sender_mobile' => trim($p['sender_mobile']),
            'sender_province' => trim($p['sender_province']),
            'sender_city' => trim($p['sender_city']),
            'sender_district' => trim($p['sender_district']),
            'sender_address' => trim($p['sender_address']),
            'sender_code' => trim($p['sender_code']),
            'updated_at' => $now,
        );

        $result = $shopMdl->save($data);
        if ($result) {
            $this->success(array('redirect' => '/home/shop'));
        }
        else {
            $this->error('编辑绑定店铺失败');
        }
    }

    public function delete() {
        $id = trim($_GET['id']);

        $shopMdl = D('Shop');
        $shop = $shopMdl->getRow(array('id' => $id, 'passport_id' => $tihs->passport['id']));
        if (!$shop) {
            $this->error('没有权限');
        }
        else {
            $data = array('id' => $id, 'status' => 'deleted', 'updated_at' => $now);
            $result = $shopMdl->save($data);
            $this->success(array('redirect' => '/home/shop'));
        }
    }
}
