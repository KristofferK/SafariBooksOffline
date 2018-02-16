<?PHP
class WebClient
{
	public function __construct(string $cookieFile) {
		$this->saveCookies = false;
		$this->cookiePath = dirname(__FILE__) . '/web_client_cookie.'.md5($cookieFile);
	}
	
    public function readSource(string $url, $postData = null, $options=[]): string {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36');
        
		if ($this->saveCookies) {
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);	
		}
        
        if ($postData != null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        
        foreach ($options as $k => $v) {
            curl_setopt($ch, $k, $v);
        }

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    
    public function enableCookies() {
        $this->saveCookies = true;
    }
    
    public function disableCookies() {
        $this->saveCookies = false;
    }
    
    public function flushCookieFile() {
        unlink($this->cookiePath);
    }
}
?>