<?php
class Export_excel extends CI_Controller{
	public function __construct(){
		//通过引用的方式赋给变量将使用原始的 CodeIgniter 对象
		$this->CI =& get_instance();
		$this->CI->load->library(array( 'PHPExcel','PHPExcel/IOFactory'));
	}
	/* 导出excel函数*/
    public function export($data,$name='Excel'){
        $objPHPExcel = new PHPExcel();
		$iofactory=new IOFactory();
		
		// //设置excel列名
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','编号');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','流水号');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','汇款识别码');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','充值金额');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','状态');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','充值时间');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','审核时间');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','企业名称');
		// //把数据循环写入excel中
		foreach($data as $key => $value){
			$key+=2;
		 	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$key,$value['index']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$key,$value['runCode']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$key,$value['remitIdentCode']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$key,$value['money']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$key,$this->checkStatus($value['rechargeStatus']));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$key,date('Y-m-d H:i:s',$value['createTime']));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$key,date('Y-m-d H:i:s',$value['updateTime']));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$key,$value['companyName']);
		}
		//导出代码
		$objPHPExcel-> setActiveSheetIndex(0);
		$objWriter = $iofactory -> createWriter($objPHPExcel, 'Excel2007');
		$filename = 'SetExcelName.xlsx';
		// 从浏览器直接输出$filename
	    $filename = 'SetExcelName.xlsx';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $name . '.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter -> save('php://output');
    }

    //判断充值状态
	function checkStatus($status){
		switch($status){
			case 0:
				return '待线下打款';
				break;
			case 1:
				return '待审核';
				break;
			case 2:
				return '充值成功';
				break;
			case 3:
				return '充值失败';
				break;
			case 4:
				return '取消充值';
				break;
			default:
	        	return '无';    
	        	break;
		}
	}
}
?>