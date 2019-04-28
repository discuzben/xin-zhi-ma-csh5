<?php
/**
 * 微信模块
 */
namespace app\mobile\controller;
use Think\Controller;
header("Content-type: text/html; charset=utf-8");

class Weixin {
	protected $access_token;
	protected $token;
	protected $appid;
	protected $appSecret;

	public function __construct(){
		parent::__construct();
		$this->token = "sharerose";
		$this->appid = "wx11a3b0bdb8cd5dff";
		$this->appSecret = "5fdb7a1b8b63430f5bbb1dbd162c1341";
		$this->access_token = $this->getAccessToken();
	}

	public function index(){
		echo $this->access_token;
	}

	/**
	 * 获取$accessToken
	 * @return mixed
	 */
	private function getAccessToken($cache =true){
		$accessToken = $this->redis->getRow("access_token");
		if($accessToken && $cache){
			return $accessToken;
		}else{
			$json=$this->https_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appSecret}");
			$arr= json_decode($json, true);
			$access_token = $arr["access_token"];
			if($access_token){
				$this->redis->setRow("access_token",$access_token,$arr['expires_in']);
			}
			return $access_token;
		}
	}

	/**
	 * 获取模版消息列表
	 */
	public function getModelList(){
		$json=$this->https_request("https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$this->access_token}");
		print_r($json);
		$json_arr = json_decode($json, true);
		if($json_arr['errcode']=="40001"){
			$this->getAccessToken(false);//重新生成一个token
		}
	}
	/**
	 * @param $url
	 * @param null $data
	 * @return mixed
	 */
	public function https_request($url, $data = null)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}



   
   

}