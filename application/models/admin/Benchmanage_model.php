<?php
/**
 *@Desc：后台工作台管理 
*/
class Benchmanage_model extends CI_Model{
	
	public function __construct(){
		$this->load->database();
		$this->load->library('pubfunction');
	}

	/**
	 *@Desc：获得所有的工作台数据
	 *@param：type  1：全部、2 今日、3：昨天、4：最近7日、5:最近30日
	 *@return：json
	*/
	public function getAllInfo($type){
		switch ($type) {
			case '1':
				//获得用户/企业数
				$userCount = $this->db->select('id')->where('status','5')->get('userRegisterInfo')->num_rows();
				$userCount = empty($userCount)?array('userCount'=>'0'):array('userCount'=>$userCount);

				//获得广告数
				$adCount = $this->db->select('id')->get('adtalkInfo')->num_rows();
				$adCount = empty($adCount)?array('adCount'=>'0'):array('adCount'=>$adCount);

				//获得投放广告数
				$adPlayCount = $this->db->select('id')->get('adPlayInfo')->num_rows();
				$adPlayCount = empty($adPlayCount)?array('adPlayCount'=>'0'):array('adPlayCount'=>$adPlayCount);

				//获得投放金额
				$sum = $this->db->select('price')->get('adPlayInfo')->result_array();
				$money = 0;
				for ($i=0; $i < count($sum); $i++) {
					$money+=$sum[$i]['price'];
				}
				$money = empty($money)?array('money'=>'0'):array('money'=>$money);
				$info = array_merge($userCount,$adCount,$adPlayCount,$money);
				break;
			case '2':
				$info = $this->getAboutTimeData($type);
				break;
			case '3':
				$info = $this->getAboutTimeData($type);
				break;
			case '4':
				$info = $this->getAboutTimeData($type);
				break;
			case '5':
				$info = $this->getAboutTimeData($type);
				break;
		}
		return $res = $this->pubfunction->pub_json('0',$info);
	}

	/**
	 * @Desc：根据界面选择时间不同 获取对应区间数据
	 * @param  type
	 * @return：array
	 */
	public function getAboutTimeData($type){
		$time = $this->pubfunction->timeType($type);
		//获得用户/企业数
		$userCount = $this->db->select('id')->where(array('status'=>'5','updateTime >'=>$time['start'],'updateTime <'=>$time['end']))->get('userRegisterInfo')->num_rows();
		$userCount = empty($userCount)?array('userCount'=>'0'):array('userCount'=>$userCount);
				
		//获得广告数
		$adCount = $this->db->select('id')->where(array('updateTime >'=>$time['start'],'updateTime <'=>$time['end']))->get('adtalkInfo')->num_rows();
		$adCount = empty($adCount)?array('adCount'=>'0'):array('adCount'=>$adCount);

		//获得投放广告数
		$adPlayCount = $this->db->select('id')->where(array('playTime >'=>$time['start'],'playTime <'=>$time['end']))->get('adPlayInfo')->num_rows();
		$adPlayCount = empty($adPlayCount)?array('adPlayCount'=>'0'):array('adPlayCount'=>$adPlayCount);
		
		//获得投放金额
		$sum = $this->db->select('price,playTime')->where(array('playTime >'=>$time['start'],'playTime <'=>$time['end']))->get('adPlayInfo')->result_array();
		$money = 0;
		for ($i=0; $i < count($sum); $i++) {
			$money+=$sum[$i]['price'];
		}
		$money = empty($money)?array('money'=>'0'):array('money'=>$money);
		return $info = array_merge($userCount,$adCount,$adPlayCount,$money);
	}

	/**
	 * @Desc：获得城市排名
	*/
	public function getAllCityRanking(){
		//获得投放广告最高的城市
		$city = $this->db->select("count(id) AS rank,city")->order_by("rank","desc")->group_by("city")->limit(10,0)->get("adPlayInfo")->result_array();
		$city = empty($city)?array('city'=>''):array('city'=>$city);

		//获得投放金额最高的城市
		$money = $this->db->select("sum(price) AS money,city")->order_by("money","desc")->group_by("city")->limit(10,0)->get("adPlayInfo")->result_array();
		$money = empty($money)?array('money'=>''):array('money'=>$money);
		$info = array_merge($city,$money);
		return $res = $this->pubfunction->pub_json('0',$info);
	}
}
?>