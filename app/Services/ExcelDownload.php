<?php

namespace App\Services;

use Vtiful\Kernel\Format;

/**
 * 通过安装的 mac 安装 php扩展 xlswriter-1.3.6
 *
 * http://pecl.php.net/package/xlswriter/1.3.6 扩展下载
 *
 * xlswriter 文档 https://xlswriter-docs.viest.me/zh-cn/download
 * Class ExcelDownload
 * @package App\Services
 */
class ExcelDownload{

    public function download($header, $data, $fileName)
    {
        $config     = [
            'path' => $this->getTmpDir() . '/',
        ];
        $now        = date('YmdHis');
        $fileName   = $fileName . $now . '.xlsx';
        $xlsxObject = new \Vtiful\Kernel\Excel($config);

        // Init File
        $fileObject = $xlsxObject->fileName($fileName);

        // 设置样式
        $fileHandle = $fileObject->getHandle();
        $format     = new \Vtiful\Kernel\Format($fileHandle);
        $style      = $format->bold()->background(
            \Vtiful\Kernel\Format::COLOR_GREEN
        )->align(Format::FORMAT_ALIGN_VERTICAL_CENTER)->toResource();

        // Writing data to a file ......
        $fileObject->header($header)
            ->data($data)
            ->freezePanes(1, 0)
            ->setRow('A1', 20, $style);

        // Outptu
        $filePath = $fileObject->output();


        // 下载
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        header('Content-Length: ' . filesize($filePath));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean();
        flush();
        if (copy($filePath, 'php://output') === false) {
            return false;
        }

        // Delete temporary file
        @unlink($filePath);

        return true;
    }

    /**
     * 获取临时文件夹
     * @return false|string
     */
    private function getTmpDir()
    {
        // 目录可以自定义
        // return \Yii::$app->params['downloadPath'];

        $tmp = ini_get('upload_tmp_dir');
        if ($tmp !== False && file_exists($tmp)) {
            return realpath($tmp);
        }
        return realpath(sys_get_temp_dir());
    }

    /**
     * 读取文件
     * @param $path
     * @param $fileName
     * @return array
     */
    public function readFile($path,$fileName)
    {
        // 读取测试文件
        $config = ['path' => $path];
        $excel  = new \Vtiful\Kernel\Excel($config);
        $data   = $excel->openFile($fileName)
            ->openSheet()
            ->getSheetData();
        return $data;
    }

}
