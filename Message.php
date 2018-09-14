<?php

/**
 * Created by PhpStorm.
 * User: chuanbin
 * Date: 2018/9/14
 * Time: 18:28
 */
include_once ('./RestClient.php');
class Message
{
    private $_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?';
    private $_send_url = 'https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=';
    private $_corp_id = 'xxxxx';//这里需要自己申请 企业微信中的corp_id
    private $_corp_secret = 'xxxxx';//这里需要自己申请 企业微信中的corp_secret
    private $_agent_id = '1000002';
    private $_token_file = './token_file';
    private $_token;
    private $_req_obj;

    public function __construct()
    {
        $this->_req_obj = new \RestClient();
        $this->_get_token();
    }

    private function _get_token(){
        //检测是否存在未过期token
        $token = file_get_contents($this->_token_file);
        $file_time = filemtime($this->_token_file);
        if(time()-$file_time>3500){
            echo 'get new token';
            $for_token['corpid'] = $this->_corp_id;
            $for_token['corpsecret'] = $this->_corp_secret;
            $http_str = http_build_query($for_token);
            $rzt = $this->_req_obj->get($this->_token_url.$http_str);
            $token = json_decode($rzt->response,true);
            $token = $token['access_token'];
            file_put_contents($this->_token_file, $token);

        }
        $this->_token = $token;
    }

    public function send_news_msg($text){
        $url = $this->_send_url.$this->_token;
        /*news 消息*/
        $param['msgtype'] = 'news';
        $param['touser'] = '@all';
        $param['agentid'] = $this-_agent_id;
        $arr[0]['title'] = '天气早知道 '.date('Y-m-d');
        $arr[0]['description'] = $text;
        $arr[0]['url'] = 'http://www.jiucaijidan.com/index.php?a=wx';
        $param['news']['articles'] = $arr;
        $r = $this->_req_obj->post($url,$param);
        return $r->response;
    }
}