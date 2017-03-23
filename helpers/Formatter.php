<?php

namespace asb\yii2\modules\contactform_3_170124\helpers;

use yii\i18n\Formatter as BaseFormatter;
use yii\helpers\Html;

/**
 * @author ASB <ab2014box@gmail.com>
 */
class Formatter extends BaseFormatter
{
    use UserFormatterTrait;

    const CONTINUED = '...';

    /**
     * Formats the value as an HTML-encoded plain text with newlines converted into breaks.
     * @param string $value the value to be formatted.
     * @param integer $maxLen max lenght of text.
     * @return string the formatted result.
     * @ see asNtext()
     */    
    public function asNtextCut($value, $maxLen)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        if (mb_strlen($value) > $maxLen) {
            $value = mb_substr($value, 0, $maxLen) . self::CONTINUED;
        }
        
        return nl2br(Html::encode($value));
    }

}
