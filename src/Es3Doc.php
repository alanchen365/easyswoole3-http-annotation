<?php

namespace Es3Doc;

use App\Constant\AppConst;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Command\Utility;
use Es3\EsConfig;

class Es3Doc
{
    use Singleton;

    /**
     * 生成文档
     */
    public function generator()
    {
        /** 不是生产环境 就生成文档 */
        if (!isProduction()) {

            /** 替换接口文档扫描目录 */
            $docPageTpl = EASYSWOOLE_ROOT . '/vendor/easyswoole/http-annotation/src/Annotation/docPage.tpl';
            file_put_contents($docPageTpl, str_replace('接口文档', AppConst::APP_NAME . '文档', file_get_contents($docPageTpl)));

            /** 替换扫描路径 */
            $easyDocBin = EASYSWOOLE_ROOT . '/vendor/easyswoole/http-annotation/bin/easy-doc';
            file_put_contents($easyDocBin, str_replace('HttpController', AppConst::ES_DIRECTORY_CONTROLLER_NAME, file_get_contents($easyDocBin)));
            file_put_contents($easyDocBin, str_replace('easyDoc.html', './Doc/document.html', file_get_contents($easyDocBin)));

            /** 替换 Parser.php */
            $parser = EASYSWOOLE_ROOT . '/vendor/easyswoole/http-annotation/src/Annotation/Parser.php';
            unlink($parser);
            copy('./vendor/alanchen365/easyswoole3-http-annotation/src/Parser.php', $parser);

            /** 删除老文档 */
            $docPath = EASYSWOOLE_ROOT . '/Doc/document.html';
            file_put_contents($docPath, 'document failure !');

            /** 开始生成文档 */
            $shell = 'php ' . EASYSWOOLE_ROOT . '/vendor/easyswoole/http-annotation/bin/easy-doc';
            exec($shell, $result);

            echo Utility::displayItem('doc', json_encode($result));
            echo "\n";
        }
    }
}
