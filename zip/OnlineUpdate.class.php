<?php

class OnlineUpdate
{
    /*更新的根目录*/
    private $root;
    
    /*zip包的下载路径*/
    private $package;

    /*ajax下载进度信息的请求页面,任意指定一个PHP文件即可*/
    private $progress;

    /*更新zip包的URL*/
    private $remoteZip;

    private $errors;

    private $ch;

    private $json;

    private $length;

    private $curLength;

    private $name;

    public function __construct($root,$remoteZip,$progress = "")
    {
        $this->root = $root;
        $this->progress = $progress;
        $this->remoteZip = $remoteZip;
        $this->ch = curl_init();
        $this->json = PRO_CACHE . "/" . sha1($_SERVER["REMOTE_ADDR"]) . ".json";
        $this->curLength = 0;
        $this->name = date("Y-m-d").".zip";
    }

    public function update()
    {
        $opt = array(
            CURLOPT_URL => $this->remoteZip,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_NOPROGRESS => 0,
            CURLOPT_HEADERFUNCTION => array($this,"read_header"),
            CURLOPT_WRITEFUNCTION => array($this,"read_content"),
        );
        curl_setopt_array($this->ch,$opt);
        $ret = curl_exec($this->ch);
        if (!$ret) {
            return false;
        }
        $zip = new ZipArchive(); 
        $zip->open(ZIP."/".$this->name); 
        $zip->extractTo(ROOT . $this->root);
        $zip->close(); 
        $json = json_encode(array("progress" => 0));
        file_put_contents($this->json,$json);
        return true;
    }

    public static function getProgressFile()
    {
        return $_SERVER["SERVER_NAME"] . "/mom/zip/json_cache/".sha1($_SERVER["REMOTE_ADDR"]).".json" ;
    }

    public function error()
    {
        return curl_error($this->ch);
    }

    private function read_header($ch,$str)
    {
        preg_match("/Content-Length: (\d+)/",$str,$matches);
        if ($matches) {
           $this->length = $matches[1];
        }
        return strlen($str);
    }

    private function read_content($ch,$str)
    {
        $this->curLength += strlen($str);
        $prog = $this->curLength / $this->length;
        $json = json_encode(array("progress" => "$prog"));
        $fh = fopen(ZIP."/".$this->name,"a");
        fwrite($fh,$str);
        file_put_contents($this->json,"$json");
        return strlen($str);
    }
}

