<?php

namespace sh\cxselect;

use yii\web\AssetBundle;

/**
 * Description of AnimateAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 2.5
 */
class CxselectAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@sh/cxselect/js';
    /**
     * @inheritdoc
     */
    public $js = [
        'jquery.cxselect.min.js',
    ];

}
