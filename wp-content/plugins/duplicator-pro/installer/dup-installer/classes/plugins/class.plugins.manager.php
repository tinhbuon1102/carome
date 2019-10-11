<?php
/**
 * Original installer files manager
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX\U
 *
 */
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

require_once($GLOBALS['DUPX_INIT'].'/classes/plugins/class.plugin.item.php');
require_once($GLOBALS['DUPX_INIT'].'/classes/plugins/class.plugin.custom.actions.php');
require_once($GLOBALS['DUPX_INIT'].'/classes/utilities/class.u.remove.redundant.data.php');

/**
 * Original installer files manager
 * singleton class
 */
final class DUPX_Plugins_Manager
{

    const SLUG_SIMPLE_SSL            = 'really-simple-ssl/rlrsssl-really-simple-ssl.php';
    const SLUG_WP_FORCE_SSL          = 'wp-force-ssl/wp-force-ssl.php';
    const SLUG_RECAPTCHA             = 'simple-google-recaptcha/simple-google-recaptcha.php';
    const SLUG_WPBAKERY_PAGE_BUILDER = 'js_composer/js_composer.php';
    const SLUG_DUPLICATOR_PRO        = 'duplicator-pro/duplicator-pro.php';
    const SLUG_POPUP_MAKER           = 'popup-maker/popup-maker.php';
    const OPTION_ACTIVATE_PLUGINS    = 'duplicator_pro_activate_plugins_after_installation';

    /**
     * 
     * @var DUPX_Plugins_Manager
     */
    private static $instance = null;

    /**
     * 
     * @var DUPX_Plugin_item[]
     */
    private $plugins = array();

    /**
     *
     * @var DUPX_Plugin_item[]
     */
    private $pluginsToActivate = array();

    /**
     *
     * @var array 
     */
    private $pluginsAutoDeactivate = array();

    /**
     *
     * @var DUPX_Plugin_item[] 
     */
    private $unistallList = array();

    /**
     *
     * @var DUPX_Plugin_custom_actions[]
     */
    private $customPluginsActions = array();

    /**
     *
     * @return DUPX_Orig_File_Manager
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {

        foreach ($GLOBALS['DUPX_AC']->wpInfo->plugins as $pluginInfo) {
            $this->plugins[$pluginInfo->slug] = new DUPX_Plugin_item((array) $pluginInfo);
        }

        $this->setCustomPluginsActions();

        DUPX_Log::info('CONTRUCT PLUGINS OBJECTS: '.DUPX_log::varToString($this->plugins), DUPX_Log::LV_HARD_DEBUG);
    }

    private function setCustomPluginsActions()
    {
        $this->customPluginsActions[self::SLUG_DUPLICATOR_PRO] = new DUPX_Plugin_custom_actions(self::SLUG_DUPLICATOR_PRO, DUPX_Plugin_custom_actions::BY_DEFAULT_ENABLED, true, null);

        $longMsg = "It is deactivated by default  automatically. <strong>You must reactivate from the WordPress admin panel after completing the installation</strong> "
            ."or select the plugin on plugins tab.<br>"
            ." After activating it, your site's frontend will look correct.";

        $this->customPluginsActions[self::SLUG_WPBAKERY_PAGE_BUILDER] = new DUPX_Plugin_custom_actions(self::SLUG_WPBAKERY_PAGE_BUILDER, DUPX_Plugin_custom_actions::BY_DEFAULT_DISABLED, true, $longMsg);

        $longMsg = "This plugin is deactivated by default automatically due to issues that one may encounter when migrating. "
            ."<strong>You must reactivate from the WordPress admin panel after completing the installation</strong> "
            ."or select the plugin on plugins tab.<br>"
            ."After activation, Your site's frontend will display properly.";

        $this->customPluginsActions[self::SLUG_POPUP_MAKER] = new DUPX_Plugin_custom_actions(self::SLUG_POPUP_MAKER, DUPX_Plugin_custom_actions::BY_DEFAULT_DISABLED, true, $longMsg);
    }

    /**
     * 
     * @return DUPX_Plugin_item[]
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * This function performs status checks on plugins and disables those that must disable creating user messages
     */
    public function preViewChecks()
    {
        $noticeManager = DUPX_NOTICE_MANAGER::getInstance();
        $paramsManager = DUPX_Paramas_Manager::getInstance();
        $activePlugins = $paramsManager->getValue(DUPX_Paramas_Manager::PARAM_PLUGINS);
        $saveParams    = false;

        foreach ($this->customPluginsActions as $slug => $customPlugin) {
            if (!isset($this->plugins[$slug])) {
                continue;
            }

            switch ($customPlugin->byDefaultStatus()) {
                case DUPX_Plugin_custom_actions::BY_DEFAULT_DISABLED:
                    if (($delKey = array_search($slug, $activePlugins)) !== false) {
                        $saveParams = true;
                        unset($activePlugins[$delKey]);

                        $noticeManager->addNextStepNotice(array(
                            'shortMsg'    => 'Plugin '.$this->plugins[$slug]->name.' disabled by default',
                            'level'       => DUPX_NOTICE_ITEM::NOTICE,
                            'longMsg'     => $customPlugin->byDefaultMessage(),
                            'longMsgMode' => DUPX_NOTICE_ITEM::MSG_MODE_HTML,
                            'sections'    => 'plugins'
                            ), DUPX_NOTICE_MANAGER::ADD_UNIQUE, 'custom_plugin_action'.$slug);
                    }
                    break;
                case DUPX_Plugin_custom_actions::BY_DEFAULT_ENABLED:
                    if (!in_array($slug, $activePlugins)) {
                        $saveParams      = true;
                        $activePlugins[] = $slug;

                        $noticeManager->addNextStepNotice(array(
                            'shortMsg'    => 'Plugin '.$this->plugins[$slug]->name.' enabled by default',
                            'level'       => DUPX_NOTICE_ITEM::NOTICE,
                            'longMsg'     => $customPlugin->byDefaultMessage(),
                            'longMsgMode' => DUPX_NOTICE_ITEM::MSG_MODE_HTML,
                            'sections'    => 'plugins'
                            ), DUPX_NOTICE_MANAGER::ADD_UNIQUE, 'custom_plugin_action'.$slug);
                    }
                    break;
                case DUPX_Plugin_custom_actions::BY_DEFAULT_AUTO:
                default:
                    break;
            }
        }

        if ($saveParams) {
            $paramsManager->setValue(DUPX_Paramas_Manager::PARAM_PLUGINS, $activePlugins);
            $paramsManager->save();
            $noticeManager->saveNotices();
        }
    }

    public function getStatusCounts($subsiteId = -1)
    {
        $result = array(
            DUPX_Plugin_item::STATUS_MUST_USE       => 0,
            DUPX_Plugin_item::STATUS_DROP_INS       => 0,
            DUPX_Plugin_item::STATUS_NETWORK_ACTIVE => 0,
            DUPX_Plugin_item::STATUS_ACTIVE         => 0,
            DUPX_Plugin_item::STATUS_INACTIVE       => 0
        );

        foreach ($this->plugins as $plugin) {
            $result[$plugin->getOrgiStatus($subsiteId)] ++;
        }

        return $result;
    }

    /**
     * get installer wp content  path if exists or false id don't exists
     * 
     * @todo This function is temporary. wp-content can be dynamic in relation to define WP_CONTENT_DIR. 
     * 
     * 
     * @staticvar string $path
     * @return string
     */
    public static function getWpContentPath()
    {
        static $path = null;

        if (is_null($path)) {
            $path = false;

            $safePath = DupProSnapLibIOU::safePathUntrailingslashit($GLOBALS['DUPX_ROOT'].'/wp-content');
            if (!is_dir($safePath) || !is_readable($safePath)) {
                DUPX_Log::info('[PLUGINS MANAGER] the wp content path '.DUPX_Log::varToString($safePath).' don\'t exists os isn\' readable');
            } else {
                DUPX_Log::info('[PLUGINS MANAGER] the wp content path '.DUPX_Log::varToString($safePath).' exist', DUPX_Log::LV_DETAILED);
                $path = $safePath;
            }
        }
        return $path;
    }

    public function getDefaultActivePluginsList($subsiteId = -1)
    {
        $result         = array();
        $networkInstall = DUPX_ArchiveConfig::getInstance()->isNetworkInstall();
        foreach ($this->plugins as $plugin) {
            if ($networkInstall) {
                if ($plugin->isNetworkActive($subsiteId)) {
                    $result[] = $plugin->slug;
                }
            } else {
                if (!$plugin->isInactive($subsiteId)) {
                    $result[] = $plugin->slug;
                }
            }
        }
        return $result;
    }

    /**
     * get installer plugin path if exists or false id don't exists
     * @staticvar string $path
     * @return string
     */
    public static function getPluginsPath()
    {
        static $path = null;

        if (is_null($path)) {
            $path = false;

            if (($pPath = $GLOBALS['DUPX_AC']->getDefineValue('PLUGINDIR', false)) !== false) {
                $safePath = DupProSnapLibIOU::safePathUntrailingslashit($GLOBALS['DUPX_ROOT'].'/'.$pPath);
                if (!is_dir($safePath) || !is_readable($safePath)) {
                    DUPX_Log::info('[PLUGINS MANAGER] the plugins path '.DUPX_Log::varToString($safePath).' don\'t exists os isn\' readable');
                } else {
                    DUPX_Log::info('[PLUGINS MANAGER] the plugins path '.DUPX_Log::varToString($safePath).' exist', DUPX_Log::LV_DETAILED);
                    $path = $safePath;
                }
            } else {
                DUPX_Log::info('[PLUGINS MANAGER] define PLUGINDIR is false');
            }
        }
        return $path;
    }

    /**
     * get installer mu plugin path if exists or false id don't exists
     * @staticvar string $path
     * @return string
     */
    public static function getMuPluginsPath()
    {
        static $path = null;

        if (is_null($path)) {
            $path = false;

            if (($pPath = $GLOBALS['DUPX_AC']->getDefineValue('MUPLUGINDIR', false)) !== false) {
                $safePath = DupProSnapLibIOU::safePathUntrailingslashit($GLOBALS['DUPX_ROOT'].'/'.$pPath);
                if (!is_dir($safePath) || !is_readable($safePath)) {
                    DUPX_Log::info('[PLUGINS MANAGER] the mu plugins path '.DUPX_Log::varToString($safePath).' don\'t exists os isn\' readable');
                } else {
                    DUPX_Log::info('[PLUGINS MANAGER] the mu plugins path '.DUPX_Log::varToString($safePath).' exist', DUPX_Log::LV_DETAILED);
                    $path = $safePath;
                }
            } else {
                DUPX_Log::info('[PLUGINS MANAGER] define MUPLUGINDIR is false');
            }
        }
        return $path;
    }

    /**
     * return alla plugins slugs list
     * 
     * @return string[]
     */
    public function getAllPluginsSlugs()
    {
        return array_keys($this->plugins);
    }

    public function setActions($plugins, $subsiteId = -1)
    {
        DUPX_Log::info('FUNCTION ['.__FUNCTION__.']: plugins '.DUPX_log::varToString($plugins), DUPX_Log::LV_DEBUG);
        $networkInstall = DUPX_ArchiveConfig::getInstance()->isNetworkInstall();

        foreach ($this->plugins as $slug => $plugin) {
            $deactivate = false;

            if ($plugin->isForceDisabled()) {
                $deactivate = true;
            } else {
                if ($networkInstall) {
                    if (!$this->plugins[$slug]->isNetworkInactive() && !in_array($slug, $plugins)) {
                        $deactivate = true;
                    }
                } else {
                    if (!$this->plugins[$slug]->isInactive($subsiteId) && !in_array($slug, $plugins)) {
                        $deactivate = true;
                    }
                }
            }

            if ($deactivate) {
                $this->plugins[$slug]->setDeactivateAction($subsiteId, null, null, $networkInstall);
            }
        }

        foreach ($plugins as $slug) {
            if (isset($this->plugins[$slug])) {
                $this->plugins[$slug]->setActivationAction($subsiteId, $networkInstall);
            }
        }

        $this->setAutoActions($subsiteId);
        DUPX_NOTICE_MANAGER::getInstance()->saveNotices();
    }

    public function executeActions($dbh, $subsiteId = -1)
    {
        $activePluginsList          = array();
        $activateOnLoginPluginsList = array();
        $removeInactivePlugins      = DUPX_Paramas_Manager::getInstance()->getValue(DUPX_Paramas_Manager::PARAM_REMOVE_RENDUNDANT);
        $this->unistallList         = array();

        $noticeManager = DUPX_NOTICE_MANAGER::getInstance();
        $s3Funcs       = DUPX_S3_Funcs::getInstance();
        DUPX_Log::info('PLUGINS OBJECTS: '.DUPX_log::varToString($this->plugins), DUPX_Log::LV_HARD_DEBUG);

        foreach ($this->plugins as $plugin) {
            $deactivated = false;
            if ($plugin->deactivateAction) {
                $plugin->deactivate();
                // can't remove deactivate after login
                $deactivated = true;
            } else if ($s3Funcs->newSiteIsMultisite()) {
                if ($plugin->isNetworkActive()) {
                    $activePluginsList[$plugin->slug] = time();
                }
            } else {
                if ($plugin->isActive($subsiteId)) {
                    $activePluginsList[] = $plugin->slug;
                }
            }

            if ($plugin->activateAction) {
                $activateOnLoginPluginsList[] = $plugin->slug;
                $noticeManager->addFinalReportNotice(array(
                    'shortMsg' => $plugin->name.' will be activated at the first login',
                    'level'    => DUPX_NOTICE_ITEM::INFO,
                    'sections' => 'plugins'
                ));
            } else {
                // remove only if isn't activated
                if ($removeInactivePlugins && ($plugin->isInactive($subsiteId) || $deactivated)) {
                    $this->unistallList[] = $plugin;
                }
            }
        }

        // force duplicator pro activation
        if (!array_key_exists(self::SLUG_DUPLICATOR_PRO, $activePluginsList)) {
            if ($s3Funcs->newSiteIsMultisite()) {
                $activePluginsList[self::SLUG_DUPLICATOR_PRO] = time();
            } else {
                $activePluginsList[] = self::SLUG_DUPLICATOR_PRO;
            }
        }

        DUPX_Log::info('Active plugins: '.DUPX_log::varToString($activePluginsList), DUPX_Log::LV_DEBUG);

        $value = mysqli_real_escape_string($dbh, @serialize($activePluginsList));
        if ($s3Funcs->newSiteIsMultisite()) {
            $table = mysqli_real_escape_string($dbh, $GLOBALS['DUPX_AC']->wp_tableprefix).'sitemeta';
            $query = "UPDATE `".$table."` SET meta_value = '".$value."'  WHERE meta_key = 'active_sitewide_plugins'";
        } else {

            $table = mysqli_real_escape_string($dbh, $GLOBALS['DUPX_AC']->wp_tableprefix)."options";
            $query = "UPDATE `".$table."` SET option_value = '".$value."'  WHERE option_name = 'active_plugins' ";
        }

        if (!DUPX_DB::mysqli_query($dbh, $query)) {
            $noticeManager->addFinalReportNotice(array(
                'shortMsg'    => 'QUERY ERROR: MySQL',
                'level'       => DUPX_NOTICE_ITEM::HARD_WARNING,
                'longMsg'     => "Error description: ".mysqli_error($dbh),
                'longMsgMode' => DUPX_NOTICE_ITEM::MSG_MODE_DEFAULT,
                'sections'    => 'database'
            ));
            throw new Exception("Database error description: ".mysqli_error($dbh));
        }

        $value = mysqli_real_escape_string($dbh, @serialize($activateOnLoginPluginsList));
        $table = mysqli_real_escape_string($dbh, $GLOBALS['DUPX_AC']->wp_tableprefix)."options";
        $query = "INSERT INTO `".$table."` (option_name, option_value) VALUES('".self::OPTION_ACTIVATE_PLUGINS."','".$value."') ON DUPLICATE KEY UPDATE option_name=\"".self::OPTION_ACTIVATE_PLUGINS."\"";
        if (!DUPX_DB::mysqli_query($dbh, $query)) {
            $noticeManager->addFinalReportNotice(array(
                'shortMsg'    => 'QUERY ERROR: MySQL',
                'level'       => DUPX_NOTICE_ITEM::HARD_WARNING,
                'longMsg'     => "Error description: ".mysqli_error($dbh),
                'longMsgMode' => DUPX_NOTICE_ITEM::MSG_MODE_DEFAULT,
                'sections'    => 'database'
            ));
            throw new Exception("Database error description: ".mysqli_error($dbh));
        }

        return true;
    }

    /**
     * remove inactive plugins
     * this method must calle after wp-config set
     * 
     */
    public function unistallInactivePlugins()
    {
        DUPX_Log::info('FUNCTION ['.__FUNCTION__.']: unistall inactive plugins');

        foreach ($this->unistallList as $plugin) {
            if ($plugin->uninstall()) {
                DUPX_Log::info("UNINSTALL PLUGIN ".DUPX_Log::varToString($plugin->slug).' DONE');
            } else {
                DUPX_Log::info("UNINSTALL PLUGIN ".DUPX_Log::varToString($plugin->slug).' FAILED');
            }
        }
    }

    /**
     * Get Automatic actions for plugins
     * 
     * @return array key as plugin slug and val as plugin title
     */
    public function setAutoActions($subsiteId = -1)
    {
        $this->pluginsAutoDeactivate = array();

        if (isset($this->plugins[self::SLUG_DUPLICATOR_PRO]) && $this->plugins[self::SLUG_DUPLICATOR_PRO]->isInactive($subsiteId)) {
            $this->plugins[self::SLUG_DUPLICATOR_PRO]->setActivationAction($subsiteId);
        }

        if (!DUPX_U::is_ssl() && isset($this->plugins[self::SLUG_SIMPLE_SSL]) && $this->plugins[self::SLUG_SIMPLE_SSL]->isActive($subsiteId)) {
            DUPX_Log::info('Really Simple SSL [as Non-SSL installation] will be deactivated', DUPX_Log::LV_DEBUG);
            $shortMsg = "Deactivated plugin: ".$this->plugins[self::SLUG_SIMPLE_SSL]->name;
            $longMsg  = "This plugin is deactivated because you are migrating from SSL (HTTPS) to Non-SSL (HTTP).<br>".
                "If it was not deactivated, you would not able to login.";

            $this->plugins[self::SLUG_SIMPLE_SSL]->setDeactivateAction($subsiteId, $shortMsg, $longMsg);
        }

        if (!DUPX_U::is_ssl() && isset($this->plugins[self::SLUG_WP_FORCE_SSL]) && $this->plugins[self::SLUG_WP_FORCE_SSL]->isActive($subsiteId)) {
            DUPX_Log::info('SLUG_WP_FORCE_SSL [as Non-SSL installation] will be deactivated', DUPX_Log::LV_DEBUG);
            $shortMsg = "Deactivated plugin: ".$this->plugins[self::SLUG_WP_FORCE_SSL]->name;
            $longMsg  = "This plugin is deactivated because you are migrating from SSL (HTTPS) to Non-SSL (HTTP).<br>".
                "If it was not deactivated, you would not able to login.";

            $this->plugins[self::SLUG_WP_FORCE_SSL]->setDeactivateAction($subsiteId, $shortMsg, $longMsg);
        }

        if ($GLOBALS['DUPX_AC']->url_old != DUPX_S3_Funcs::getInstance()->getPost('siteurl') && isset($this->plugins[self::SLUG_RECAPTCHA]) && $this->plugins[self::SLUG_RECAPTCHA]->isActive($subsiteId)) {
            DUPX_Log::info('Simple Google reCAPTCHA [as package creation site URL and the installation site URL are different] will be deactivated', DUPX_Log::LV_DEBUG);
            $shortMsg = "Deactivated plugin: ".$this->plugins[self::SLUG_RECAPTCHA]->name;
            $longMsg  = "It is deactivated because the Google Recaptcha required reCaptcha site key which is bound to the site's address.".
                "Your package site's address and installed site's address doesn't match. ".
                "You can reactivate it from the installed site login panel after completion of the installation.<br>".
                "<strong>Please do not forget to change the reCaptcha site key after activating it.</strong>";

            $this->plugins[self::SLUG_RECAPTCHA]->setDeactivateAction($subsiteId, $shortMsg, $longMsg);
        }

        foreach ($this->customPluginsActions as $slug => $customPlugin) {
            if (!isset($this->plugins[$slug])) {
                continue;
            }

            if ($customPlugin->isEnableAfterLogin()) {
                $this->plugins[$slug]->setActivationAction($subsiteId);
            }
        }

        /**
          if (isset($this->plugins[self::SLUG_WPBAKERY_PAGE_BUILDER]) && $this->plugins[self::SLUG_WPBAKERY_PAGE_BUILDER]->isActive($subsiteId)) {
          DUPX_Log::info('WPBakery Page Builder will be deactivated, If It is activated', DUPX_Log::LV_DEBUG);
          $shortMsg = "Deactivated plugin: ".$this->plugins[self::SLUG_WPBAKERY_PAGE_BUILDER]->name;
          $longMsg  = "It is deactivated automatically. <strong>You must reactivate from the WordPress admin panel after completing the installation.".
          "</strong> After activating it, your site's frontend will look correct.";

          $this->plugins[self::SLUG_WPBAKERY_PAGE_BUILDER]->setDeactivateAction($subsiteId, $shortMsg, $longMsg);
          $this->plugins[self::SLUG_WPBAKERY_PAGE_BUILDER]->setActivationAction($subsiteId);
          }

          if (isset($this->plugins[self::SLUG_POPUP_MAKER]) && $this->plugins[self::SLUG_POPUP_MAKER]->isActive($subsiteId)) {
          DUPX_Log::info('Popup Maker will be deactivated, if it is activated', DUPX_Log::LV_DEBUG);
          $shortMsg = "Deactivated plugin: ".$this->plugins[self::SLUG_POPUP_MAKER]->name;
          $longMsg  = "This plugin is deactivated automatically due to issues that one may encounter when migrating. "
          ."<strong>You must reactivate from the WordPress admin panel after completing the installation."
          ."</strong> After activation, Your site's frontend will display properly.";
          $this->plugins[self::SLUG_POPUP_MAKER]->setDeactivateAction($subsiteId, $shortMsg, $longMsg);
          $this->plugins[self::SLUG_POPUP_MAKER]->setActivationAction($subsiteId);
          }
         * 
         */
        DUPX_Log::info('Activated plugins listed here will be deactivated: '.DUPX_Log::varToString(array_keys($this->pluginsAutoDeactivate)));
    }

    private function __clone()
    {
        
    }

    private function __wakeup()
    {
        
    }
}