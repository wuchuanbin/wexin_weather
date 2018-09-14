<?php
/**
 * Created by PhpStorm.
 * User: chuanbin
 * Date: 2018/9/14
 * Time: 11:14
 */


include ('./Weather.php');
include ('./Message.php');

$cmd = $_GET['cmd'];
$obj = new Weather();
$json = $obj->get('101010100','forecast');
$msg = new Message();
$r = $msg->send_news_msg($json[0]);
var_dump($r);
