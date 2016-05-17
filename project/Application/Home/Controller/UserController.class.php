<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class UserController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->display();
    }
}
