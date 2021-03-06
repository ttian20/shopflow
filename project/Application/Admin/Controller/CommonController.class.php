<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    protected $_loginExcept = array();
    public function __construct() {
        parent::__construct();
        
        if (!in_array(ACTION_NAME, $this->getLoginExcept()) && !session('user')) {
            $this->redirect('/login');
        }

        $this->assign('controllerName', strtolower(CONTROLLER_NAME));
        $this->assign('actionName', strtolower(ACTION_NAME));
        $this->assign('ca', strtolower(CONTROLLER_NAME . '_' . ACTION_NAME));
    }

    protected function setLoginExcept($actArr) {
        $this->_loginExcept = $actArr;
    }

    protected function getLoginExcept() {
        return $this->_loginExcept;
    }

    protected function error($msg) {
        header('Content-type: application/json');
        echo json_encode(array('status' => 'fail', 'msg' => $msg));
        exit;
    }

    protected function success($data) {
        header('Content-type: application/json');
        echo json_encode(array('status' => 'success', 'data' => $data));
        exit;
    }
}
