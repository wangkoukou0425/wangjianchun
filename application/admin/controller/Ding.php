<?php
namespace app\admin\controller;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\Controller;
use gmars\rbac\Rbac;
use Db;
use Request;



class Ding extends Common
{
    public function list()
    {
    	return $this->fetch();
    }
    
    public function test()
    {
    	$helper = new Sample();
    	if ($helper->isCli()) {
    	    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);
    	    return;
    	}
    	$spreadsheet = new Spreadsheet();
    	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
    	    ->setLastModifiedBy('Maarten Balliauw')
    	    ->setTitle('Office 2007 XLSX Test Document')
    	    ->setSubject('Office 2007 XLSX Test Document')
    	    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    	    ->setKeywords('office 2007 openxml php')
    	    ->setCategory('Test result file');
            $arr=Db::query("select * from `order`");
    	// Add some data
    	$spreadsheet->setActiveSheetIndex(0)
    	    ->setCellValue('A1', 'id')
    	    ->setCellValue('B2', 'name');
            foreach ($arr as $key => $value) {
               $i=$key+2;
               $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'id')
            ->setCellValue('B2', 'name');
            }
    	// Miscellaneous glyphs, UTF-8
    	// Rename worksheet
    	$spreadsheet->getActiveSheet()->setTitle('Simple');


    	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    	$spreadsheet->setActiveSheetIndex(0);


    	// Redirect output to a client’s web browser (Ods)
    	header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
    	header('Content-Disposition: attachment;filename="01simple.xlsx"');
    	header('Cache-Control: max-age=0');
    	// If you're serving to IE 9, then the following may be needed
    	header('Cache-Control: max-age=1');


    	// If you're serving to IE over SSL, then the following may be needed
    	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    	header('Pragma: public'); // HTTP/1.0


    	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    	$writer->save('php://output');
    	exit;
   	}

    public function add(){
        
        $data=Request::post();
        // $goods_id=$data['goods_id'];
        $file = request()->file('wenjian');
        $info = $file->move( 'static/xlsx');
         $path=str_replace("\\","/",$info->getSaveName());
        $inputFileName =  'static/xlsx/'.$path;
         $helper = new Sample();
        $helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory to identify the format');
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        var_dump($sheetData);
        foreach ($sheetData as $key => $value) {
            $name=$value['B'];
        }

    }






}
