<?php
namespace Common\Model;
use Think\Model;
class BaseModel extends Model {
    public function __construct() {
        parent::__construct();
    }

    public function getRow($filter, $lock = false) {
        if ($lock) {
            $row = $this->lock(true)->where($filter)->find();
        }
        else {
            $row = $this->where($filter)->find();
        }
        return $row;
    }

    public function getAll($filter) {
        $rows = $this->lock(true)->where($filter)->select();
        return $row;
    }

    public function getList($filter, $limit=10, $page=1, $order="update_time desc") {
        $row = $this->where($filter)->limit($limit)->page($page)->order($order)->select();
        return $row;
    }

    public function getCount($filter) {
        $row = $this->where($filter)->count();
        return $row;
    }
}
