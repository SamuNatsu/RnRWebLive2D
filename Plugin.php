<?php

/**
 * Rainiar's Toolkit for Typecho - Web Live2D
 * 
 * @package RnRWebLive2D
 * @author Rainiar
 * @version 1.3.1
 * @link https://rainiar.top
 */
class RnRWebLive2D_Plugin implements Typecho_Plugin_Interface {
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array('RnRWebLive2D_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('RnRWebLive2D_Plugin', 'footer');
    }

    public static function header() {
        $url = Helper::options()->pluginUrl;
?>
<link rel="stylesheet" href="<?php echo $url; ?>/RnRWebLive2D/waifu.min.css">
<script src="<?php echo $url; ?>/RnRWebLive2D/waifu-tips.min.js"></script>
<script src="<?php echo $url; ?>/RnRWebLive2D/live2d.min.js"></script>
<?php
    }

    public static function footer() {
        $settings = Helper::options()->plugin('RnRWebLive2D');
?>
<div class="waifu">
    <div class="waifu-tips"></div>
    <canvas id="live2d" class="live2d"></canvas>
    <div class="waifu-tool">
        <span class="fui-home"></span>
        <span class="fui-chat"></span>
        <span class="fui-eye"></span>
        <span class="fui-user"></span>
        <span class="fui-photo"></span>
        <span class="fui-info-circle"></span>
        <span class="fui-cross"></span>
    </div>
</div>
<script defer>
    live2d_settings["modelAPI"] = "<?php echo $settings->apiURL; ?>";
    live2d_settings["hitokotoAPI"] = "<?php echo $settings->htktAPI; ?>";
    live2d_settings["modelId"] = <?php echo $settings->mdID; ?>;
    live2d_settings["modelTexturesId"] = <?php echo $settings->txID; ?>;
    live2d_settings["modelStorage"] = <?php echo $settings->mdSt == 1 ? 'true' : 'false'; ?>;
    live2d_settings["modelRandMode"] = "<?php echo $settings->mdSw == 1 ? 'rand' : 'switch'; ?>";
    live2d_settings["modelTexturesRandMode"] = "<?php echo $settings->txSw == 1 ? 'rand' : 'switch'; ?>";
    live2d_settings["homePageUrl"] = "<?php echo Helper::options()->rootUrl; ?>";
    if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)) {
        console.log("[RnRWebLive2D] Disable on mobile devices.");
        $(".waifu").hide();
    }
    else 
        initModel("<?php echo Helper::options()->pluginUrl; ?>/RnRWebLive2D/waifu-tips.json");
</script>
<?php
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate() {}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form) {
        $apiURL = new Typecho_Widget_Helper_Form_Element_Text('apiURL', NULL, NULL, _t('Live2D后端API地址'), _t('我们默认不提供此项，你可以去看看 → https://www.fghrsh.net/post/123.html<br/>该插件使用的后端API是源自这位大大的，你可以自己在你的网站搭建API（推荐放在根目录）'));
        $form->addInput($apiURL);
        
        $htktAPI = new Typecho_Widget_Helper_Form_Element_Text('htktAPI', NULL, _t('hitokoto.cn'), _t('一言API地址'), _t('默认值为：hitokoto.cn'));
        $form->addInput($htktAPI);
        
        $mdID = new Typecho_Widget_Helper_Form_Element_Text('mdID', NULL, _t('1'), _t('默认模型ID'), NULL);
        $form->addInput($mdID);
        
        $txID = new Typecho_Widget_Helper_Form_Element_Text('txID', NULL, _t('1'), _t('默认材质ID'), NULL);
        $form->addInput($txID);
        
        $mdSt = new Typecho_Widget_Helper_Form_Element_Radio('mdSt', array('1' => _t('是'), '0' => _t('否')) , '0', _t('记住模型材质') , _t('决定用户在主界面上切换模型后是否会被记住，默认为关闭'));
        $form->addInput($mdSt);
        
        $mdSw = new Typecho_Widget_Helper_Form_Element_Radio('mdSw', array('1' => _t('随机'), '0' => _t('顺序')) , '0', _t('模型切换规则') , _t('决定用户在主界面上切换模型的方式，默认为顺序'));
        $form->addInput($mdSw);
        
        $txSw = new Typecho_Widget_Helper_Form_Element_Radio('txSw', array('1' => _t('随机'), '0' => _t('顺序')) , '0', _t('材质切换规则') , _t('决定用户在主界面上切换材质的方式，默认为顺序'));
        $form->addInput($txSw);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form) {}
}
