<?php
    //获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'connect';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }


else{

        //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string( $postArr );
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号,此公众号为测试公众号！';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
        }
      
      //判断该数据包是否是文本消息
        if( strtolower( $postObj->MsgType) == 'text'){
             //接受文本信息
    		$content = $postObj->Content;
          	//根据城市获取城市编码
          $url = "http://123.207.147.163/city/".$content;
          $a = file_get_contents($url);
          $res = json_decode($a, true);
          if($res['code'] == '200'){
           
          	//根据编码获取天气信息
         $weather = file_get_contents('http://123.207.147.163/weather/'.$res['data']);
         $res = json_decode($weather, true); 
          	$content = $res['data'];
             //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  =$content;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
        }
        else{
           //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  ='请输入城市名，查询天气';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
        
        
        }
        
    }
}