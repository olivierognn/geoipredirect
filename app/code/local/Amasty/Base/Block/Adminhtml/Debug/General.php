<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Magento 1 Base Package
*/

class Amasty_Base_Block_Adminhtml_Debug_General extends Amasty_Base_Block_Adminhtml_Debug_Base
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/ambase/debug/general.phtml');
    }

    public function getDisableModulesOutput()
    {
        $config = array();

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $tableName = $resource->getTableName('core/config_data');

        $query = "SELECT * FROM " . $tableName . " WHERE path LIKE '%advanced/modules_disable_output%' AND value = 1";

        $data = $readConnection->fetchAll($query);

        foreach ($data as $item) {
            $config[] = array(
                "name" => str_replace("advanced/modules_disable_output/", "", $item["path"])
            );
        }

        return $config;
    }

    public function isCompilationEnabled()
    {
        $ret = false;

        $configFile = BP . DS . 'includes' . DS . 'config.php';
        if (file_exists($configFile)) {
            $config = file_get_contents($configFile);
            $ret = strpos($config, "#define('COMPILER_INCLUDE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'src')") === FALSE;
        }

        return $ret;
    }

    public function getCrontabConfig()
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $tableName = $resource->getTableName('cron/schedule');

        $query = "SELECT * FROM " . $tableName . "  order by schedule_id desc limit 5";

        $data = $readConnection->fetchAll($query);

        return $data;
    }

}
