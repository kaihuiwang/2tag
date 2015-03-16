<?php
//https://github.com/nette/mail
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

class zl_mail extends Message
{
    protected static $_instance = null;

    /**
     * @return zl_mail
     */
    public static function mail()
    {
        $className = get_called_class();
        if (!isset(self::$_instance[$className]) || !self::$_instance[$className]) {

            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    function dosend()
    {
        if(!zl::config()->get("app.mail.enable")) return true;
        $className = get_called_class();
        $mailConfig = zl::config()->get("app.mail.smtp");

        $mailer = new SmtpMailer($mailConfig);

        return $mailer->send(self::$_instance[$className]);
    }

    function send($recevierArr,$subject,$body,$attachment=""){
        $className = get_called_class();
        $obj = self::$_instance[$className];
        $sender = zl::config()->get("app.mail.sender");

        $obj->setFrom($sender);
        if(is_array($recevierArr)){
            foreach($recevierArr as $v){
                $obj->addTo($v);
            }
        }else{
            $obj->addTo($recevierArr);
        }
        $obj->setSubject($subject);
        $obj->setHTMLBody($body);
        if($attachment) $obj->addAttachment($attachment);
//        dd($obj);
        return $obj->dosend();
    }

}