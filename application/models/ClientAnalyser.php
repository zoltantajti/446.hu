<?php
class ClientAnalyser extends CI_Model {
    protected $userAgent;
    protected $os, $browser;
    protected $blockOS = array("Windows");
    public function __construct()
    {
        parent::__construct();
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->os = $this->getOS();
        $this->browser = $this->getBrowser();
        $this->clientAuthorize();
    }

    public function getOS()
    {
        $os = null;
        if (preg_match('/Windows/i', $this->userAgent)) {
            $os = "Windows";
        } elseif (preg_match('/Mac OS X/i', $this->userAgent)) {
            $os = "Mac OS X";
        } elseif (preg_match('/Linux/i', $this->userAgent)) {
            $os = "Linux";
        } elseif (preg_match('/Android/i', $this->userAgent)) {
            $os = "Android";
        } elseif (preg_match('/iPhone/i', $this->userAgent)) {
            $os = "iOS";
        };
        return $os;
    }
    public function getBrowser()
    {        
        $browser = null;
        if (preg_match('/MSIE/i', $this->userAgent) && !preg_match('/Opera/i', $this->userAgent)) {
            $browser = "Internet Explorer";
        } elseif (preg_match('/Edge/i', $this->userAgent) || preg_match('/Edg/i', $this->userAgent)){
            $browser = "Microsoft Edge";
        } elseif (preg_match('/Firefox/i', $this->userAgent)) {
            $browser = "Mozilla Firefox";
        } elseif (preg_match('/Chrome/i', $this->userAgent)) {
            $browser = "Google Chrome";
        } elseif (preg_match('/Safari/i', $this->userAgent)) {
            $browser = "Apple Safari";
        } elseif (preg_match('/Opera/i', $this->userAgent)) {
            $browser = "Opera";
        } elseif (preg_match('/Netscape/i', $this->userAgent)) {
            $browser = "Netscape";
        };
        return $browser;
    }

    public function isAllowedOS()
    {
        return (!in_array($this->os,$this->blockOS) ? true : false);
    }
    public function clientAuthorize()
    {
        $token = @$_SERVER["HTTP_TOKEN"];
        if($token){
            $key = "0123456789ABCDEF0123456789ABCDEF";
            $dText = $this->decrypt($token,$key);
            if($dText == "446PontHUClient"){
                return true;
            }else{
                return false;
            };
        }else return false;
    }
        private function decrypt($encrypted,$key){
            $decoded = base64_decode($encrypted);
            $iv = substr($decoded, 0, 16); // Az inicializációs vektor (IV) 16 bájton
            $cipherText = substr($decoded, 16);
        
            return openssl_decrypt($cipherText, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        }

    public function getMessage()
    {
        $html = '
<!doctype html>
<html lang="en" class="h-100" data-bs-theme="dark">
    <head>
        <base href="'.base_url().'" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tiltott - 446.hu</title>
        <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/bootstrap.min.css" />
        <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/fa.all.min.pro.css" />
        <link rel="stylesheet" media="screen" type="text/css" href="./assets/css/system.css" />
    </head>
    <body class="d-flex flex-column h-100">
        <main class="flex-shrink-0">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger">
                            A biztonsági házirend tiltja, hogy megtekintsd az oldalt!<br/>
                            <hr/>
                            Amennyiben, regisztrált tag vagy, <a href="#" target="_blank">ide kattintva</a> letöltheted a szükséges klienst a számítógépedre!
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>';
        return $html;
    }
}