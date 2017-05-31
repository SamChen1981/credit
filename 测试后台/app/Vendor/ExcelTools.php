<?php 
	include 'Phpexcel/PHPExcel.php';
	class ExcelTools{
		public function exportExcel($data=array(),$title=""){
			$objExcel = new PHPExcel();
			$objWriter = new PHPExcel_Writer_Excel5($objExcel);
			
			//缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0
			$objExcel->setActiveSheetIndex(0);
			$objActSheet = $objExcel->getActiveSheet();
			
			//设置单元格内容 由PHPExcel根据传入内容自动判断单元格内容类型
			$objActSheet->setCellValue('A1', 'ID');
			$objActSheet->setCellValue('B1', '用户名');
			$objActSheet->setCellValue('C1', '手机号码'); 
			$objActSheet->setCellValue('D1', '参与时间');
			$objActSheet->setCellValue('E1', '是否中奖');
			$objActSheet->setCellValue('F1', '奖品');
			$objActSheet->setCellValue('G1', '数量');
			
			$index = 2;
			foreach ($data as $key=>$log){
				$objActSheet->setCellValue('A'.$index, $log['id']);
				$objActSheet->setCellValue('B'.$index, $log['user_name']);
				$objActSheet->setCellValue('C'.$index, $log['user_mobile_phone']);
				$objActSheet->setCellValue('D'.$index, $log['create_time']);
				$objActSheet->setCellValue('E'.$index, $log['hit_status']);
				$objActSheet->setCellValue('F'.$index, $log['prizeName']);
				$objActSheet->setCellValue('G'.$index, $log['prizeSize']);
				
				$index++;
			}
			for ($i=1;$i<$index;$i++){
				$objActSheet->getStyle('A'.$i.':G'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				$objActSheet->getStyle('A'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			}
			$columns = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G');
			foreach ($columns as $key=>$columnName){
				$objActSheet->getColumnDimension($columnName)->setWidth(18);
			}
			//输出内容
			$outputFileName = $title.".xls";
			//到浏览器
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header('Content-Disposition:inline;filename="'.$outputFileName.'"');
			header("Content-Transfer-Encoding: binary");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Pragma: no-cache");
			$objWriter->save('php://output');
            die;
		}
	}
?>