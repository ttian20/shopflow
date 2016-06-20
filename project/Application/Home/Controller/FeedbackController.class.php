<?php
namespace Home\Controller;
use Home\Controller\CommonController;
class FeedbackController extends CommonController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $passport = $this->passport;
        if(!$passport) {
            $this->redirect("/login");
        }
        $passport_id = $passport['id'];
        
        $limit = 10;
        $feedMdl = D('Feedback');
        $page = $_GET['page']?$_GET['page']:1;
        $count = $feedMdl->getCount(array('passport_id' => $passport_id));
        $feeds = $feedMdl->getList(array('passport_id' => $passport_id), $limit, $page);

        $utils = new \Common\Lib\Utils();
        $pagination = $utils->pagination($count, $limit);

        $this->assign('feeds', $feeds);
        $this->assign('passport', $passport);
        $this->assign('types', C('FEEDBACK_TYPE'));
        $this->assign('page', $pagination);
        $this->display();
    }

    public function save() {
        $passport = $this->passport;

        if(!$passport) {
            echo "您没有登录，不能操作";
            exit();
        }

        $post = $_POST;
        $post['passport_id'] = $passport['id'];
        if($post['content'] == '') {
            echo "请填写意见内容";
            exit();
        }

        if($post['qq'] == '' && $post['email'] == '' && $post['mobile'] == '') {
            echo "至少填写一个联系方式，方便我们联系您";
            exit();
        }
        
        $feedMdl = D('Feedback');
        $res = $feedMdl->saveData($post);
        if($res['status'] == 'success') {
            $this->redirect('/home/feedback');
        }

    }

}

