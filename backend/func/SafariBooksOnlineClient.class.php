<?PHP

class SafariBooksOnlineClient
{
    private $webClient;
    private $daysToKeepCredentials = 10;
    
    public function __construct(WebClient $webClient) {
        $this->webClient = $webClient;
        $this->webClient->enableCookies();
        
		$this->credentialsFile = dirname(__FILE__) . '/safari_books_online_credentials.json';
        if (!file_exists($this->credentialsFile)) {
            $this->saveCredentials('', '', 0);
        }
    }
    
    private function saveCredentials(string $email, string $password, $createdTimestamp = -1) {
        if ($createdTimestamp === -1) {
            $createdTimestamp = time();
        }
        $credentials = ['email' => $email, 'password' => $password, 'createdTimestamp' => $createdTimestamp];
        file_put_contents($this->credentialsFile, json_encode($credentials));
    }
    
    public function getCredentials() {
        $credentials = json_decode(file_get_contents($this->credentialsFile), true);
        if ($credentials['createdTimestamp'] > time() - 60*60*24*$this->daysToKeepCredentials) {
            return $credentials;
        }
        return null;
    }
    
    public function getPage(string $link) {
        return $this->webClient->readSource($link);
    }
    
    public function login(string $email, string $password) {
        $token = $this->getCrsfToken();
        if ($token === '') {
            return false;
        }
        $payload = 'csrfmiddlewaretoken=&csrfmiddlewaretoken='.$token.'&email='.urlencode($email).'&password1='.urlencode($password).'&remember=on&is_login_form=true&leaveblank=&dontchange=http%3A%2F%2F';
        $link = 'https://www.safaribooksonline.com/accounts/login/';
        $referer = 'https://www.safaribooksonline.com/';
        $this->webClient->readSource($link, $payload, [CURLOPT_REFERER => $referer]);
        return true;
    }
    
    public function selectTopics() {
        $link = 'https://www.safaribooksonline.com/register-topics/';
        $payload = 'topics=236&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=&topics=';
        $source = $this->webClient->readSource($link, $payload, [CURLOPT_REFERER => $link]);
    }
    
    public function register(): array {
        $email = 'julius'.mt_rand(1000,9999).'@gmail.com';
        $password = substr(md5(uniqid()), 0, 16);
        $this->validateEmail($email);
        
        $link = 'https://www.safaribooksonline.com/register/';
        $payload = $this->getRegisterPayload($email, $password);
        if ($payload === '') {
            return [];
        }
        $this->webClient->readSource($link, $payload, [CURLOPT_REFERER => $link]);
        $this->saveCredentials($email, $password);
        
        return ['email' => $email, 'password' => $password];
    }
    
    private function validateEmail(string $email): string {
        $link = 'https://www.safaribooksonline.com/check-email-availability/?email='.urlencode($email);
        $headers = [
            CURLOPT_REFERER => 'https://www.safaribooksonline.com/register/',
            CURLOPT_HTTPHEADER => ["X-Requested-With: XMLHttpRequest"]
        ];
        return $this->webClient->readSource($link, null, $headers);
    }
    
    private function getRegisterPayload(string $email, string $password): string {
        $csrfToken = $this->getCrsfToken();
        if ($csrfToken === '') {
            return '';
        }
        $firstName = 'Julius';
        $lastName = 'Caesar';
        $referrer = 'friend-or-coworker';
        return "csrfmiddlewaretoken={$csrfToken}&first_name={$firstName}&last_name={$lastName}&email={$email}&password1={$password}&referrer{$referrer}=&recently_viewed_bits=%5B%5D";
    }
    
    private function getCrsfToken(): string {
        $source = $this->webClient->readSource('https://www.safaribooksonline.com/register/');
        if (!strpos($source, 'csrfmiddlewaretoken')) {
            // Must already be logged in
            return '';
        }
        
        $csrfToken = explode('csrfmiddlewaretoken\' value=\'', $source)[1];
        $csrfToken = explode('\'', $csrfToken)[0];
        return $csrfToken;
    }
    
}
?>