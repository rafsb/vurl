<?php
namespace rafsb;
/*
 * VALIDATE URL CLASS BY RAFAEL BERTOLINI
 * 
 */
class vurl {
    protected function __Curl($url, $redirect = true){
        if(! $url || ! is_string($url)) return false;
        $ch = @curl_init($url);
        if($ch === false) return false;
        @curl_setopt($ch, CURLOPT_HEADER         ,true);
        @curl_setopt($ch, CURLOPT_NOBODY         ,true);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);
        if($redirect){
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,true);
            @curl_setopt($ch, CURLOPT_MAXREDIRS      ,10);
        }else @curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,false);
        //@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
        //@curl_setopt($ch, CURLOPT_TIMEOUT        ,6);
        //@curl_setopt($ch, CURLOPT_USERAGENT      ,"Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1");
        @curl_exec($ch);
        if(@curl_errno($ch)){
            @curl_close($ch);
            return false;
        }
        $code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        @curl_close($ch);
        return $code;
    }
    protected function __GetHeaders($url, $redirect = true){
        if(! $url || ! is_string($url)) return false;
        $headers = @get_headers($url);
        if($headers && is_array($headers)){
            if($redirect) $headers = array_reverse($headers);
            foreach($headers as $hline){
                if(preg_match('/^HTTP\/\S+\s+([1-9][0-9][0-9])\s+.*/', $hline, $matches) ){
                    $code = $matches[1];
                    return $code;
                }
            }
            return false;
        }
        return false;
    }
    public function validate($url){
        if(!$url || !is_string($url)) return false;
        if(!preg_match('/^http(s)?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)) return false;
        if(vurl::__Curl($url) != 200) return false;
        return true;
    }
}