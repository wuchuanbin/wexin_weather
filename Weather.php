<?php

/**
 * Created by PhpStorm.
 * User: chuanbin
 * Date: 2018/9/14
 * Time: 10:57
 */
include ('./RestClient.php');
class Weather
{
    private $_key = '90e0a201ade8e3a5bb6563f4c1a87f78';
    private $_api = 'http://api.weatherdt.com/common/';

    private $_config = array(
        '000' => '',
        '001' => '白天天气 ',
        '002' => '晚上天气 ',
        '003' => '全天最高温度（单位摄氏度）',
        '004' => '全天最低温度（单位摄氏度）',
        '005' => '白天风力编码（转码）',
        '006' => '晚上风力编码（转码）',
        '007' => '白天风向编码（转码）',
        '008' => '晚上风向编码（转码）',
    );

    private $_tianqi = array(
        '00'  =>array('晴','					Sunny'),
        '01'  =>array('多云','						Cloudy'),
        '02'  =>array('阴','					Overcast'),
        '03'  =>array('阵雨','						Shower'),
        '04'  =>array('雷阵雨				','						Thundershower'),
        '05'  =>array('雷阵雨伴有冰雹		','								Thundershower with hail'),
        '06'  =>array('雨夹雪				','					Sleet'),
        '07'  =>array('小雨				','					Light rain'),
        '08'  =>array('中雨				','					Moderate rain'),
        '09'  =>array('大雨				','					Heavy rain'),
        '10'  =>array('暴雨				','					Storm'),
        '11'  =>array('大暴雨				','							Heavy storm'),
        '12'  =>array('特大暴雨			','					Severe storm'),
        '13'  =>array('阵雪				','					Snow flurry'),
        '14'  =>array('小雪				','					Light snow'),
        '15'  =>array('中雪				','					Moderate snow'),
        '16'  =>array('大雪				','					Heavy snow'),
        '17'  =>array('暴雪				','					Snowstorm'),
        '18'  =>array('雾					','					Foggy'),
        '19'  =>array('冻雨				','						Ice rain'),
        '20'  =>array('沙尘暴				','						Duststorm'),
        '21'  =>array('小到中雨			','							Light to moderate rain'),
        '22'  =>array('中到大雨			','							Moderate to heavy rain'),
        '23'  =>array('大到暴雨			','							Heavy rain to storm'),
        '24'  =>array('暴雨到大暴雨		','							Storm to heavy storm'),
        '25'  =>array('大暴雨到特大暴雨		','						Heavy to severe storm'),
        '26'  =>array('小到中雪			','							Light to moderate snow'),
        '27'  =>array('中到大雪			','							Moderate to heavy snow'),
        '28'  =>array('大到暴雪			','							Heavy snow to snowstorm'),
        '29'  =>array('浮尘				','							Dust'),
        '30'  =>array('扬沙				','							Sand'),
        '31'  =>array('强沙尘暴			','							Sandstorm'),
        '53'  =>array('霾					','						Haze'),
        '99'  =>array('无					','							Unknown'),
        '32'  =>array('浓雾				','							Dense fog'),
        '49'  =>array('强浓雾				','							Strong fog'),
        '54'  =>array('中度霾				','							Moderate haze'),
        '55'  =>array('重度霾				','							Severe haze'),
        '56'  =>array('严重霾				','							Severe haze'),
        '57'  =>array('大雾				','							Dense fog'),
        '58'  =>array('特强浓雾			','							Extra heavy fog'),
        '301' =>array('雨					','						rain'),
        '302' =>array('雪					','						snow'),
    );
    private $fenxiang = array(
        '0'=>'无风',
        '1'=>'东北风',
        '2'=>'东风',
        '3'=>'东南风',
        '4'=>'南风',
        '5'=>'西南风',
        '6'=>'西风',
        '7'=>'西北风',
        '8'=>'北风',
        '9'=>'旋转风',
    );
    private $fensu = array(
        '0'=>'微风',
        '1'=>'3-4级',
        '2'=>'4-5级',
        '3'=>'5-6级',
        '4'=>'6-7级',
        '5'=>'7-8级',
        '6'=>'8-9级',
        '7'=>'9-10级',
        '8'=>'10-11级',
        '9'=>'11-12级',
    );


    private function _get_url($area,$type){
        //req url
        $req['area'] = $area;
        $req['type'] = $type;
        $req['key'] = $this->_key;
        $query = http_build_query($req);
        $req_url = $this->_api.'?'.$query;
        return $req_url;
    }

    public function get($area,$type){
        $rest_client = new RestClient();
        $req_url = $this->_get_url($area,$type);
        $response = $rest_client->get($req_url);
        $data = json_decode($response->response,true);
        $ret = $this->_format($data,$area);
        return $ret;
    }

    private function _format($weather,$area){
        $out = array();
        $data = $weather['forecast']['24h'][$area]['1001001'];
        foreach($data as $key=>$val){
            $tianqi_1 = $this->_tianqi[$val['001']];
            $tianqi_2 = $this->_tianqi[$val['002']];
            $str = '白天天气 '.$tianqi_1[0];
            $str .= '夜晚天气 '.$tianqi_2[0];
            $str .= ' 最高气温 '.$val['003'];
            $str .= ' 最低气温 '.$val['004'];
            $fen_1 = $this->fenxiang[$val['007']];
            $fen_2 = $this->fenxiang[$val['008']];

            $fensu_1 = $this->fensu[$val['005']];
            $fensu_2 = $this->fensu[$val['006']];
            $str .= ' 白天风向 '.$fen_1.' '.$fensu_1;
            $str .= ' 夜晚风向 '.$fen_2.' '.$fensu_2;
            $out[$key] = $str;
        }
        return $out;
    }
}