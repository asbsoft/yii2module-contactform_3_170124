<?php

namespace asb\yii2\modules\contactform_3_170124;

use asb\yii2\common_2_170212\base\SeparatedModule;

/**
 * @author ASB <ab2014box@gmail.com>
 */
class FrontendModule extends SeparatedModule
{
    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->_type = static::MODULE_FRONTEND;
        parent::__construct($id, $parent, $config);
    }
}
