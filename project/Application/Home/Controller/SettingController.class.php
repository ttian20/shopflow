<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class SettingController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->display();
    }
}
