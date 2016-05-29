<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class InviteController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $encodePassportId = \Common\Lib\Idhandler::encode($this->passport['id']);
        $inviteUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/register?referrer=' . $encodePassportId;
        $this->assign('inviteUrl', $inviteUrl);
        $this->display();
    }
}
