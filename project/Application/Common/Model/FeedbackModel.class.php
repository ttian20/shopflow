<?php
namespace Common\Model; 
use Think\Model;

class FeedbackModel extends Model {

    public function getRow($filter){
        return $this->where($filter)->find();
    }

    public function getList($filter, $limit=10, $page=1, $orderby='created_at desc'){
        return $this->where($filter)->limit($limit)->page($page)->order($orderby)->select();
    }

    public function getCount($filter){
        return $this->where($filter)->count();
    }

    public function getAll($filter){
        return $this->where($filter)->select();
    }

    public function saveData($data){
        $data['update_at'] = time();        
        $id = isset($data['id'])?$data['id']:'';
        if($id){
            $this->create($data);
            $this->save();
        }else{
            $data['create_at'] = time();
            $this->create($data);
            $id = $this->add();
        }

        $return = array('status' => 'success', 'data' => $id);

        return $return;

    }

    public function del($filter){
        return $this->where($filter)->delete();
    }

}
