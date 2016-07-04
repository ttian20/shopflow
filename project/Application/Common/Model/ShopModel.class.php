<?php
namespace Common\Model;
use Common\Model\BaseModel;
class ShopModel extends BaseModel {
    public function getMyShopTypes($passportId) {
        $filter = array('passport_id' => $passportId);
        $types = $this->distinct(true)->field('shop_type')->where($filter)->select();
        return $types;
    }
}
