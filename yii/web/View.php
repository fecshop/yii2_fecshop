<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace fecshop\yii\web;

use yii\helpers\Html;

class View extends \yii\web\View
{
    /**
     * 第三方js数组
     */
    public $externalJs;
    
    public function registerExternalJs($jsScriptStr, $position = self::POS_HEAD, $key = null)
    {
        $key = $key ?: md5($jsScriptStr);
        $this->externalJs[$position][$key] = $jsScriptStr;
    }
    
    /**
     * Renders the content to be inserted in the head section.
     * The content is rendered using the registered meta tags, link tags, CSS/JS code blocks and files.
     * @return string the rendered content
     */
    protected function renderHeadHtml()
    {
        $lines = [];
        if (!empty($this->metaTags)) {
            $lines[] = implode("\n", $this->metaTags);
        }

        if (!empty($this->linkTags)) {
            $lines[] = implode("\n", $this->linkTags);
        }
        if (!empty($this->cssFiles)) {
            $lines[] = implode("\n", $this->cssFiles);
        }
        if (!empty($this->css)) {
            $lines[] = implode("\n", $this->css);
        }
        if (!empty($this->externalJs[self::POS_HEAD])) {
            $lines[] = implode("\n", $this->externalJs[self::POS_HEAD]);
        }
        if (!empty($this->jsFiles[self::POS_HEAD])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_HEAD]);
        }
        if (!empty($this->js[self::POS_HEAD])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_HEAD]));
        }
        
        return empty($lines) ? '' : implode("\n", $lines);
    }
    
    /**
     * Renders the content to be inserted at the beginning of the body section.
     * The content is rendered using the registered JS code blocks and files.
     * @return string the rendered content
     */
    protected function renderBodyBeginHtml()
    {
        $lines = [];
        
        if (!empty($this->externalJs[self::POS_BEGIN])) {
            $lines[] = implode("\n", $this->externalJs[self::POS_BEGIN]);
        }
        
        if (!empty($this->jsFiles[self::POS_BEGIN])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_BEGIN]);
        }
        if (!empty($this->js[self::POS_BEGIN])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_BEGIN]));
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }
    
    
    protected function renderBodyEndHtml($ajaxMode)
    {
        $lines = [];
        
        
        if (!empty($this->jsFiles[self::POS_END])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
        }
        if (!empty($this->externalJs[self::POS_END])) {
            $lines[] = implode("\n", $this->externalJs[self::POS_END]);
        }

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts));
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_END]));
            }
            if (!empty($this->js[self::POS_READY])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_READY]));
                
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_LOAD]));
                
            }
        }
        
        if (!empty($this->jsFiles['POS_READY'])) {
            $lines[] = implode("\n", $this->jsFiles['POS_READY']);
        }
        
        return empty($lines) ? '' : implode("\n", $lines);
    }
    
    public function registerJs($js, $position = self::POS_READY, $key = null)
    {
        $key = $key ?: md5($js);
        $this->js[$position][$key] = $js;
        //if ($position === self::POS_READY || $position === self::POS_LOAD) {
        //    JqueryAsset::register($this);
        //}
    }
}