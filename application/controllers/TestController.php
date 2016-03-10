<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 支持XxxController的类名
 *
 * @author Dave Xie <hhxsv5@sina.com>
 */
class TestController extends CI_Controller
{

    public function __construct()
    {
        if (ENVIRONMENT === 'production')
            show_404();
        parent::__construct();
    }

    public function index()
    {
        echo __METHOD__;
    }

    public function actionTest()
    {
        echo __METHOD__;
    }

    public function export()
    {
        $this->load->library('PHPExcel/PHPExcel', NULL, 'phpexcel');
        
        // Create new PHPExcel object
        // $objPHPExcel = new PHPExcel();
        $objPHPExcel = $this->phpexcel;
        
        // Set document properties
        $objPHPExcel->getProperties()
            ->setCreator('Dave')
            ->setLastModifiedBy('Dave')
            ->setTitle('Some title')
            ->setSubject('Some subjecct')
            ->setDescription('Some description')
            ->setKeywords('Some keywords')
            ->setCategory('CategoryA');
        
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '订单号')
            ->setCellValue('C1', '姓名')
            ->setCellValue('D1', '联系方式')
            ->setCellValue('E1', '总价')
            ->setCellValue('F1', '干洗')
            ->setCellValue('G1', '水洗')
            ->setCellValue('H1', '物流工号')
            ->setCellValue('I1', '下单时间')
            ->setCellValue('J1', '收账时间')
            ->setCellValue('I1', '备注');
        
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Sheet title');
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="test.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit();
    }
}