<?php
/**
 * 
 * @author chenlin
 *
 */
namespace Multiple\Library;
class Func{
	/**
	 * 
	 * @param Phalcon\Mvc\Model\Resultset\Simple $obj
	 * @return array
	 */
	public static function toOneArray($obj,$column=null){
		$arr=array();
		if(count($obj)){
			foreach($obj as $v){
				if($column!==null){
					$arr[]=$v[$column];
				}else{
					$arr[]=$v;
				}
			}
		}
		return $arr;
	}
	
	public static function getip() {
		static $ip = '';
		if($ip==''){
			if(isset($_SERVER['REMOTE_ADDR'])){
				$ip = $_SERVER['REMOTE_ADDR'];
			}elseif(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
				$ip = $_SERVER['HTTP_CDN_SRC_IP'];
			} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
				foreach ($matches[0] AS $xip) {
					if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
						$ip = $xip;
						break;
					}
				}
			}
		}
		return $ip;
	}
}