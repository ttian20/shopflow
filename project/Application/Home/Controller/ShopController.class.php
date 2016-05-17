<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class ShopController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->display();
    }
}
