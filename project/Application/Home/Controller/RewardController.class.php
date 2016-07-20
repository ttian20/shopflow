<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class RewardController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $tasks_mdl = D('Tasks');
        $tasks_filter = array('passport_id' => $this->_passport['id']);
        $count = $tasks_mdl->getCount($tasks_filter);

        $utils = new \Common\Lib\Utils();
        $pagination = $utils->pagination($count, C('PAGE_LIMIT'));

        if (!isset($_GET['p']) || !$_GET['p']) {
            $page = 1;
        }
        else {
            $page = intval($_GET['p']);
        }
        $tasks = $tasks_mdl->getLists($tasks_filter);
        $this->assign('page', $pagination);
        $this->assign('tasks', $tasks);

        $taskTypes = $this->_getSearchTypes();
        $this->assign('taskTypes', $taskTypes);
        $this->display();
    }
}
