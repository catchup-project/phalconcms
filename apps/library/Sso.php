<?php
/**
 * 单点登录服务
 */
class Sso
{
    /**
     * 取消服务端 HTTP401
     */
    public $pass401=false;

    /**
     * SSO服务地址
     * @var string
     */
    public $url = "http://sso.lab.mycihi.cn/sso/";

    /**
     * 代理ID
     * @var string
     */
    public $broker = "cihi";

    /**
     * 秘药
     * @var string
     */
    public $secret = "1A9624F80F6FCE52325B74255ABE7A94";

    /**
     * 不能比服务端设置的小
     * @var string
     */
    public $sessionExpire = 1800;

    /**
     * SESSION hash
     * @var string
     */
    protected $sessionToken;

    /**
     * 用户信息
     * @var array
     */
    protected $userinfo;


    /**
     * 错误信息
     *
     * 1. no_username 缺少用户名
     * 2. no_password 缺少密码
     * 3. user_not_exists 用户不存在
     * 4. bad_password 密码错误
     * 4. unknown  未知
     *
     */
    public  $error;


    /**
     * 构造函数
     */
    public function __construct($auto_attach=true)
    {
        $this->test_connectiong();
        //如果cookie存在session_token
        if (isset($_COOKIE['session_token'])) $this->sessionToken = $_COOKIE['session_token'];
        //如果设置自动粘贴token并且不存在sessiontoken,带上参数跳转的服务端
        if ($auto_attach && !isset($this->sessionToken)) {
            //跳转至SSO
            header("Location: " . $this->getAttachUrl() . "&redirect=". urlencode("http://{$_SERVER["SERVER_NAME"]}{$_SERVER["REQUEST_URI"]}"), true, 307);
            exit;
        }
    }

    //测试通讯

    public function test_connectiong ()
    {
        if(isset($_GET['testsso'])&&$_GET['testsso']==1){
            echo "connected";
        }
    }

    /**
     * 获取客户端的session_token
     *
     * @return string
     */
    public function getSessionToken()
    {
        //如果没有生成过session_token 生成session_token
        if (!isset($this->sessionToken)) {
            //随机申城session_token
            $this->sessionToken = md5(uniqid(rand(), true));
            //吧session_token写入cookie
            setcookie('session_token', $this->sessionToken, time() + $this->sessionExpire);
        }

        return $this->sessionToken;
    }

    /**
     * 生成session id
     *
     * @return string
     */
    protected function getSessionId()
    {
        if (!isset($this->sessionToken)) return null;
        return "SSO-{$this->broker}-{$this->sessionToken}-" . md5('session' . $this->sessionToken . $_SERVER['REMOTE_ADDR'] . $this->secret);
    }

    /**
     * 获取URL并传递session到sso服务器
     *
     * @return string
     */
    public function getAttachUrl()
    {
        $token = $this->getSessionToken();
        //根据token和IP和代理端秘药生成校验码传递给服务端
        $checksum = md5("attach{$token}{$_SERVER['REMOTE_ADDR']}{$this->secret}");
        //拼接URL 传递 sessioin_token和校验码到服务端
        return "{$this->url}attach?broker={$this->broker}&token=$token&checksum=$checksum";
    }


    /**
     * WEB登录
     *
     * @param string $username
     * @param string $password
     * @return boolean
     *
     */
    public function login($username, $password)
    {
        list($ret, $body) = $this->serverCmd('login', array('username'=>$username, 'password'=>$password));

        switch ($ret) {

            case 200: $this->parseInfo($body);
                return 1;
            case 401: $this->error= $body;
                return 0;
            default:  $this->error= $body;
            return 0;

        }
    }

    /**
     * PC登录
     *
     * @param string $username
     * @param string $password
     * @return boolean
     *
     */
    public function pc_login($cihiuserno, $cihikey)
    {
        list($ret, $body) = $this->serverCmd('pc_login', array('cihiuserno'=>$cihiuserno, 'cihikey'=>$cihikey));

        switch ($ret) {

            case 200: $this->parseInfo($body);
                return 1;
            case 401: $this->error= $body;
                return 0;
            default:  $this->error= $body;
            return 0;

        }
    }


    /**
     * 退出单点登录
     */
    public function logout()
    {
        if(isset($_GET['testsso'])&&$_GET['testsso']==1){
            echo "connected";exit();
        }
        //header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

        list($ret, $body) = $this->serverCmd('logout');

        echo $body;

        setcookie('session_token', '');
    }



    /**
     * 获取SSO当前登陆用户信息
     */
    public function getInfo()
    {
        if (!isset($this->userinfo)) {

            list($ret, $body) = $this->serverCmd('info');
            switch ($ret) {
                case 200:
                    return $this->parseInfo($body);
                case 401: $this->error= $body;
                    return 0;
                default:  $this->error= $body;
                return 0;
            }
        }

        return $this->userinfo;
    }

    /**
     * 执行CURL请求
     *
     * @param string $cmd   Command
     * @param array  $vars  Post variables
     * @return array
     *
     */
    protected function serverCmd($cmd, $vars = array())
    {

        $curl = curl_init($this->url . '/' . urlencode($cmd));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, "PHPSESSID=" . $this->getSessionId());

        if (!empty($vars)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $vars);
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $body = curl_exec($curl);


        $ret = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl) != 0) throw new Exception("SSO failure: HTTP request to server failed. " . curl_error($curl));

        return array($ret, $body);
    }


    /**
     * 解析返回用户信息数据
     *
     * @param string $json
     */
    protected function parseInfo($json)
    {
        $josn = json_decode($json);
        return $this->userinfo = (array)$josn;
    }

    /**
     * 获取错误信息
     */

    public function get_error()
    {
        return $this->error;
    }
}
