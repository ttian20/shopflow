<?php
namespace Common\Lib;
use \Think\Page;

class Pagination extends Page{
    private $p          = 'p'; //分页参数名
    private $url        = ''; //当前链接URL
    private $base_url   = '';
    private $nowPage    = 1;
    public $rollPage    = 6;

    private $config  = array(
        'header' => '<span class="rows">共 %TOTAL_ROW% 条记录</span>',
        'prev'   => '&lsaquo;',
        'next'   => '&rsaquo;',
        'start'  => '&laquo;',
        'end'    => '&raquo;',
        'first'  => '1',
        'last'   => '...%TOTAL_PAGE%',
        'theme'  => '%START_PAGE% %UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %END_PAGE% %GOTO%',
    );

    public function __construct($totalRows, $listRows=20, $parameter = array()) {
        parent::__construct($totalRows, $listRows, $parameter);
        $this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
    }

    private function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }

    public function show() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        /*
        $base_url_path = '';
        foreach ($this->parameter as $key => $value) {
            if ($value != '' && $key != $this->p) {
                $base_url_path .= '/'.$key.'/'.$value;
            }
        }
        $this->base_url = U(ACTION_NAME, array()).$base_url_path;
        */

        $request_uri = $_SERVER['REQUEST_URI'];
        $url_arr = parse_url($request_uri);
        $this->base_url = $url_arr['path'];

        $this->parameter = array();
        if ($url_arr['query']) {
            parse_str($url_arr['query'], $parameters);
            $this->parameter = $parameters;
        }

        $this->parameter[$this->p] = '[PAGE]';
        $this->url = $this->base_url . '?' . http_build_query($this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
		$now_cool_page_ceil = ceil($now_cool_page);
		$this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ? '<li><a class="pagi-btn" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a></li>' : '<li><span class="pagi-btn disabled">'.$this->config['prev'].'</span></li>';

        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ? '<li><a class="pagi-btn" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a></li>' : '<li><span class="pagi-btn disabled">'.$this->config['next'].'</span></li>';

        //首页
        $start_page = $up_row > 0 ? '<li><a href="'.$this->url(1).'" class="pagi-btn">'.$this->config['start'].'</a></li>' : '<li><span class="pagi-btn disabled">'.$this->config['start'].'</span></li>';

        //末页
        $end_page = $down_row <= $this->totalPages ? '<li><a href="'.$this->url($this->totalPages).'" class="pagi-btn">'.$this->config['end'].'</a></li>' : '<li><span class="pagi-btn disabled">'.$this->config['end'].'</span></li>';

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            $the_first .= '<li><a href="' . $this->url(1) . '">' . $this->config['first'] . '</a></li>';
            if(($this->nowPage - $now_cool_page) > 1){
                $the_first .= '<li><span>...</span></li>';
            }
        }

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            if(($this->nowPage + $now_cool_page + 1) < $this->totalPages){
                $the_end .= '<li><span>...</span></li>';
            }
            $the_end .= '<li><a href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a></li>';
        }

        //跳转
        $goto_bar = '';
        if ($this->totalPages > 1) {
            $goto_bar .= '<li class="pagi-goto"><form action="">';
            foreach ($this->parameter as $k => $v) {
                if ($this->p == $k) {
                    continue;
                }
                if (!is_array($v)) {
                    $goto_bar .='<input name="'.$k.'" type="hidden" value="'.$v.'">';
                }
                else {
                    foreach ($v as $kk => $vv) {
                        $goto_bar .='<input name="'.$k.'['.$kk.']" type="hidden" value="'.$vv.'">';
                    }
                }
            }
    
            $goto_bar .='<input name="'.$this->p.'" type="text" class="input-text input-mini" placeholder="跳转至"><button class="btn" type="submit">确定</button></form></li>';
        }

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
			if(($this->nowPage - $now_cool_page) <= 0 ){
				$page = $i;
			}elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
				$page = $this->totalPages - $this->rollPage + $i;
			}else{
				$page = $this->nowPage - $now_cool_page_ceil + $i;
			}
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= '<li><a href="' . $this->url($page) . '">' . $page . '</a></li>';
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    $link_page .= '<li><span class="active">' . $page . '</span></li>';
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%START_PAGE%', '%END_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%GOTO%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $start_page, $end_page, $the_first, $link_page, $the_end, $goto_bar, $this->totalRows, $this->totalPages),
            $this->config['theme']);

        return ($this->totalPages > 1) ?  "<div class='pagination'><ul>{$page_str}</ul></div>" : "";
    }
}
