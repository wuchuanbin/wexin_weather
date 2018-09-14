### 企业微信推送消息到分组

###### Message.php 中配置 private信息


```
private $_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?';
private $_send_url = 'https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=';
private $_corp_id = 'xxxxx';//这里需要自己申请 企业微信中的corp_id
private $_corp_secret = 'xxxxx';//这里需要自己申请 企业微信中的corp_secret
private $_agent_id = '1000002';
private $_token_file = './token_file';
```

###### 运行方式

```
php index.php
```
