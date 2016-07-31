<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {
    public function index(){
        $this->show('xxx','utf-8');
    }
    
    public function dis() {
        $this->display();
    }
}