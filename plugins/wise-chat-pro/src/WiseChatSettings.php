<?php

/**
 * WiseChat admin settings page.
 *
 * @author Kainex <contact@kaine.pl>
 */
class WiseChatSettings {
	const OPTIONS_GROUP = 'wise_chat_options_group';
	const MENU_SLUG = 'wise-chat-admin';
	
	const PAGE_TITLE = 'Settings Admin';
	const MENU_TITLE = 'Wise Chat Pro Settings';
	const COOKIE_MESSAGE = 'wc_messages_update_'.COOKIEHASH;
	const COOKIE_MESSAGE_ERROR = 'wc_messages_error_'.COOKIEHASH;
	
	const SECTION_FIELD_KEY = '_section';
	
	/**
	* @var array Tabs definition
	*/
	private $tabs = array(
		'wise-chat-general' => 'General',
		'wise-chat-externalLogin' => 'External Login',
		'wise-chat-messages' => 'Messages Posting',
		'wise-chat-moderation' => 'Moderation',
		'wise-chat-permissions' => 'Permissions',
		'wise-chat-appearance' => 'Appearance',
		'wise-chat-emoticons' => 'Emoticons',
		'wise-chat-channels' => 'Channels',
		'wise-chat-notifications' => 'Notifications',
		'wise-chat-filters' => 'Filters',
		'wise-chat-bans' => 'Bans',
		'wise-chat-kicks' => 'Kicks',
		'wise-chat-localization' => 'Localization',
		'wise-chat-advanced' => 'Advanced',
	);
	
	/**
	* @var array Generated sections
	*/
	private $sections = array();
	
	public function __construct() {
		WiseChatContainer::load('admin/WiseChatAbstractTab');
	}
	
	/**
	* Initializes settings page link in admin menu.
	*
	* @return null
	*/
	public function initialize() {
		add_action('admin_menu', array($this, 'addAdminMenu'));
		add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
		add_action('admin_init', array($this, 'pageInit'));
		if (array_key_exists('showDocs', $_GET)) {
			add_filter('admin_init', array($this, 'showDocs'));
		}
	}
	
	public function addAdminMenu() {
		add_options_page(self::PAGE_TITLE, self::MENU_TITLE, 'manage_options', self::MENU_SLUG, array($this, 'renderAdminPage'));
		$this->handleActions();
	}
	
	public function enqueueScripts() {
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wc-admin-script', plugins_url('../js/wise_chat_admin.js', __FILE__), array('wp-color-picker'), false, true);
		wp_localize_script('wc-admin-script', 'wcAdminConfig', array('ajaxurl' => admin_url('admin-ajax.php')));
	}
	
	public function pageInit() {
		register_setting(self::OPTIONS_GROUP, WiseChatOptions::OPTIONS_NAME, array($this, 'getSanitizedFormValues'));

		foreach ($this->tabs as $key => $caption) {
			$sectionKey = "section_{$key}";
			$tabObject = $this->getTabObject($key);
			
			$fields = $tabObject->getFields();
			foreach ($fields as $field) {
				$id = $field[0];
				$name = $field[1];
				
				if ($id === self::SECTION_FIELD_KEY) {
					$sectionKey = "section_{$key}_".md5($name);
					add_settings_section($sectionKey, $name, null, $key);
					$this->sections[$key][] = array(
						'id' => $sectionKey,
						'name' => $name,
						'hint' => array_key_exists(2, $field) ? $field[2] : '',
						'options' => array_key_exists(3, $field) ? $field[3] : array()
					);
				} else {
					$args = array(
						'id' => $id,
						'name' => $name,
						'hint' => array_key_exists(4, $field) ? $field[4] : '',
						'options' => array_key_exists(5, $field) ? $field[5] : array()
					);
				
					add_settings_field($id, $name, array($tabObject, $field[2]), $key, $sectionKey, $args);
				}
			}
		}
	}

	/**
	* Sets the default values of all configuration fields.
	* It should be used right after the activation of the plugin.
	*
	* @return null
	*/
	public function setDefaultSettings() {
		$options = get_option(WiseChatOptions::OPTIONS_NAME, array());
		
		foreach ($this->tabs as $key => $caption) {
			$tabObject = $this->getTabObject($key);
			foreach ($tabObject->getDefaultValues() as $key => $value) {
				if (!array_key_exists($key, $options)) {
					$options[$key] = $value;
				}
			}
		}
		update_option(WiseChatOptions::OPTIONS_NAME, $options);
	}

	public function renderAdminPage() {
		?>
			<div class="wrap">
				<style type="text/css">
					.wcAdminFl { float: left; }
					.wcAdminFr { float: right; }
					.wcAdminCb { clear:both; }
					.wcAdminMenu, .wcAdminMenu * { -moz-box-sizing: border-box; box-sizing: border-box; }
					.wcAdminTabContainer { overflow:hidden; }
					.wcAdminMenu *:focus { outline: none; box-shadow: none; }
					.wcAdminMenu { width: 200px; margin-right: 10px; border: 1px solid #e5e5e5; -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04); box-shadow: 0 1px 1px rgba(0,0,0,.04); }
					.wcAdminMenu ul { margin: 0px; list-style: none; padding: 0px; }
					.wcAdminMenu ul li { border-bottom: 1px solid #dfdfdf; margin: 0; display: list-item; text-align: -webkit-match-parent; }
					.wcAdminMenu ul li a { background-color: #fff; display: inline-block; padding: 10px 20px; width: 100%; font-size: 1.1em; text-decoration: none; color: #000; }
					.wcAdminMenu ul li a:hover { background-color: #fafafa; color: #000; outline: 0;}
					.wcAdminMenu ul li a:visited { color: #000; }
					.wcAdminMenu ul li a.wcAdminMenuActive { font-weight: bold; background-color: #fafafa; }
					.wcUserSearchLayer { border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff; position: absolute; max-width: 300px; max-height: 200px; overflow-y: auto; z-index: 2;}
					.wcUserSearchLayer a { display: block; margin: 6px; text-decoration: none; }
					.wcCondensedTable th, .wcCondensedTable td { padding: 4px; width: auto; }
				</style>
			
				<h2><?php echo self::MENU_TITLE ?></h2>
				
				<form method="post" action="options.php" class="metabox-holder">
					<!-- Disabling autocomplete: -->
					<input type="text" style="display: none" />
					<input type="password" style="display: none" />

					<?php settings_fields(self::OPTIONS_GROUP); ?>
					
					<?php $this->renderMenu(); ?>
					
					<?php
						$isFirstContainer = true;
						foreach ($this->tabs as $pageId => $tabCaption) {
							$hideContainer = $isFirstContainer ? '' : 'display:none';
							echo "<div id='{$pageId}Container' class='wcAdminTabContainer' style='{$hideContainer}'>";
							
							$sections = $this->sections[$pageId];
							foreach ($sections as $sectionKey => $section) {
								echo "<div class='postbox'>";
								echo "<h3 class='hndle'><span>".$section['name']."</span></h3>";
								echo "<div class='inside'>";
								echo '<table class="form-table">';
								if (strlen($section['hint']) > 0) {
									echo '<tr><td colspan="2" style="padding:0"><p class="description">'.$section['hint'].'</p></td></tr>';
								}
								do_settings_fields($pageId, $section['id']);
								echo '<tr><td colspan="2">';
								if (!array_key_exists('hideSubmitButton', $section['options']) || $section['options']['hideSubmitButton'] !== true) {
									submit_button('', 'primary large', 'submit', false, array('id' => "submit_{$pageId}_{$sectionKey}", 'onclick' => 'wise_chat_append_tab(\'' . str_replace('wise-chat-', '', $pageId) . '\')'));
								}
								echo '</td></tr>';
								echo '</table>';
								echo "</div></div>";
							}
							
							echo "</div>";
							$isFirstContainer = false;
						}
					?>
					
					<br class="wcAdminCb" />
				</form>
				
				<script type="text/javascript">
					function wise_chat_append_tab(tab) {
						var referrer = jQuery('input[name = "_wp_http_referer"]');
						referrer.val(referrer.val() + '#tab=' + tab);
					}

					jQuery(window).load(function() {
						jQuery('.wcAdminMenu a').click(function() {
							jQuery('.wcAdminTabContainer').hide();
							jQuery('#' + jQuery(this).attr('id') + 'Container').show();
							jQuery('.wcAdminMenu a').removeClass('wcAdminMenuActive');
							jQuery(this).addClass('wcAdminMenuActive');
						});

						if (location && location.hash && location.hash.length > 0) {
							var matches = location.hash.match(new RegExp('tab=([^&]*)'));
							if (matches) {
								var tab = matches[1];
								jQuery('.wcAdminTabContainer').hide();
								jQuery('#wise-chat-' + tab + 'Container').show();
								jQuery('.wcAdminMenu a').removeClass('wcAdminMenuActive');
								jQuery('#wise-chat-' + tab).addClass('wcAdminMenuActive');
							}
						}
					});
				</script>
			</div>
		<?php
	}
	
	private function renderMenu() {
		$outHtml = '';
		
		$outHtml .= '<div class="wcAdminMenu wcAdminFl">';
		$outHtml .= '<ul>';
		$isFirstTab = true;
		foreach ($this->tabs as $key => $caption) {
			$isActive = $isFirstTab ? 'wcAdminMenuActive' : '';
			$outHtml .= '<li><a id="'.$key.'" class="'.$isActive.'" href="javascript://">'.$caption.'</a></li>';
			$isFirstTab = false;
		}
		$outHtml .= '</ul>';
		$outHtml .= '</div>';
		
		echo $outHtml;
	}
	
	/**
	* Detects actions passed in parameters and delegates to an action method.
	*
	* @return null
	*/
	public function handleActions() {
		global $wpdb;
		
		if (isset($_GET['wc_action'])) {
			foreach ($this->tabs as $tabKey => $tabCaption) {
				$tabObject = $this->getTabObject($tabKey);
				$actionMethod = $_GET['wc_action'].'Action';
				if (method_exists($tabObject, $actionMethod)) {
					$tabObject->$actionMethod();
				}
			}
			
			$redirURL = admin_url("options-general.php?page=".self::MENU_SLUG).(isset($_GET['tab']) ? '#wc_tab='.urlencode($_GET['tab']) : '');
			echo '<script type="text/javascript">location.replace("' . $redirURL . '");</script>';
		} else {
			$this->showUpdatedMessage();
			$this->showErrorMessage();
		}
	}
	
	/**
	* Filters form input using filters from each tab object.
	*
	* @param array $input A key-value list of form values
	*
	* @return array Filtered array
	*/
	public function getSanitizedFormValues($input) {
		$sanitized = array();
		foreach ($this->tabs as $tabKey => $tabCaption) {
			$sanitized = array_merge($sanitized, $this->getTabObject($tabKey)->sanitizeOptionValue($input));
		}
		$sanitized = array_merge(get_option(WiseChatOptions::OPTIONS_NAME, array()), $sanitized);
		
		return $sanitized;
	}
	
	
	/**
	* Returns an instance of the requested tab object.
	*
	* @param string $tabKey A key from $this->tabs array
	*
	* @return WiseChatAbstractTab
	*/
	private function getTabObject($tabKey) {
		$tabKey = ucfirst(str_replace('wise-chat-', '', $tabKey));
		$classPathAndName = "admin/WiseChat{$tabKey}Tab";
		
		return WiseChatContainer::get($classPathAndName);
	}

	/**
	 * Shows a message stored in the transient.
	 */
	private function showUpdatedMessage() {
		$message = get_transient('wc_admin_settings_message');
		if (is_string($message) && strlen($message) > 0) {
			add_settings_error(md5($message), esc_attr('settings_updated'), strip_tags($message), 'updated');
			delete_transient('wc_admin_settings_message');
		}
	}

	/**
	 * Shows a message stored in the transient.
	 */
	private function showErrorMessage() {
		$message = get_transient('wc_admin_settings_error_message');
		if (is_string($message) && strlen($message) > 0) {
			add_settings_error(md5($message), esc_attr('settings_updated'), strip_tags($message), 'error');
			delete_transient('wc_admin_settings_error_message');
		}
	}

	/**
	 * Shows the documentation of all shortcode attributes.
	 */
	public function showDocs() {
		$excludedFields = array(
			'user_actions', 'enable_opening_control', 'opening_days', 'opening_hours',
			'bans', 'ban_add', 'channels', 'admin_actions', 'filters', 'filter_add',
			'kicks', 'kick_add', 'custom_emoticons'
		);
		foreach ($this->tabs as $key => $caption) {
			$tabObject = $this->getTabObject($key);
			$fields = $tabObject->getFields();
			$defaults = $tabObject->getDefaultValues();
			$printSection = null;
			foreach ($fields as $field) {
				$id = $field[0];
				$name = str_replace('<br />', ' ', $field[1]);
				$callback = $field[2];
				$type = $field[3];
				$hint = $field[4];
				$values = $field[5];

				if (in_array($id, $excludedFields)) {
					continue;
				}

				if ($id == '_section') {
					$printSection = "<h4>{$caption}: $name</h4>";
					continue;
				} else {
					if ($printSection !== null) {
						echo $printSection;
						$printSection = null;
					}
					$default = $defaults[$id];
					$defaultLabel = ($default !== '' && $default !== null ? $default : '[not set]');

					$allowedValue = 'a text';
					if ($callback == 'booleanFieldCallback') {
						$allowedValue = '<ul><li><i>0</i> - disabled</li><li><i>1</i> - enabled</li></ul>';
					} else if ($type == 'integer') {
						$allowedValue = '<i>a positive number</i>';
					} else if ($callback == 'colorFieldCallback') {
						$allowedValue = '<i>a color in hex format, eg. #ef2244</i>';
					} else if (is_array($values)) {
						$allowedValues = array();
						foreach ($values as $key => $value) {
							if ($key === '') {
								$key = '[not set]';
							}
							if ($id == 'permission_delete_message_role' || $id == 'permission_ban_user_role') {
								$value = ucfirst($key);
							}
							$allowedValues[] = "<li><i>$key</i> - {$value}</li>";
						}
						$allowedValue = '<ul>'.implode('', $allowedValues).'</ul>';
					}

					if ($id == 'chat_width' || $id == 'chat_height') {
						$allowedValue = null;
					}

					echo "<h5>$id - $name</h5>\n";
					echo "$hint\n";
					if ($allowedValue !== null) {
						echo "Allowed values: {$allowedValue}\n";
					}
					echo "Default: <i>".$defaultLabel."</i>\n\n";
				}
			}
		}

		die();
	}

	public function userSearchEndpoint() {
		$searchTerm = $_POST['keyword'];
		$out = array('type' => 'success', 'users' => array());

		$args = array (
			'order' => 'ASC',
			'orderby' => 'display_name',
			'search' => '*'.esc_attr($searchTerm).'*',
			'number' => 5,
			'search_columns' => array(
				'user_login',
				'user_nicename',
				'user_email',
				'user_url',
				'display_name',
			)
		);
		$query = new WP_User_Query($args);
		$users = $query->get_results();

		if (!empty($users)) {
			foreach ($users as $user) {
				$out['users'][] = array(
					'login' => $user->user_login,
					'text' => $user->user_login.' ('.$user->display_name.')'
				);
			}
		}

		echo json_encode($out);
		die();
	}
}