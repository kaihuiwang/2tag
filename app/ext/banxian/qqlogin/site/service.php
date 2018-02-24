<?php
class ext_banxian_qqlogin_site_service extends zl_ext_service{

    function getQQconfig($callBackUrl=""){
        $qqk = zl::config()->getExt('app.openlogin.qq.appkey');
        $qqs = zl::config()->getExt('app.openlogin.qq.appsecret');
        $callBackUrl = $callBackUrl ? $callBackUrl : "/qqlogin";
        $domain = str_replace("http://","",zl::config()->get('app.siteUrl'));
        $scope='get_user_info,add_share';
        $callback_url = $domain.url($callBackUrl);
        return array($qqk,$qqs,$scope,$callback_url);
    }

    function qqAccess($callBackUrl=""){
        list($qqk,$qqs,$scope,$callback_url) = $this->getQQconfig($callBackUrl);
        $qq=new ext_banxian_qqlogin_libs_qqlogin($qqk, $qqs);
        $login_url=$qq->login_url($callback_url, $scope);
        zl::redirect($login_url,200);
        exit;
    }

    function getQQAccessToken($code,$callBackUrl=""){
        list($qqk,$qqs,$scope,$callback_url) = $this->getQQconfig($callBackUrl);
        $qq=new ext_banxian_qqlogin_libs_qqlogin($qqk, $qqs);
        return $qq->access_token($callback_url, $code);
    }

    function getQQInfo($accessToken,$callBackUrl=""){
        list($qqk,$qqs,$scope,$callback_url) = $this->getQQconfig($callBackUrl);
        $qq=new ext_banxian_qqlogin_libs_qqlogin($qqk, $qqs,$accessToken);
        $qq_oid=$qq->get_openid();
        $openid=$qq_oid['openid']; //获取登录用户open id
        $result = $qq->get_user_info($openid);
        if(!isset($result['nickname'])) return errorReturn("登录失败");
        $result['openid'] = $openid;
        return $result;
    }

}