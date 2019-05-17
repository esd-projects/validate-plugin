<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/17
 * Time: 11:36
 */

namespace ESD\Plugins\Validate;


use ESD\BaseServer\Server\Context;
use ESD\BaseServer\Server\PlugIn\AbstractPlugin;
use ESD\Plugins\AnnotationsScan\AnnotationsScanPlugin;

class ValidatePlugin extends AbstractPlugin
{

    public function __construct()
    {
        parent::__construct();
        $this->atAfter(AnnotationsScanPlugin::class);
    }

    /**
     * 获取插件名字
     * @return string
     */
    public function getName(): string
    {
        return "Validate";
    }

    /**
     * 初始化
     * @param Context $context
     * @return mixed
     */
    public function beforeServerStart(Context $context)
    {

    }

    /**
     * 在进程启动前
     * @param Context $context
     * @return mixed
     */
    public function beforeProcessStart(Context $context)
    {
        $this->ready();
    }
}