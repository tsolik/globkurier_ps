<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminControllerCore extends Controller
{
	public $path;
	public static $currentIndex;
	public $content;
	public $warnings = array();
	public $informations = array();
	public $confirmations = array();
	public $shopShareDatas = false;
	
	public $_languages = array();
	public $default_form_language;
	public $allow_employee_form_lang;

	public $layout = 'layout.tpl';
	public $bootstrap = false ;

	protected $meta_title;

	public $template = 'content.tpl';

	/** @var string Associated table name */
	public $table = 'configuration';

	public $list_id;

	/** @var string Object identifier inside the associated table */
	protected $identifier = false;
	protected $identifier_name = 'name';

	/** @var string Tab name */
	public $className;

	/** @var array tabAccess */
	public $tabAccess;

	/** @var integer Tab id */
	public $id = -1;

	public $required_database = false;

	/** @var string Security token */
	public $token;

	/** @var string shop | group_shop */
	public $shopLinkType;

	/** @var string Default ORDER BY clause when $_orderBy is not defined */
	protected $_defaultOrderBy = false;
	protected $_defaultOrderWay = 'ASC';

	public $tpl_form_vars = array();
	public $tpl_list_vars = array();
	public $tpl_delete_link_vars = array();
	public $tpl_option_vars = array();
	public $tpl_view_vars = array();
	public $tpl_required_fields_vars = array();

	public $base_tpl_view = null;
	public $base_tpl_form = null;

	/** @var bool if you want more fieldsets in the form */
	public $multiple_fieldsets = false;

	public $fields_value = false;

	/** @var array Errors displayed after post processing */
	public $errors = array();

	/** @var define if the header of the list contains filter and sorting links or not */
	protected $list_simple_header;

	/** @var array list to be generated */
	protected $fields_list;
	
	/** @var array modules list filters */
	protected $filter_modules_list = null;
	
	/** @var array modules list filters */
	protected $modules_list = array();
	
	/** @var array edit form to be generated */
	protected $fields_form;

	/** @var override of $fields_form */
	protected $fields_form_override;
	
	/** @var override form action */
	protected $submit_action;

	/** @var array list of option forms to be generated */
	protected $fields_options = array();

	protected $shopLink;

	/** @var string SQL query */
	protected $_listsql = '';

	/** @var array Cache for query results */
	protected $_list = array();

	/** @var define if the header of the list contains filter and sorting links or not */
	protected $toolbar_title;

	/** @var array list of toolbar buttons */
	protected $toolbar_btn = null;

	/** @var boolean scrolling toolbar */
	protected $toolbar_scroll = true;

	/** @var boolean set to false to hide toolbar and page title */
	protected $show_toolbar = true;

	/** @var boolean set to true to show toolbar and page title for options */
	protected $show_toolbar_options = false;

	/** @var integer Number of results in list */
	protected $_listTotal = 0;

	/** @var boolean Automatically join language table if true */
	public $lang = false;

	/** @var array WHERE clause determined by filter fields */
	protected $_filter;

	/** @var array Temporary SQL table WHERE clause determinated by filter fields */
	protected $_tmpTableFilter = '';

	/** @var array Number of results in list per page (used in select field) */
	protected $_pagination = array(20, 50, 100, 300, 1000);

	/** @var integer Default number of results in list per page */
	protected $_default_pagination = 50;

	/** @var string ORDER BY clause determined by field/arrows in list header */
	protected $_orderBy;

	/** @var string Order way (ASC, DESC) determined by arrows in list header */
	protected $_orderWay;

	/** @var array list of available actions for each list row - default actions are view, edit, delete, duplicate */
	protected $actions_available = array('view', 'edit', 'duplicate', 'delete');

	/** @var array list of required actions for each list row */
	protected $actions = array();

	/** @var array list of row ids associated with a given action for witch this action have to not be available */
	protected $list_skip_actions = array();

	/* @var boolean don't show header & footer */
	protected $lite_display = false;
	/** @var bool boolean List content lines are clickable if true */
	protected $list_no_link = false;

	protected $allow_export = false;

	/** @var array $cache_lang cache for traduction */
	public static $cache_lang = array();

	/** @var array required_fields to display in the Required Fields form */
	public $required_fields = array();
	
	/** @var Helper */
	protected $helper;

	/**
	 * @var array actions to execute on multiple selections
	 * Usage:
	 * array(
	 * 		'actionName' => array(
	 * 			'text' => $this->l('Message displayed on the submit button (mandatory)'),
	 * 			'confirm' => $this->l('If set, this confirmation message will pop-up (optional)')),
	 * 		'anotherAction' => array(...)
	 * );
	 *
	 * If your action is named 'actionName', you need to have a method named bulkactionName() that will be executed when the button is clicked.
	 */
	protected $bulk_actions;

	/**
	 * @var array ids of the rows selected
	 */
	protected $boxes;
	
	/** @var string Do not automatically select * anymore but select only what is necessary */
	protected $explicitSelect = false;

	/** @var string Add fields into data query to display list */
	protected $_select;

	/** @var string Join tables into data query to display list */
	protected $_join;

	/** @var string Add conditions into data query to display list */
	protected $_where;

	/** @var string Group rows into data query to display list */
	protected $_group;

	/** @var string Having rows into data query to display list */
	protected $_having;

	protected $is_cms = false;

	/** @var string	identifier to use for changing positions in lists (can be omitted if positions cannot be changed) */
	protected $position_identifier;
	protected $position_group_identifier;

	/** @var boolean Table records are not deleted but marked as deleted if set to true */
	protected $deleted = false;
	/**
	 * @var bool is a list filter set
	 */
	protected $filter;
	protected $noLink;
	protected $specificConfirmDelete = null;
	protected $colorOnBackground;
	/** @var bool If true, activates color on hover */
	protected $row_hover = true;
	/** @string Action to perform : 'edit', 'view', 'add', ... */
	protected $action;
	protected $display;
	protected $_includeContainer = true;
	protected $tab_modules_list = array('default_list' => array(), 'slider_list' => array());

	public $tpl_folder;

	protected $bo_theme;

	/** @var bool Redirect or not ater a creation */
	protected $_redirect = true;

	/** @var array Name and directory where class image are located */
	public $fieldImageSettings = array();

	/** @var string Image type */
	public $imageType = 'jpg';

	/** @var instanciation of the class associated with the AdminController */
	protected $object;

	/** @var int current object ID */
	protected $id_object;

	/**
	 * @var current controller name without suffix
	 */
	public $controller_name;

	public $multishop_context = -1;
	public $multishop_context_group = true;

	/**
	 * Current breadcrumb position as an array of tab names
	 */
	protected $breadcrumbs;

	//Bootstrap variable
	public $show_page_header_toolbar = false;
	public $page_header_toolbar_title;
	public $page_header_toolbar_btn = array();
	public $show_form_cancel_button;

	public $admin_webpath;
	
	protected $list_natives_modules = array();
	protected $list_partners_modules = array();

	public function __construct()
	{
		global $timer_start;
		$this->timer_start = $timer_start;
		// Has to be remove for the next Prestashop version
		global $token;

		$this->controller_type = 'admin';
		$this->controller_name = get_class($this);
		if (strpos($this->controller_name, 'Controller'))
			$this->controller_name = substr($this->controller_name, 0, -10);
		parent::__construct();

		if ($this->multishop_context == -1)
			$this->multishop_context = Shop::CONTEXT_ALL | Shop::CONTEXT_GROUP | Shop::CONTEXT_SHOP;

		$this->bo_theme = ((Validate::isLoadedObject($this->context->employee) && $this->context->employee->bo_theme) ? $this->context->employee->bo_theme : 'default');
		if (!file_exists(_PS_BO_ALL_THEMES_DIR_.$this->bo_theme.DIRECTORY_SEPARATOR.'template'))
			$this->bo_theme = 'default';
		$this->bo_css = ((Validate::isLoadedObject($this->context->employee) && $this->context->employee->bo_css) ? $this->context->employee->bo_css : 'admin-theme.css');
		if (!file_exists(_PS_BO_ALL_THEMES_DIR_.$this->bo_theme.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$this->bo_css))
			$this->bo_css = 'admin-theme.css';

		$this->context->smarty->setTemplateDir(array(
			_PS_BO_ALL_THEMES_DIR_.$this->bo_theme.DIRECTORY_SEPARATOR.'template',
			_PS_OVERRIDE_DIR_.'controllers'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'templates'
		));

		$this->id = Tab::getIdFromClassName($this->controller_name);
		$this->token = Tools::getAdminToken($this->controller_name.(int)$this->id.(int)$this->context->employee->id);

		$token = $this->token;

		$this->_conf = array(
			1 => $this->l('Deletion successful'),
			2 => $this->l('The selection has been successfully deleted.'),
			3 => $this->l('Creation successful'),
			4 => $this->l('Update successful'),
			5 => $this->l('The status has been updated successfully.'),
			6 => $this->l('The settings have been updated successfully.'),
			7 => $this->l('The image was successfully deleted.'),
			8 => $this->l('The module was downloaded successfully.'),
			9 => $this->l('The thumbnails were successfully regenerated.'),
			10 => $this->l('Message sent to the customer.'),
			11 => $this->l('Comment added'),
			12 => $this->l('Module(s) installed successfully.'),
			13 => $this->l('Module(s) uninstalled successfully.'),
			14 => $this->l('The translation was successfully copied.'),
			15 => $this->l('The translations have been successfully added.'),
			16 => $this->l('The module transplanted successfully to the hook.'),
			17 => $this->l('The module was successfully removed from the hook.'),
			18 => $this->l('Upload successful'),
			19 => $this->l('Duplication was completed successfully.'),
			20 => $this->l('The translation was added successfully, but the language has not been created.'),
			21 => $this->l('Module reset successfully.'),
			22 => $this->l('Module deleted successfully.'),
			23 => $this->l('Localization pack imported successfully.'),
			24 => $this->l('Localization pack imported successfully.'),
			25 => $this->l('The selected images have successfully been moved.'),
			26 => $this->l('Your cover selection has been saved.'),
			27 => $this->l('The image shop association has been modified.'),
			28 => $this->l('A zone has been assigned to the selection successfully.'),
			29 => $this->l('Upgrade successful'),
			30 => $this->l('A partial refund was successfully created.'),
			31 => $this->l('The discount was successfully generated.'),
			32 => $this->l('Successfully signed in to PrestaShop Addons')
		);

		if (!$this->identifier) $this->identifier = 'id_'.$this->table;
		if (!$this->_defaultOrderBy) $this->_defaultOrderBy = $this->identifier;
		$this->tabAccess = Profile::getProfileAccess($this->context->employee->id_profile, $this->id);

		// Fix for homepage
		if ($this->controller_name == 'AdminDashboard')
			$_POST['token'] = $this->token;

		if (!Shop::isFeatureActive())
			$this->shopLinkType = '';

		//$this->base_template_folder = _PS_BO_ALL_THEMES_DIR_.$this->bo_theme.'/template';
		$this->override_folder = Tools::toUnderscoreCase(substr($this->controller_name, 5)).'/';
		// Get the name of the folder containing the custom tpl files
		$this->tpl_folder = Tools::toUnderscoreCase(substr($this->controller_name, 5)).'/';

		$this->initShopContext();

		$this->context->currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
		
		$this->admin_webpath = str_ireplace(_PS_CORE_DIR_, '', _PS_ADMIN_DIR_);
		$this->admin_webpath = preg_replace('/^'.preg_quote(DIRECTORY_SEPARATOR, '/').'/', '', $this->admin_webpath);


	}

	/**
	 * Set breadcrumbs array for the controller page
	 */
	public function initBreadcrumbs($tab_id = null, $tabs = null)
	{
		if (is_array($tabs) || count($tabs))
			$tabs = array();
		
		if (is_null($tab_id))
			$tab_id = $this->id;
		
		$tabs = Tab::recursiveTab($tab_id, $tabs);

		$dummy = array('name' => '', 'href' => '', 'icon' => '');
		$breadcrumbs2 = array(
			'container' => $dummy,
			'tab' => $dummy,
			'action' => $dummy
		);
		if (isset($tabs[0]))
		{
			$breadcrumbs2['tab']['name'] = $tabs[0]['name'];
			$breadcrumbs2['tab']['href'] = __PS_BASE_URI__.basename(_PS_ADMIN_DIR_ ).'/'.$this->context->link->getAdminLink($tabs[0]['class_name']);
			if (!isset($tabs[1]))
				$breadcrumbs2['tab']['icon'] = 'icon-'.$tabs[0]['class_name'];
		}
		if (isset($tabs[1]))
		{
			$breadcrumbs2['container']['name'] = $tabs[1]['name'];
			$breadcrumbs2['container']['href'] = __PS_BASE_URI__.basename(_PS_ADMIN_DIR_ ).'/'.$this->context->link->getAdminLink($tabs[1]['class_name']);
			$breadcrumbs2['container']['icon'] = 'icon-'.$tabs[1]['class_name'];
		}

		/* content, edit, list, add, details, options, view */
		switch ($this->display)
		{
			case 'add':
				$breadcrumbs2['action']['name'] = $this->l('Add', null, null, false);
				$breadcrumbs2['action']['icon'] = 'icon-plus';
				break;
			case 'edit':
				$breadcrumbs2['action']['name'] = $this->l('Edit', null, null, false);
				$breadcrumbs2['action']['icon'] = 'icon-pencil';
				break;
			case '':
			case 'list':
				$breadcrumbs2['action']['name'] = $this->l('List', null, null, false);
				$breadcrumbs2['action']['icon'] = 'icon-th-list';
				break;
			case 'details':
			case 'view':
				$breadcrumbs2['action']['name'] = $this->l('View details', null, null, false);
				$breadcrumbs2['action']['icon'] = 'icon-zoom-in';
				break;
			case 'options':
				$breadcrumbs2['action']['name'] = $this->l('Options', null, null, false);
				$breadcrumbs2['action']['icon'] = 'icon-cogs';
				break;
			case 'generator':
				$breadcrumbs2['action']['name'] = $this->l('Generator', null, null, false);
				$breadcrumbs2['action']['icon'] = 'icon-flask';
				break;
		}

		$this->context->smarty->assign('breadcrumbs2', $breadcrumbs2);

		/* BEGIN - Backward compatibility < 1.6.0.3 */
		$this->breadcrumbs[] = $tabs[0]['name'];
		$navigationPipe = (Configuration::get('PS_NAVIGATION_PIPE') ? Configuration::get('PS_NAVIGATION_PIPE') : '>');
		$this->context->smarty->assign('navigationPipe', $navigationPipe);
		/* END - Backward compatibility < 1.6.0.3 */
	}

	/**
	 * set default toolbar_title to admin breadcrumb
	 *
	 * @return void
	 */
	public function initToolbarTitle()
	{
		$this->toolbar_title = is_array($this->breadcrumbs) ? array_unique($this->breadcrumbs) : array($this->breadcrumbs);

		switch ($this->display)
		{
			case 'edit':
				$this->toolbar_title[] = $this->l('Edit', null, null, false);
				break;

			case 'add':
				$this->toolbar_title[] = $this->l('Add new', null, null, false);
				break;

			case 'view':
				$this->toolbar_title[] = $this->l('View', null, null, false);
				break;
		}

		if ($filter = $this->addFiltersToBreadcrumbs())
			$this->toolbar_title[] = $filter;
	}
	
	public function addFiltersToBreadcrumbs()
	{
		if (Tools::isSubmit('submitFilter') && is_array($this->fields_list))
		{
			$filters = array();
			foreach ($this->fields_list as $field => $t)
			{
				if (isset($t['filter_key']))
					$field = $t['filter_key'];
				if ($val = Tools::getValue($this->table.'Filter_'.$field))
				{
					if (!is_array($val))
					{
						$filter_value = '';
						if (isset($t['type']) && $t['type'] == 'bool')
							$filter_value = ((bool)$val) ? $this->l('yes') : $this->l('no');
						elseif (is_string($val))
							$filter_value = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
						if (!empty($filter_value))
							$filters[] = sprintf($this->l('%s: %s'), $t['title'], $filter_value);
					}
					else
					{
						$filter_value = '';
						foreach ($val as $v)
							if (is_string($v) && !empty($v))
								$filter_value .= ' - '.htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
						$filter_value = ltrim($filter_value, ' -');
						if (!empty($filter_value))
							$filters[] = sprintf($this->l('%s: %s'), $t['title'], $filter_value);
					}
				}
			}

			if (count($filters))
				return sprintf($this->l('filter by %s'), implode(', ', $filters));
		}
	}

	/**
	 * Check rights to view the current tab
	 *
	 * @param bool $disable
	 * @return boolean
	 */
	public function viewAccess($disable = false)
	{
		if ($disable)
			return true;

		if ($this->tabAccess['view'] === '1')
			return true;
		return false;
	}

	/**
	 * Check for security token
	 */
	public function checkToken()
	{
		$token = Tools::getValue('token');
		return (!empty($token) && $token === $this->token);
	}

	/**
	 * Set the filters used for the list display
	 */
	public function processFilter()
	{
		if (!isset($this->list_id))
			$this->list_id = $this->table;

		$prefix = str_replace(array('admin', 'controller'), '', Tools::strtolower(get_class($this)));

		if (isset($this->list_id))
		{
			foreach ($_POST as $key => $value)
			{
				if ($value === '')
					unset($this->context->cookie->{$prefix.$key});
				elseif (stripos($key, $this->list_id.'Filter_') === 0)
					$this->context->cookie->{$prefix.$key} = !is_array($value) ? $value : serialize($value);
				elseif (stripos($key, 'submitFilter') === 0)
					$this->context->cookie->$key = !is_array($value) ? $value : serialize($value);
			}

			foreach ($_GET as $key => $value)
				if (stripos($key, $this->list_id.'OrderBy') === 0 && Validate::isOrderBy($value))
				{
					if ($value === '' || $value == $this->_defaultOrderBy)
						unset($this->context->cookie->{$prefix.$key});
					else
						$this->context->cookie->{$prefix.$key} = $value;
				}
				elseif (stripos($key, $this->list_id.'Orderway') === 0 && Validate::isOrderWay($value))
				{
					if ($value === '' || $value == $this->_defaultOrderWay)
						unset($this->context->cookie->{$prefix.$key});
					else
						$this->context->cookie->{$prefix.$key} = $value;
				}
		}

		$filters = $this->context->cookie->getFamily($prefix.$this->list_id.'Filter_');

		foreach ($filters as $key => $value)
		{
			/* Extracting filters from $_POST on key filter_ */
			if ($value != null && !strncmp($key, $prefix.$this->list_id.'Filter_', 7 + Tools::strlen($prefix.$this->list_id)))
			{
				$key = Tools::substr($key, 7 + Tools::strlen($prefix.$this->list_id));
				/* Table alias could be specified using a ! eg. alias!field */
				$tmp_tab = explode('!', $key);
				$filter = count($tmp_tab) > 1 ? $tmp_tab[1] : $tmp_tab[0];

				if ($field = $this->filterToField($key, $filter))
				{
					$type = (array_key_exists('filter_type', $field) ? $field['filter_type'] : (array_key_exists('type', $field) ? $field['type'] : false));					if (($type == 'date' || $type == 'datetime') && is_string($value))
						$value = Tools::unSerialize($value);
					$key = isset($tmp_tab[1]) ? $tmp_tab[0].'.`'.$tmp_tab[1].'`' : '`'.$tmp_tab[0].'`';

					// Assignement by reference
					if (array_key_exists('tmpTableFilter', $field))
						$sql_filter = & $this->_tmpTableFilter;
					elseif (array_key_exists('havingFilter', $field))
						$sql_filter = & $this->_filterHaving;
					else
						$sql_filter = & $this->_filter;

					/* Only for date filtering (from, to) */
					if (is_array($value))
					{
						if (isset($value[0]) && !empty($value[0]))
						{
							if (!Validate::isDate($value[0]))
								$this->errors[] = Tools::displayError('The \'From\' date format is invalid (YYYY-MM-DD)');
							else
								$sql_filter .= ' AND '.pSQL($key).' >= \''.pSQL(Tools::dateFrom($value[0])).'\'';
						}

						if (isset($value[1]) && !empty($value[1]))
						{
							if (!Validate::isDate($value[1]))
								$this->errors[] = Tools::displayError('The \'To\' date format is invalid (YYYY-MM-DD)');
							else
								$sql_filter .= ' AND '.pSQL($key).' <= \''.pSQL(Tools::dateTo($value[1])).'\'';
						}
					}
					else
					{
						$sql_filter .= ' AND ';
						$check_key = ($key == $this->identifier || $key == '`'.$this->identifier.'`');

						if ($type == 'int' || $type == 'bool')
							$sql_filter .= (($check_key || $key == '`active`') ? 'a.' : '').pSQL($key).' = '.(int)$value.' ';
						elseif ($type == 'decimal')
							$sql_filter .= ($check_key ? 'a.' : '').pSQL($key).' = '.(float)$value.' ';
						elseif ($type == 'select')
							$sql_filter .= ($check_key ? 'a.' : '').pSQL($key).' = \''.pSQL($value).'\' ';
						else
							$sql_filter .= ($check_key ? 'a.' : '').pSQL($key).' LIKE \'%'.pSQL($value).'%\' ';
					}
				}
			}
		}
	}

	/**
	 * @todo uses redirectAdmin only if !$this->ajax
	 */
	public function postProcess()
	{
		try {
			if ($this->ajax)
			{
				// from ajax-tab.php
				$action = Tools::getValue('action');
				// no need to use displayConf() here
				if (!empty($action) && method_exists($this, 'ajaxProcess'.Tools::toCamelCase($action)))
				{
					Hook::exec('actionAdmin'.ucfirst($this->action).'Before', array('controller' => $this));
					Hook::exec('action'.get_class($this).ucfirst($this->action).'Before', array('controller' => $this));

					$return = $this->{'ajaxProcess'.Tools::toCamelCase($action)}();

					Hook::exec('actionAdmin'.ucfirst($this->action).'After', array('controller' => $this, 'return' => $return));
					Hook::exec('action'.get_class($this).ucfirst($this->action).'After', array('controller' => $this, 'return' => $return));

					return $return;
				}
				elseif (!empty($action) && $this->controller_name == 'AdminModules' && Tools::getIsset('configure'))
				{
					$module_obj = Module::getInstanceByName(Tools::getValue('configure'));
					if (Validate::isLoadedObject($module_obj) && method_exists($module_obj, 'ajaxProcess'.$action))
						return $module_obj->{'ajaxProcess'.$action}();
				}
				elseif (method_exists($this, 'ajaxProcess'))
					return $this->ajaxProcess();
			}
			else
			{
				// Process list filtering
				if ($this->filter)
					$this->processFilter();

				// If the method named after the action exists, call "before" hooks, then call action method, then call "after" hooks
				if (!empty($this->action) && method_exists($this, 'process'.ucfirst(Tools::toCamelCase($this->action))))
				{
					// Hook before action
					Hook::exec('actionAdmin'.ucfirst($this->action).'Before', array('controller' => $this));
					Hook::exec('action'.get_class($this).ucfirst($this->action).'Before', array('controller' => $this));
					// Call process
					$return = $this->{'process'.Tools::toCamelCase($this->action)}();
					// Hook After Action
					Hook::exec('actionAdmin'.ucfirst($this->action).'After', array('controller' => $this, 'return' => $return));
					Hook::exec('action'.get_class($this).ucfirst($this->action).'After', array('controller' => $this, 'return' => $return));

					return $return;
				}
			}
		} catch (PrestaShopException $e) {
			$this->errors[] = $e->getMessage();
		};
		return false;
	}

	/**
	 * Object Delete images
	 */
	public function processDeleteImage()
	{
		if (Validate::isLoadedObject($object = $this->loadObject()))
		{
			if (($object->deleteImage()))
			{
				$redirect = self::$currentIndex.'&add'.$this->table.'&'.$this->identifier.'='.Tools::getValue($this->identifier).'&conf=7&token='.$this->token;
				if (!$this->ajax)
					$this->redirect_after = $redirect;
				else
					$this->content = 'ok';
			}
		}
		$this->errors[] = Tools::displayError('An error occurred while attempting to delet the image. (cannot load object).');
		return $object;
	}
	
	public function processExport($text_delimiter = '"')
	{

		// clean buffer
		if (ob_get_level() && ob_get_length() > 0)
			ob_clean();
		$this->getList($this->context->language->id, null, null, 0, false);
		if (!count($this->_list))
			return;

		header('Content-type: text/csv');
		header('Content-Type: application/force-download; charset=UTF-8');
		header('Cache-Control: no-store, no-cache');
		header('Content-disposition: attachment; filename="'.$this->table.'_'.date('Y-m-d_His').'.csv"');

		$headers = array();
		foreach ($this->fields_list as $datas)
			$headers[] = Tools::htmlentitiesDecodeUTF8($datas['title']);
		$content = array();
		foreach ($this->_list as $i => $row)
		{
			$content[$i] = array();
			$path_to_image = false;
			foreach ($this->fields_list as $key => $params)
			{
				$field_value = isset($row[$key]) ? Tools::htmlentitiesDecodeUTF8(
					Tools::nl2br($row[$key])) : '';
				if ($key == 'image')
				{
					if ($params['image'] != 'p' || Configuration::get('PS_LEGACY_IMAGES'))
						$path_to_image = Tools::getShopDomain(true)._PS_IMG_.$params['image'].'/'.$row['id_'.$this->table].(isset($row['id_image']) ? '-'.(int)$row['id_image'] : '').'.'.$this->imageType;
					else
						$path_to_image = Tools::getShopDomain(true)._PS_IMG_.$params['image'].'/'.Image::getImgFolderStatic($row['id_image']).(int)$row['id_image'].'.'.$this->imageType;
					if ($path_to_image)
						$field_value = $path_to_image;  
				}
				$content[$i][] = $field_value;
			}
		}

		$this->context->smarty->assign(array(
			'export_precontent' => "\xEF\xBB\xBF",
			'export_headers' => $headers,
			'export_content' => $content,
			'text_delimiter' => $text_delimiter
			)
		);

		$this->layout = 'layout-export.tpl';
	}

	/**
	 * Object Delete
	 */
	public function processDelete()
	{
		if (Validate::isLoadedObject($object = $this->loadObject()))
		{
			$res = true;
			// check if request at least one object with noZeroObject
			if (isset($object->noZeroObject) && count(call_user_func(array($this->className, $object->noZeroObject))) <= 1)
			{
				$this->errors[] = Tools::displayError('You need at least one object.').
					' <b>'.$this->table.'</b><br />'.
					Tools::displayError('You cannot delete all of the items.');
			}
			elseif (array_key_exists('delete', $this->list_skip_actions) && in_array($object->id, $this->list_skip_actions['delete'])) //check if some ids are in list_skip_actions and forbid deletion
					$this->errors[] = Tools::displayError('You cannot delete this item.');
			else
			{
				if ($this->deleted)
				{
					if (!empty($this->fieldImageSettings))
						$res = $object->deleteImage();

					if (!$res)
						$this->errors[] = Tools::displayError('Unable to delete associated images.');

					$object->deleted = 1;
					if ($res = $object->update())
						$this->redirect_after = self::$currentIndex.'&conf=1&token='.$this->token;
				}
				elseif ($res = $object->delete())
					$this->redirect_after = self::$currentIndex.'&conf=1&token='.$this->token;
				$this->errors[] = Tools::displayError('An error occurred during deletion.');
				if ($res)
					PrestaShopLogger::addLog(sprintf($this->l('%s deletion', 'AdminTab', false, false), $this->className), 1, null, $this->className, (int)$this->object->id, true, (int)$this->context->employee->id);
			}
		}
		else
		{
			$this->errors[] = Tools::displayError('An error occurred while deleting the object.').
				' <b>'.$this->table.'</b> '.
				Tools::displayError('(cannot load object)');
		}
		return $object;
	}

	/**
	 * Call the right method for creating or updating object
	 *
	 * @return mixed
	 */
	public function processSave()
	{
		if ($this->id_object)
		{
			$this->object = $this->loadObject();
			return $this->processUpdate();
		}
		else
			return $this->processAdd();
	}

	/**
	 * Object creation
	 */
	public function processAdd()
	{
		if (!isset($this->className) || empty($this->className))
			return false;
		/* Checking fields validity */
		$this->validateRules();
		if (count($this->errors) <= 0)
		{
			$this->object = new $this->className();

			$this->copyFromPost($this->object, $this->table);
			$this->beforeAdd($this->object);
			if (method_exists($this->object, 'add') && !$this->object->add())
			{
				$this->errors[] = Tools::displayError('An error occurred while creating an object.').
					' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
			}
			/* voluntary do affectation here */
			elseif (($_POST[$this->identifier] = $this->object->id) && $this->postImage($this->object->id) && !count($this->errors) && $this->_redirect)
			{
				PrestaShopLogger::addLog(sprintf($this->l('%s addition', 'AdminTab', false, false), $this->className), 1, null, $this->className, (int)$this->object->id, true, (int)$this->context->employee->id);
				$parent_id = (int)Tools::getValue('id_parent', 1);
				$this->afterAdd($this->object);
				$this->updateAssoShop($this->object->id);
				// Save and stay on same form
				if (empty($this->redirect_after) && $this->redirect_after !== false && Tools::isSubmit('submitAdd'.$this->table.'AndStay'))
					$this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$this->object->id.'&conf=3&update'.$this->table.'&token='.$this->token;
				// Save and back to parent
				if (empty($this->redirect_after) && $this->redirect_after !== false && Tools::isSubmit('submitAdd'.$this->table.'AndBackToParent'))
					$this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$parent_id.'&conf=3&token='.$this->token;
				// Default behavior (save and back)
				if (empty($this->redirect_after) && $this->redirect_after !== false)
					$this->redirect_after = self::$currentIndex.($parent_id ? '&'.$this->identifier.'='.$this->object->id : '').'&conf=3&token='.$this->token;
			}
		}

		$this->errors = array_unique($this->errors);
		if (!empty($this->errors))
		{
			// if we have errors, we stay on the form instead of going back to the list
			$this->display = 'edit';
			return false;
		}

		return $this->object;
	}


	/**
	 * Object update
	 */
	public function processUpdate()
	{
		/* Checking fields validity */
		$this->validateRules();
		if (empty($this->errors))
		{
			$id = (int)Tools::getValue($this->identifier);

			/* Object update */
			if (isset($id) && !empty($id))
			{
				$object = new $this->className($id);
				if (Validate::isLoadedObject($object))
				{
					/* Specific to objects which must not be deleted */
					if ($this->deleted && $this->beforeDelete($object))
					{
						// Create new one with old objet values
						$object_new = $object->duplicateObject();
						if (Validate::isLoadedObject($object_new))
						{
							// Update old object to deleted
							$object->deleted = 1;
							$object->update();

							// Update new object with post values
							$this->copyFromPost($object_new, $this->table);
							$result = $object_new->update();
							if (Validate::isLoadedObject($object_new))
								$this->afterDelete($object_new, $object->id);
						}
					}
					else
					{
						$this->copyFromPost($object, $this->table);
						$result = $object->update();
						$this->afterUpdate($object);
					}

					if ($object->id)
						$this->updateAssoShop($object->id);

					if (!$result)
					{
						$this->errors[] = Tools::displayError('An error occurred while updating an object.').
							' <b>'.$this->table.'</b> ('.Db::getInstance()->getMsgError().')';
					}
					elseif ($this->postImage($object->id) && !count($this->errors) && $this->_redirect)
					{
						$parent_id = (int)Tools::getValue('id_parent', 1);
						// Specific back redirect
						if ($back = Tools::getValue('back'))
							$this->redirect_after = urldecode($back).'&conf=4';
						// Specific scene feature
						// @todo change stay_here submit name (not clear for redirect to scene ... )
						if (Tools::getValue('stay_here') == 'on' || Tools::getValue('stay_here') == 'true' || Tools::getValue('stay_here') == '1')
							$this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$object->id.'&conf=4&updatescene&token='.$this->token;
						// Save and stay on same form
						// @todo on the to following if, we may prefer to avoid override redirect_after previous value
						if (Tools::isSubmit('submitAdd'.$this->table.'AndStay'))
							$this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$object->id.'&conf=4&update'.$this->table.'&token='.$this->token;
						// Save and back to parent
						if (Tools::isSubmit('submitAdd'.$this->table.'AndBackToParent'))
							$this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$parent_id.'&conf=4&token='.$this->token;

						// Default behavior (save and back)
						if (empty($this->redirect_after) && $this->redirect_after !== false)
							$this->redirect_after = self::$currentIndex.($parent_id ? '&'.$this->identifier.'='.$object->id : '').'&conf=4&token='.$this->token;
					}
					PrestaShopLogger::addLog(sprintf($this->l('%s edition', 'AdminTab', false, false), $this->className), 1, null, $this->className, (int)$object->id, true, (int)$this->context->employee->id);
				}
				else
					$this->errors[] = Tools::displayError('An error occurred while updating an object.').
						' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
			}
		}
		$this->errors = array_unique($this->errors);
		if (!empty($this->errors))
		{
			// if we have errors, we stay on the form instead of going back to the list
			$this->display = 'edit';
			return false;
		}

		if (isset($object))
			return $object;
		return;
	}

	/**
	 * Change object required fields
	 */
	public function processUpdateFields()
	{
		if (!is_array($fields = Tools::getValue('fieldsBox')))
			$fields = array();

		$object = new $this->className();
		if (!$object->addFieldsRequiredDatabase($fields))
			$this->errors[] = Tools::displayError('An error occurred when attempting to update the required fields.');
		else
			$this->redirect_after = self::$currentIndex.'&conf=4&token='.$this->token;

		return $object;
	}

	/**
	 * Change object status (active, inactive)
	 */
	public function processStatus()
	{
		if (Validate::isLoadedObject($object = $this->loadObject()))
		{
			if ($object->toggleStatus())
			{
				$matches = array();
				if (preg_match('/[\?|&]controller=([^&]*)/', (string)$_SERVER['HTTP_REFERER'], $matches) !== FALSE
					&& strtolower($matches[1]) != strtolower(preg_replace('/controller/i', '', get_class($this))))
						$this->redirect_after = preg_replace('/[\?|&]conf=([^&]*)/i', '', (string)$_SERVER['HTTP_REFERER']);
				else
					$this->redirect_after = self::$currentIndex.'&token='.$this->token;

				$id_category = (($id_category = (int)Tools::getValue('id_category')) && Tools::getValue('id_product')) ? '&id_category='.$id_category : '';
				$this->redirect_after .= '&conf=5'.$id_category;
			}
			else
				$this->errors[] = Tools::displayError('An error occurred while updating the status.');
		}
		else
			$this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').
				' <b>'.$this->table.'</b> '.
				Tools::displayError('(cannot load object)');

		return $object;
	}

	/**
	 * Change object position
	 */
	public function processPosition()
	{
		if (!Validate::isLoadedObject($object = $this->loadObject()))
		{
			$this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').
				' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
		}
		elseif (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position')))
			$this->errors[] = Tools::displayError('Failed to update the position.');
		else
		{
			$id_identifier_str = ($id_identifier = (int)Tools::getValue($this->identifier)) ? '&'.$this->identifier.'='.$id_identifier : '';
			$redirect = self::$currentIndex.'&'.$this->table.'Orderby=position&'.$this->table.'Orderway=asc&conf=5'.$id_identifier_str.'&token='.$this->token;
			$this->redirect_after = $redirect;
		}
		return $object;
	}

	/**
	 * Cancel all filters for this tab
	 */
	public function processResetFilters($list_id = null)
	{
		if ($list_id === null)
			$list_id = isset($this->list_id) ? $this->list_id : $this->table;

		$prefix = str_replace(array('admin', 'controller'), '', Tools::strtolower(get_class($this)));
		$filters = $this->context->cookie->getFamily($prefix.$list_id.'Filter_');
		foreach ($filters as $cookie_key => $filter)
			if (strncmp($cookie_key, $prefix.$list_id.'Filter_', 7 + Tools::strlen($prefix.$list_id)) == 0)
			{
				$key = substr($cookie_key, 7 + Tools::strlen($prefix.$list_id));
				if (is_array($this->fields_list) && array_key_exists($key, $this->fields_list))
					$this->context->cookie->$cookie_key = null;
				unset($this->context->cookie->$cookie_key);
			}

		if (isset($this->context->cookie->{'submitFilter'.$list_id}))
			unset($this->context->cookie->{'submitFilter'.$list_id});
		if (isset($this->context->cookie->{$prefix.$list_id.'Orderby'}))
			unset($this->context->cookie->{$prefix.$list_id.'Orderby'});
		if (isset($this->context->cookie->{$prefix.$list_id.'Orderway'}))
			unset($this->context->cookie->{$prefix.$list_id.'Orderway'});

		$_POST = array();
		$this->_filter = false;
		unset($this->_filterHaving);
		unset($this->_having);
	}

	/**
	 * Update options and preferences
	 */
	protected function processUpdateOptions()
	{
		$this->beforeUpdateOptions();

		$languages = Language::getLanguages(false);

		$hide_multishop_checkbox = (Shop::getTotalShops(false, null) < 2) ? true : false;
		foreach ($this->fields_options as $category_data)
		{
			if (!isset($category_data['fields']))
				continue;

			$fields = $category_data['fields'];

			foreach ($fields as $field => $values)
			{
				if (isset($values['type']) && $values['type'] == 'selectLang')
				{
					foreach ($languages as $lang)
						if (Tools::getValue($field.'_'.strtoupper($lang['iso_code'])))
							$fields[$field.'_'.strtoupper($lang['iso_code'])] = array(
								'type' => 'select',
								'cast' => 'strval',
								'identifier' => 'mode',
								'list' => $values['list']
							);
				}
			}

			// Validate fields
			foreach ($fields as $field => $values)
			{
				// We don't validate fields with no visibility
				if (!$hide_multishop_checkbox && Shop::isFeatureActive() && isset($values['visibility']) && $values['visibility'] > Shop::getContext())
					continue;

				// Check if field is required
				if ((!Shop::isFeatureActive() && isset($values['required']) && $values['required']) 
					|| (Shop::isFeatureActive() && isset($_POST['multishopOverrideOption'][$field]) && isset($values['required']) && $values['required']))
					if (isset($values['type']) && $values['type'] == 'textLang')
					{
						foreach ($languages as $language)
							if (($value = Tools::getValue($field.'_'.$language['id_lang'])) == false && (string)$value != '0')
								$this->errors[] = sprintf(Tools::displayError('field %s is required.'), $values['title']);
					}
					elseif (($value = Tools::getValue($field)) == false && (string)$value != '0')
						$this->errors[] = sprintf(Tools::displayError('field %s is required.'), $values['title']);

				// Check field validator
				if (isset($values['type']) && $values['type'] == 'textLang')
				{
					foreach ($languages as $language)
						if (Tools::getValue($field.'_'.$language['id_lang']) && isset($values['validation']))
							if (!Validate::$values['validation'](Tools::getValue($field.'_'.$language['id_lang'])))
								$this->errors[] = sprintf(Tools::displayError('field %s is invalid.'), $values['title']);
				}
				elseif (Tools::getValue($field) && isset($values['validation']))
					if (!Validate::$values['validation'](Tools::getValue($field)))
						$this->errors[] = sprintf(Tools::displayError('field %s is invalid.'), $values['title']);

				// Set default value
				if (Tools::getValue($field) === false && isset($values['default']))
					$_POST[$field] = $values['default'];
			}

			if (!count($this->errors))
			{
				foreach ($fields as $key => $options)
				{
					if (!$hide_multishop_checkbox && Shop::isFeatureActive() && isset($options['visibility']) && $options['visibility'] > Shop::getContext())
						continue;

					if (!$hide_multishop_checkbox && Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_ALL && empty($options['no_multishop_checkbox']) && empty($_POST['multishopOverrideOption'][$key]))
					{
						Configuration::deleteFromContext($key);
						continue;
					}

					// check if a method updateOptionFieldName is available
					$method_name = 'updateOption'.Tools::toCamelCase($key, true);
					if (method_exists($this, $method_name))
						$this->$method_name(Tools::getValue($key));
					elseif (isset($options['type']) && in_array($options['type'], array('textLang', 'textareaLang')))
					{
						$list = array();
						foreach ($languages as $language)
						{
							$key_lang = Tools::getValue($key.'_'.$language['id_lang']);
							$val = (isset($options['cast']) ? $options['cast']($key_lang) : $key_lang);
							if ($this->validateField($val, $options))
							{
								if (Validate::isCleanHtml($val))
									$list[$language['id_lang']] = $val;
								else
									$this->errors[] = Tools::displayError('Can not add configuration '.$key.' for lang '.Language::getIsoById((int)$language['id_lang']));
							}
						}
						Configuration::updateValue($key, $list);
					}
					else
					{
						$val = (isset($options['cast']) ? $options['cast'](Tools::getValue($key)) : Tools::getValue($key));
						if ($this->validateField($val, $options))
						{
							if (Validate::isCleanHtml($val))
								Configuration::updateValue($key, $val);
							else
								$this->errors[] = Tools::displayError('Can not add configuration '.$key);
						}
					}
				}
			}
		}

		$this->display = 'list';
		if (empty($this->errors))
			$this->confirmations[] = $this->_conf[6];
	}

	public function initPageHeaderToolbar()
	{
		if (empty($this->toolbar_title))
			$this->initToolbarTitle();

		if (!is_array($this->toolbar_title))
			$this->toolbar_title = array($this->toolbar_title);

		switch ($this->display)
		{
			case 'view':
				// Default cancel button - like old back link
				$back = Tools::safeOutput(Tools::getValue('back', ''));
				if (empty($back))
					$back = self::$currentIndex.'&token='.$this->token;
				if (!Validate::isCleanHtml($back))
					die(Tools::displayError());
				if (!$this->lite_display)
					$this->page_header_toolbar_btn['back'] = array(
						'href' => $back,
						'desc' => $this->l('Back to list')
					);
				$obj = $this->loadObject(true);
				if (Validate::isLoadedObject($obj) && isset($obj->{$this->identifier_name}) && !empty($obj->{$this->identifier_name}))
				{
					array_pop($this->toolbar_title);
					$this->toolbar_title[] = is_array($obj->{$this->identifier_name}) ? $obj->{$this->identifier_name}[$this->context->employee->id_lang] : $obj->{$this->identifier_name};
				}
				break;
			case 'edit':
				$obj = $this->loadObject(true);
				if (Validate::isLoadedObject($obj) && isset($obj->{$this->identifier_name}) && !empty($obj->{$this->identifier_name}))
				{
					array_pop($this->toolbar_title);
					$this->toolbar_title[] = sprintf($this->l('Edit: %s'),
						is_array($obj->{$this->identifier_name}) ? $obj->{$this->identifier_name}[$this->context->employee->id_lang] : $obj->{$this->identifier_name});
				}
				break;
		}

		if (is_array($this->page_header_toolbar_btn)
			&& $this->page_header_toolbar_btn instanceof Traversable
			|| count($this->toolbar_title))
			$this->show_page_header_toolbar = true;

		if (empty($this->page_header_toolbar_title))
			$this->page_header_toolbar_title = array_pop($this->toolbar_title);
		$this->addPageHeaderToolBarModulesListButton();

		$this->context->smarty->assign('help_link', 'http://help.prestashop.com/'.$this->context->language->iso_code.'/doc/'.Tools::getValue('controller').'?version='._PS_VERSION_.'&country='.$this->context->country->iso_code);
	}

	/**
	 * assign default action in toolbar_btn smarty var, if they are not set.
	 * uses override to specifically add, modify or remove items
	 *
	 */
	public function initToolbar()
	{
		switch ($this->display)
		{
			case 'add':
			case 'edit':
				// Default save button - action dynamically handled in javascript
				$this->toolbar_btn['save'] = array(
					'href' => '#',
					'desc' => $this->l('Save')
				);
				$back = Tools::safeOutput(Tools::getValue('back', ''));
				if (empty($back))
					$back = self::$currentIndex.'&token='.$this->token;
				if (!Validate::isCleanHtml($back))
					die(Tools::displayError());
				if (!$this->lite_display)
					$this->toolbar_btn['cancel'] = array(
						'href' => $back,
						'desc' => $this->l('Cancel')
					);
				break;
			case 'view':
				// Default cancel button - like old back link
				$back = Tools::safeOutput(Tools::getValue('back', ''));
				if (empty($back))
					$back = self::$currentIndex.'&token='.$this->token;
				if (!Validate::isCleanHtml($back))
					die(Tools::displayError());
				if (!$this->lite_display)
					$this->toolbar_btn['back'] = array(
						'href' => $back,
						'desc' => $this->l('Back to list')
					);
				break;
			case 'options':
				$this->toolbar_btn['save'] = array(
					'href' => '#',
					'desc' => $this->l('Save')
				);
				break;
			default: // list
				$this->toolbar_btn['new'] = array(
					'href' => self::$currentIndex.'&amp;add'.$this->table.'&amp;token='.$this->token,
					'desc' => $this->l('Add new')
				);
				if ($this->allow_export)
					$this->toolbar_btn['export'] = array(
						'href' => self::$currentIndex.'&amp;export'.$this->table.'&amp;token='.$this->token,
						'desc' => $this->l('Export')
					);
		}
		$this->addToolBarModulesListButton();
	}

	/**
	 * Load class object using identifier in $_GET (if possible)
	 * otherwise return an empty object, or die
	 *
	 * @param boolean $opt Return an empty object if load fail
	 * @return object|boolean
	 */
	protected function loadObject($opt = false)
	{
		if (!isset($this->className) || empty($this->className))
			return true;
		$id = (int)Tools::getValue($this->identifier);
		if ($id && Validate::isUnsignedId($id))
		{
			if (!$this->object)
				$this->object = new $this->className($id);
			if (Validate::isLoadedObject($this->object))
				return $this->object;
			// throw exception
			$this->errors[] = Tools::displayError('The object cannot be loaded (or found)');
			return false;
		}
		elseif ($opt)
		{
			if (!$this->object)
				$this->object = new $this->className();
			return $this->object;
		}
		else
		{
			$this->errors[] = Tools::displayError('The object cannot be loaded (the dentifier is missing or invalid)');
			return false;
		}
	}

	/**
	 * Check if the token is valid, else display a warning page
	 */
	public function checkAccess()
	{
		if (!$this->checkToken())
		{
			// If this is an XSS attempt, then we should only display a simple, secure page
			// ${1} in the replacement string of the regexp is required,
			// because the token may begin with a number and mix up with it (e.g. $17)
			$url = preg_replace('/([&?]token=)[^&]*(&.*)?$/', '${1}'.$this->token.'$2', $_SERVER['REQUEST_URI']);
			if (false === strpos($url, '?token=') && false === strpos($url, '&token='))
				$url .= '&token='.$this->token;
			if (strpos($url, '?') === false)
				$url = str_replace('&token', '?controller=AdminDashboard&token', $url);

			$this->context->smarty->assign('url', htmlentities($url));
			return false;
		}
		return true;
	}

	protected function filterToField($key, $filter)
	{
		if (!isset($this->fields_list))
			return false;

		foreach ($this->fields_list as $field)
			if (array_key_exists('filter_key', $field) && $field['filter_key'] == $key)
				return $field;
		if (array_key_exists($filter, $this->fields_list))
			return $this->fields_list[$filter];
		return false;
	}

	public function displayNoSmarty()
	{
	}

	public function displayAjax()
	{
		if (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
			$requestJson = true;

		if ($this->json || (isset($requestJson) && $requestJson))
		{
			$this->context->smarty->assign(array(
				'json' => true,
				'status' => $this->status,
			));
		}
		$this->layout = 'layout-ajax.tpl';
		$this->display_header = false;
		$this->display_footer = false;
		return $this->display();
	}

	protected function redirect()
	{
		header('Location: '.$this->redirect_after);
		exit;
	}

	public function display()
	{
		$this->context->smarty->assign(array(
			'display_header' => $this->display_header,
			'display_footer' => $this->display_footer,
		));

		// Use page title from meta_title if it has been set else from the breadcrumbs array
		if (!$this->meta_title)
			$this->meta_title = strip_tags(is_array($this->toolbar_title) ? implode(' '.Configuration::get('PS_NAVIGATION_PIPE').' ', $this->toolbar_title) : $this->toolbar_title);
		$this->context->smarty->assign('meta_title', $this->meta_title);

		$template_dirs = $this->context->smarty->getTemplateDir();
		
		// Check if header/footer have been overriden
		$dir = $this->context->smarty->getTemplateDir(0).'controllers'.DIRECTORY_SEPARATOR.trim($this->override_folder, '\\/').DIRECTORY_SEPARATOR;
		$module_list_dir = $this->context->smarty->getTemplateDir(0).'helpers'.DIRECTORY_SEPARATOR.'modules_list'.DIRECTORY_SEPARATOR;

		$header_tpl = file_exists($dir.'header.tpl') ? $dir.'header.tpl' : 'header.tpl';
		$page_header_toolbar = file_exists($dir.'page_header_toolbar.tpl') ? $dir.'page_header_toolbar.tpl' : 'page_header_toolbar.tpl';
		$footer_tpl = file_exists($dir.'footer.tpl') ? $dir.'footer.tpl' : 'footer.tpl';
		$modal_module_list = file_exists($module_list_dir.'modal.tpl') ? $module_list_dir.'modal.tpl' : 'modal.tpl';
		$tpl_action = $this->tpl_folder.$this->display.'.tpl';

		// Check if action template has been overriden
		foreach ($template_dirs as $template_dir)
			if (file_exists($template_dir.DIRECTORY_SEPARATOR.$tpl_action) && $this->display != 'view' && $this->display != 'options')
			{
				if (method_exists($this, $this->display.Tools::toCamelCase($this->className)))
					$this->{$this->display.Tools::toCamelCase($this->className)}();
				$this->context->smarty->assign('content', $this->context->smarty->fetch($tpl_action));
				break;
			}

		if (!$this->ajax)
		{
			$template = $this->createTemplate($this->template);
			$page = $template->fetch();
		}
		else
			$page = $this->content;

		if ($conf = Tools::getValue('conf'))
			$this->context->smarty->assign('conf', $this->json ? Tools::jsonEncode($this->_conf[(int)$conf]) : $this->_conf[(int)$conf]);

		foreach (array('errors', 'warnings', 'informations', 'confirmations') as $type)
		{
			if (!is_array($this->$type))
				$this->$type = (array)$this->$type;
			$this->context->smarty->assign($type, $this->json ? Tools::jsonEncode(array_unique($this->$type)) : array_unique($this->$type));
		}

		if ($this->show_page_header_toolbar && !$this->lite_display)
			$this->context->smarty->assign(array(
				'page_header_toolbar' => $this->context->smarty->fetch($page_header_toolbar),
				'modal_module_list' => $this->context->smarty->fetch($modal_module_list),
				)
			);

		$this->context->smarty->assign(array(
			'page' =>  $this->json ? Tools::jsonEncode($page) : $page,
			'header' => $this->context->smarty->fetch($header_tpl),
			'footer' => $this->context->smarty->fetch($footer_tpl),
			)
		);
			
		$this->smartyOutputContent($this->layout);
	}

	/**
	 * add a warning message to display at the top of the page
	 *
	 * @param string $msg
	 */
	protected function displayWarning($msg)
	{
		$this->warnings[] = $msg;
	}

	/**
	 * add a info message to display at the top of the page
	 *
	 * @param string $msg
	 */
	protected function displayInformation($msg)
	{
		$this->informations[] = $msg;
	}

	/**
	 * Assign smarty variables for the header
	 */
	public function initHeader()
	{
		// Multishop
		$is_multishop = Shop::isFeatureActive();

		// Quick access
		$quick_access = QuickAccess::getQuickAccesses($this->context->language->id);
		foreach ($quick_access as $index => $quick)
		{
			if ($quick['link'] == '../' && Shop::getContext() == Shop::CONTEXT_SHOP)
			{
				$url = $this->context->shop->getBaseURL();
				if (!$url)
				{
					unset($quick_access[$index]);
					continue;
				}
				$quick_access[$index]['link'] = $url;
			}
			else
			{
				preg_match('/controller=(.+)(&.+)?$/', $quick['link'], $admin_tab);
				if (isset($admin_tab[1]))
				{
					if (strpos($admin_tab[1], '&'))
						$admin_tab[1] = substr($admin_tab[1], 0, strpos($admin_tab[1], '&'));

					$token = Tools::getAdminToken($admin_tab[1].(int)Tab::getIdFromClassName($admin_tab[1]).(int)$this->context->employee->id);
					$quick_access[$index]['link'] .= '&token='.$token;
				}
			}
		}

		// Tab list
		$tabs = Tab::getTabs($this->context->language->id, 0);
		$current_id = Tab::getCurrentParentId();
		foreach ($tabs as $index => $tab)
		{
			if (!checkTabRights($tab['id_tab'])
				|| ($tab['class_name'] == 'AdminStock' && Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') == 0)
				|| $tab['class_name'] == 'AdminCarrierWizard')
			{
				unset($tabs[$index]);
				continue;
			}

			$img_cache_url = 'themes/'.$this->context->employee->bo_theme.'/img/t/'.$tab['class_name'].'.png';
			$img_exists_cache = Tools::file_exists_cache(_PS_ADMIN_DIR_.$img_cache_url);
			// retrocompatibility : change png to gif if icon not exists
			if (!$img_exists_cache)
				$img_exists_cache = Tools::file_exists_cache(_PS_ADMIN_DIR_.str_replace('.png', '.gif', $img_cache_url));

			if ($img_exists_cache)
				$path_img = $img = $img_exists_cache;
			else
			{
				$path_img = _PS_IMG_DIR_.'t/'.$tab['class_name'].'.png';
				// Relative link will always work, whatever the base uri set in the admin
				$img = '../img/t/'.$tab['class_name'].'.png';
			}

			if (trim($tab['module']) != '')
			{
				$path_img = _PS_MODULE_DIR_.$tab['module'].'/'.$tab['class_name'].'.png';
				// Relative link will always work, whatever the base uri set in the admin
				$img = '../modules/'.$tab['module'].'/'.$tab['class_name'].'.png';
			}

			// retrocompatibility
			if (!file_exists($path_img))
				$img = str_replace('png', 'gif', $img);
			// tab[class_name] does not contains the "Controller" suffix
			$tabs[$index]['current'] = ($tab['class_name'].'Controller' == get_class($this)) || ($current_id == $tab['id_tab']);
			$tabs[$index]['img'] = $img;
			$tabs[$index]['href'] = $this->context->link->getAdminLink($tab['class_name']);

			$sub_tabs = Tab::getTabs($this->context->language->id, $tab['id_tab']);
			foreach ($sub_tabs as $index2 => $sub_tab)
			{	
				//check if module is enable and 
				if (isset($sub_tab['module']) && !empty($sub_tab['module']))
				{
					$module = Module::getInstanceByName($sub_tab['module']);
					if (is_object($module) && !$module->isEnabledForShopContext())
					{
						unset($sub_tabs[$index2]);
						continue;
					}
				}
				
				if (Tab::checkTabRights($sub_tab['id_tab']) === true && (bool)$sub_tab['active'] && $sub_tab['class_name'] != 'AdminCarrierWizard') 
				// class_name is the name of the class controller				
				{
					$sub_tabs[$index2]['href'] = $this->context->link->getAdminLink($sub_tab['class_name']);
					$sub_tabs[$index2]['current'] = ($sub_tab['class_name'].'Controller' == get_class($this) || $sub_tab['class_name'] == Tools::getValue('controller'));
				}
				elseif ($sub_tab['class_name'] == 'AdminCarrierWizard' && $sub_tab['class_name'].'Controller' == get_class($this))
				{
					foreach ($sub_tabs as $i => $tab) {
						if($tab['class_name'] == 'AdminCarriers')
							break;
					}
					$sub_tabs[$i]['current'] = true;
					unset($sub_tabs[$index2]);
				}
				else
					unset($sub_tabs[$index2]);
			}

			$tabs[$index]['sub_tabs'] = $sub_tabs;
		}

		if (Validate::isLoadedObject($this->context->employee))
		{
			$accesses = Profile::getProfileAccesses($this->context->employee->id_profile, 'class_name');
			/* Hooks are volontary out the initialize array (need those variables already assigned) */
			$bo_color = empty($this->context->employee->bo_color) ? '#FFFFFF' : $this->context->employee->bo_color;
			$this->context->smarty->assign(array(
				'autorefresh_notifications' => Configuration::get('PS_ADMINREFRESH_NOTIFICATION'),
				'help_box' => Configuration::get('PS_HELPBOX'),
				'round_mode' => Configuration::get('PS_PRICE_ROUND_MODE'),
				'brightness' => Tools::getBrightness($bo_color) < 128 ? 'white' : '#383838',
				'bo_width' => (int)$this->context->employee->bo_width,
				'bo_color' => isset($this->context->employee->bo_color) ? Tools::htmlentitiesUTF8($this->context->employee->bo_color) : null,
				'show_new_orders' => Configuration::get('PS_SHOW_NEW_ORDERS') && isset($accesses['AdminOrders']) && $accesses['AdminOrders']['view'],
				'show_new_customers' => Configuration::get('PS_SHOW_NEW_CUSTOMERS') && isset($accesses['AdminCustomers']) && $accesses['AdminCustomers']['view'],
				'show_new_messages' => Configuration::get('PS_SHOW_NEW_MESSAGES') && isset($accesses['AdminCustomerThreads'])&& $accesses['AdminCustomerThreads']['view'],
				'employee' => $this->context->employee,
				'search_type' => Tools::getValue('bo_search_type'),
				'bo_query' => Tools::safeOutput(Tools::stripslashes(Tools::getValue('bo_query'))),
				'quick_access' => $quick_access,
				'multi_shop' => Shop::isFeatureActive(),
				'shop_list' => Helper::renderShopList(),
				'shop' => $this->context->shop,
				'shop_group' => new ShopGroup((int)Shop::getContextShopGroupID()),
				'current_parent_id' => (int)Tab::getCurrentParentId(),
				'tabs' => $tabs,
				'is_multishop' => $is_multishop,
				'multishop_context' => $this->multishop_context,
				'default_tab_link' => $this->context->link->getAdminLink(Tab::getClassNameById((int)Context::getContext()->employee->default_tab)),
				'collapse_menu' => isset($this->context->cookie->collapse_menu) ? (int)$this->context->cookie->collapse_menu : 0
			));
		}
		else
			$this->context->smarty->assign('default_tab_link', $this->context->link->getAdminLink('AdminDashboard'));

		$this->context->smarty->assign(array(
			'img_dir' => _PS_IMG_,
			'iso' => $this->context->language->iso_code,
			'class_name' => $this->className,
			'iso_user' => $this->context->language->iso_code,
			'country_iso_code' => $this->context->country->iso_code,
			'version' => _PS_VERSION_,
			'lang_iso' => $this->context->language->iso_code,
			'full_language_code' => $this->context->language->language_code,
			'link' => $this->context->link,
			'shop_name' => Configuration::get('PS_SHOP_NAME'),
			'base_url' => $this->context->shop->getBaseURL(),
			'tab' => isset($tab) ? $tab : null, // Deprecated, this tab is declared in the foreach, so it's the last tab in the foreach
			'current_parent_id' => (int)Tab::getCurrentParentId(),
			'tabs' => $tabs,
			'install_dir_exists' => file_exists(_PS_ADMIN_DIR_.'/../install'),
			'pic_dir' => _THEME_PROD_PIC_DIR_,
			'controller_name' => htmlentities(Tools::getValue('controller')),
			'currentIndex' => self::$currentIndex,
			'bootstrap' => $this->bootstrap,
			'default_language' => (int)Configuration::get('PS_LANG_DEFAULT')
		));

		$module = Module::getInstanceByName('themeconfigurator');
		$lang = '';
		if (Configuration::get('PS_REWRITING_SETTINGS') && count(Language::getLanguages(true)) > 1)
			$lang = Language::getIsoById($this->context->employee->id_lang).'/';
		if (is_object($module) && (int)Configuration::get('PS_TC_ACTIVE') == 1 && $this->context->shop->getBaseURL())
			$this->context->smarty->assign('base_url_tc', $this->context->shop->getBaseUrl()
				.(Configuration::get('PS_REWRITING_SETTINGS') ? '' : 'index.php')
				.$lang
				.'?live_configurator_token='.$module->getLiveConfiguratorToken()
				.'&id_employee='.(int)$this->context->employee->id
				.'&id_shop='.(int)$this->context->shop->id
				.(Configuration::get('PS_TC_THEME') != '' ? '&theme='.Configuration::get('PS_TC_THEME') : '')
				.(Configuration::get('PS_TC_FONT') != '' ? '&theme_font='.Configuration::get('PS_TC_FONT') : ''));
	}

	/**
	 * Declare an action to use for each row in the list
	 */
	public function addRowAction($action)
	{
		$action = strtolower($action);
		$this->actions[] = $action;
	}

	/**
	 * Add  an action to use for each row in the list
	 */
	public function addRowActionSkipList($action, $list)
	{
		$action = strtolower($action);
		$list = (array)$list;

		if (array_key_exists($action, $this->list_skip_actions))
			$this->list_skip_actions[$action] = array_merge($this->list_skip_actions[$action], $list);
		else
			$this->list_skip_actions[$action] = $list;
	}

	/**
	 * Assign smarty variables for all default views, list and form, then call other init functions
	 */
	public function initContent()
	{
		if (!$this->viewAccess())
		{
			$this->errors[] = Tools::displayError('You do not have permission to view this.');
			return;
		}

		$this->getLanguages();
		$this->initToolbar();
		$this->initTabModuleList();
		$this->initPageHeaderToolbar();

		if ($this->display == 'edit' || $this->display == 'add')
		{
			if (!$this->loadObject(true))
				return;

			$this->content .= $this->renderForm();
		}
		elseif ($this->display == 'view')
		{
			// Some controllers use the view action without an object
			if ($this->className)
				$this->loadObject(true);
			$this->content .= $this->renderView();
		}
		elseif ($this->display == 'details')
		{
			$this->content .= $this->renderDetails();
		}
		elseif (!$this->ajax)
		{
			$this->content .= $this->renderModulesList();
			$this->content .= $this->renderKpis();
			$this->content .= $this->renderList();
			$this->content .= $this->renderOptions();

			// if we have to display the required fields form
			if ($this->required_database)
				$this->content .= $this->displayRequiredFields();
		}

		$this->context->smarty->assign(array(
			'content' => $this->content,
			'lite_display' => $this->lite_display,
			'url_post' => self::$currentIndex.'&token='.$this->token,
			'show_page_header_toolbar' => $this->show_page_header_toolbar,
			'page_header_toolbar_title' => $this->page_header_toolbar_title,
			'title' => $this->page_header_toolbar_title,
			'toolbar_btn' => $this->page_header_toolbar_btn,
			'page_header_toolbar_btn' => $this->page_header_toolbar_btn
		));
	}
	
	/**
	 * init tab modules list and add button in toolbar
	 */
	protected function initTabModuleList()
	{
		if (!$this->isFresh(Module::CACHE_FILE_MUST_HAVE_MODULES_LIST, 86400))
			@file_put_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST, Tools::addonsRequest('must-have'));
		if (!$this->isFresh(Module::CACHE_FILE_TAB_MODULES_LIST, 604800))
			$this->refresh(Module::CACHE_FILE_TAB_MODULES_LIST, 'http://'.Tab::TAB_MODULE_LIST_URL);
		
		$this->tab_modules_list = Tab::getTabModulesList($this->id);

		if (is_array($this->tab_modules_list['default_list']) && count($this->tab_modules_list['default_list']))
			$this->filter_modules_list = $this->tab_modules_list['default_list'];	
		elseif (is_array($this->tab_modules_list['slider_list']) && count($this->tab_modules_list['slider_list']))
		{
			$this->addToolBarModulesListButton();
			$this->addPageHeaderToolBarModulesListButton();
			$this->context->smarty->assign(array(
				'tab_modules_list' => implode(',', $this->tab_modules_list['slider_list']),
				'admin_module_ajax_url' => $this->context->link->getAdminLink('AdminModules'),
				'back_tab_modules_list' => $this->context->link->getAdminLink(Tools::getValue('controller')),
				'tab_modules_open' => (int)Tools::getValue('tab_modules_open')
			));
		}

	}

	protected function addPageHeaderToolBarModulesListButton()
	{
		$this->filterTabModuleList();
		
		if (is_array($this->tab_modules_list['slider_list']) && count($this->tab_modules_list['slider_list']))
			$this->page_header_toolbar_btn['modules-list'] = array(
				'href' => '#',
				'desc' => $this->l('Recommended Modules')
			);
	}
	
	protected function addToolBarModulesListButton()
	{
		$this->filterTabModuleList();
			
		if (is_array($this->tab_modules_list['slider_list']) && count($this->tab_modules_list['slider_list']))
			$this->toolbar_btn['modules-list'] = array(
				'href' => '#',
				'desc' => $this->l('Recommended Modules')
			);
	}
	
	protected function filterTabModuleList()
	{
		if (!$this->isFresh(Module::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST, 86400))
			file_put_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST, Tools::addonsRequest('native'));
		
		if (!$this->isFresh(Module::CACHE_FILE_MUST_HAVE_MODULES_LIST, 86400))
			@file_put_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST, Tools::addonsRequest('must-have'));
		
		libxml_use_internal_errors(true);
		
		$country_module_list = file_get_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST);
		$must_have_module_list = file_get_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST);
		$all_module_list = array();
		
		if (!empty($country_module_list) && $country_module_list_xml = simplexml_load_string($country_module_list))
		{			
			$country_module_list_array = array();
			if (is_object($country_module_list_xml->module))
				foreach ($country_module_list_xml->module as $k => $m)
					$all_module_list[] = (string)$m->name;
		}
		
		if (!empty($must_have_module_list) && $must_have_module_list_xml = simplexml_load_string($must_have_module_list))
		{			
			$must_have_module_list_array = array();
			if (is_object($country_module_list_xml->module))
				foreach ($must_have_module_list_xml->module as $l => $mo)
					$all_module_list[] = (string)$mo->name;
		}

		$this->tab_modules_list['slider_list'] = array_intersect($this->tab_modules_list['slider_list'], $all_module_list);
	}

	/**
	 * initialize the invalid doom page of death
	 *
	 * @return void
	 */
	public function initCursedPage()
	{
		$this->layout = 'invalid_token.tpl';
	}

	/**
	 * Assign smarty variables for the footer
	 */
	public function initFooter()
	{
		//RTL Support
		//rtl.js overrides inline styles
		//iso_code.css overrides default fonts for every language (optional)
		if ($this->context->language->is_rtl)
		{
			$this->addJS(_PS_JS_DIR_.'rtl.js');
			$this->addCSS(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/css/'.$this->context->language->iso_code.'.css', 'all', false);
		}

		// We assign js and css files on the last step before display template, because controller can add many js and css files
		$this->context->smarty->assign('css_files', $this->css_files);
		$this->context->smarty->assign('js_files', array_unique($this->js_files));

		$this->context->smarty->assign(array(
			'ps_version' => _PS_VERSION_,
			'timer_start' => $this->timer_start,
			'iso_is_fr' => strtoupper($this->context->language->iso_code) == 'FR',
		));
	}
	
	public function renderModulesList()
	{
		// Load cache file modules list (natives and partners modules)
		$xmlModules = false;
		if (file_exists(_PS_ROOT_DIR_.Module::CACHE_FILE_MODULES_LIST))
			$xmlModules = @simplexml_load_file(_PS_ROOT_DIR_.Module::CACHE_FILE_MODULES_LIST);
		if ($xmlModules)
			foreach ($xmlModules->children() as $xmlModule)
				foreach ($xmlModule->children() as $module)
					foreach ($module->attributes() as $key => $value)
					{
						if ($xmlModule->attributes() == 'native' && $key == 'name')
							$this->list_natives_modules[] = (string)$value;
						if ($xmlModule->attributes() == 'partner' && $key == 'name')
							$this->list_partners_modules[] = (string)$value;
					}

		if ($this->getModulesList($this->filter_modules_list))
		{
			foreach ($this->modules_list as $key => $module)
			{
				if (in_array($module->name, $this->list_partners_modules))
					$this->modules_list[$key]->type = 'addonsPartner';
				if (isset($module->description_full) && trim($module->description_full) != '')
					$module->show_quick_view = true;
			}
			$helper = new Helper();
			return $helper->renderModulesList($this->modules_list);
		}
	}
	
	
	/**
	 * Function used to render the list to display for this controller
	 */
	public function renderList()
	{
		if (!($this->fields_list && is_array($this->fields_list)))
			return false;
		$this->getList($this->context->language->id);

		// If list has 'active' field, we automatically create bulk action
		if (isset($this->fields_list) && is_array($this->fields_list) && array_key_exists('active', $this->fields_list)
			&& !empty($this->fields_list['active']))
		{
			if (!is_array($this->bulk_actions))
				$this->bulk_actions = array();

			$this->bulk_actions = array_merge(array(
				'enableSelection' => array(
					'text' => $this->l('Enable selection'),
					'icon' => 'icon-power-off text-success'
				),
				'disableSelection' => array(
					'text' => $this->l('Disable selection'),
					'icon' => 'icon-power-off text-danger'
				),
				'divider' => array(
					'text' => 'divider'
				)
			), $this->bulk_actions);
		}

		$helper = new HelperList();
		
		// Empty list is ok
		if (!is_array($this->_list))
		{
			$this->displayWarning($this->l('Bad SQL query', 'Helper').'<br />'.htmlspecialchars($this->_list_error));
			return false;
		}

		$this->setHelperDisplay($helper);
		$helper->tpl_vars = $this->tpl_list_vars;
		$helper->tpl_delete_link_vars = $this->tpl_delete_link_vars;

		// For compatibility reasons, we have to check standard actions in class attributes
		foreach ($this->actions_available as $action)
		{
			if (!in_array($action, $this->actions) && isset($this->$action) && $this->$action)
				$this->actions[] = $action;
		}
		$helper->is_cms = $this->is_cms;
		$list = $helper->generateList($this->_list, $this->fields_list);

		return $list;
	}

	/**
	 * Override to render the view page
	 */
	public function renderView()
	{
		$helper = new HelperView($this);
		$this->setHelperDisplay($helper);
		$helper->tpl_vars = $this->tpl_view_vars;
		if (!is_null($this->base_tpl_view))
			$helper->base_tpl = $this->base_tpl_view;
		$view = $helper->generateView();

		return $view;
	}

	/**
	 * Override to render the view page
	 */
	public function renderDetails()
	{
		return $this->renderList();
	}

	/**
	 * Function used to render the form for this controller
	 */
	public function renderForm()
	{
		if (!$this->default_form_language)
			$this->getLanguages();

		if (Tools::getValue('submitFormAjax'))
			$this->content .= $this->context->smarty->fetch('form_submit_ajax.tpl');

		if ($this->fields_form && is_array($this->fields_form))
		{
			if (!$this->multiple_fieldsets)
				$this->fields_form = array(array('form' => $this->fields_form));

			// For add a fields via an override of $fields_form, use $fields_form_override
			if (is_array($this->fields_form_override) && !empty($this->fields_form_override))
				$this->fields_form[0]['form']['input'] = array_merge($this->fields_form[0]['form']['input'], $this->fields_form_override);

			$fields_value = $this->getFieldsValue($this->object);

			Hook::exec('action'.$this->controller_name.'FormModifier', array(
				'fields' => &$this->fields_form,
				'fields_value' => &$fields_value,
				'form_vars' => &$this->tpl_form_vars,
			));

			$helper = new HelperForm($this);
			$this->setHelperDisplay($helper);
			$helper->fields_value = $fields_value;
			$helper->submit_action = $this->submit_action;
			$helper->tpl_vars = $this->tpl_form_vars;
			$helper->show_cancel_button = (isset($this->show_form_cancel_button)) ? $this->show_form_cancel_button : ($this->display == 'add' || $this->display == 'edit');

			$back = Tools::safeOutput(Tools::getValue('back', ''));
			if (empty($back))
				$back = self::$currentIndex.'&token='.$this->token;
			if (!Validate::isCleanHtml($back))
				die(Tools::displayError());

			$helper->back_url = $back;
			!is_null($this->base_tpl_form) ? $helper->base_tpl = $this->base_tpl_form : '';
			if ($this->tabAccess['view'])
			{
				if (Tools::getValue('back'))
					$helper->tpl_vars['back'] = Tools::safeOutput(Tools::getValue('back'));
				else
					$helper->tpl_vars['back'] = Tools::safeOutput(Tools::getValue(self::$currentIndex.'&token='.$this->token));
			}
			$form = $helper->generateForm($this->fields_form);

			return $form;
		}
	}
	
	public function renderKpis()
	{
	}

	/**
	 * Function used to render the options for this controller
	 */
	public function renderOptions()
	{
		Hook::exec('action'.$this->controller_name.'OptionsModifier', array(
			'options' => &$this->fields_options,
			'option_vars' => &$this->tpl_option_vars,
		));

		if ($this->fields_options && is_array($this->fields_options))
		{
			if (isset($this->display) && $this->display != 'options' && $this->display != 'list')
				$this->show_toolbar = false;
			else
				$this->display = 'options';

			unset($this->toolbar_btn);
			$this->initToolbar();
			$helper = new HelperOptions($this);
			$this->setHelperDisplay($helper);
			$helper->id = $this->id;
			$helper->tpl_vars = $this->tpl_option_vars;
			$options = $helper->generateOptions($this->fields_options);

			return $options;
		}
	}

	/**
	 * this function set various display option for helper list
	 *
	 * @param Helper $helper
	 * @return void
	 */
	public function setHelperDisplay(Helper $helper)
	{
		if (empty($this->toolbar_title))
			$this->initToolbarTitle();
		// tocheck
		if ($this->object && $this->object->id)
			$helper->id = $this->object->id;

		// @todo : move that in Helper
		$helper->title = is_array($this->toolbar_title) ? implode(' '.Configuration::get('PS_NAVIGATION_PIPE').' ', $this->toolbar_title) : $this->toolbar_title;
		$helper->toolbar_btn = $this->toolbar_btn;
		$helper->show_toolbar = $this->show_toolbar;
		$helper->toolbar_scroll = $this->toolbar_scroll;
		$helper->override_folder = $this->tpl_folder;
		$helper->actions = $this->actions;
		$helper->simple_header = $this->list_simple_header;
		$helper->bulk_actions = $this->bulk_actions;
		$helper->currentIndex = self::$currentIndex;
		$helper->className = $this->className;
		$helper->table = $this->table;
		$helper->name_controller = Tools::getValue('controller');
		$helper->orderBy = $this->_orderBy;
		$helper->orderWay = $this->_orderWay;
		$helper->listTotal = $this->_listTotal;
		$helper->shopLink = $this->shopLink;
		$helper->shopLinkType = $this->shopLinkType;
		$helper->identifier = $this->identifier;
		$helper->token = $this->token;
		$helper->languages = $this->_languages;
		$helper->specificConfirmDelete = $this->specificConfirmDelete;
		$helper->imageType = $this->imageType;
		$helper->no_link = $this->list_no_link;
		$helper->colorOnBackground = $this->colorOnBackground;
		$helper->ajax_params = (isset($this->ajax_params) ? $this->ajax_params : null);
		$helper->default_form_language = $this->default_form_language;
		$helper->allow_employee_form_lang = $this->allow_employee_form_lang;
		$helper->multiple_fieldsets = $this->multiple_fieldsets;
		$helper->row_hover = $this->row_hover;
		$helper->position_identifier = $this->position_identifier;
		$helper->position_group_identifier = $this->position_group_identifier;
		$helper->controller_name = $this->controller_name;
		$helper->list_id = isset($this->list_id) ? $this->list_id : $this->table;
		$helper->bootstrap = $this->bootstrap;

		// For each action, try to add the corresponding skip elements list
		$helper->list_skip_actions = $this->list_skip_actions;
		
		$this->helper = $helper;
	}
	
	public function setDeprecatedMedia()
	{
		//$this->addCSS(__PS_BASE_URI__.$admin_webpath.'/themes/'.$this->bo_theme.'/css/backward-admin-old.css', 'all', 1);
		$this->addCSS(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/css/backward-admin-bootstrap-reset.css', 'all', 2);
		
	}

	public function setMedia()
	{
		//Bootstrap + Specific Admin Theme
		$this->addCSS(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/css/'.$this->bo_css, 'all', 0);

		//Deprecated stylesheets + reset bootstrap style for the #nobootstrap field - Backward compatibility
		if (!$this->bootstrap)
			$this->setDeprecatedMedia();
		
		//RTL Support moved to footer

		$this->addJquery();
		$this->addjQueryPlugin(array('scrollTo', 'alerts', 'chosen', 'autosize'));
		$this->addjQueryPlugin('growl', null, false);
		$this->addJqueryUI(array('ui.slider', 'ui.datepicker'));

		$this->addJS(array(
			_PS_JS_DIR_.'admin.js',
			_PS_JS_DIR_.'tools.js',
			_PS_JS_DIR_.'jquery/plugins/timepicker/jquery-ui-timepicker-addon.js'
		));

		//loads specific javascripts for the admin theme
		$this->addJS(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/js/vendor/bootstrap.min.js');
		$this->addJS(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/js/vendor/modernizr.min.js');
		$this->addJS(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/js/modernizr-loads.js');
		$this->addJS(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/js/vendor/moment-with-langs.min.js');

		if (!Tools::getValue('submitFormAjax'))
			$this->addJs(_PS_JS_DIR_.'notifications.js');

		// Execute Hook AdminController SetMedia
		Hook::exec('actionAdminControllerSetMedia');
	}

	/**
	 * non-static method which uses AdminController::translate()
	 *
	 * @param mixed $string term or expression in english
	 * @param string $class name of the class
	 * @param boolan $addslashes if set to true, the return value will pass through addslashes(). Otherwise, stripslashes().
	 * @param boolean $htmlentities if set to true(default), the return value will pass through htmlentities($string, ENT_QUOTES, 'utf-8')
	 * @return string the translation if available, or the english default text.
	 */
	protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
	{
		if ($class === null || $class == 'AdminTab')
			$class = substr(get_class($this), 0, -10);
		// classname has changed, from AdminXXX to AdminXXXController, so we remove 10 characters and we keep same keys
		elseif (strtolower(substr($class, -10)) == 'controller')
			$class = substr($class, 0, -10);
		return Translate::getAdminTranslation($string, $class, $addslashes, $htmlentities);
	}

	/**
	 * Init context and dependencies, handles POST and GET
	 */
	public function init()
	{
		// Has to be removed for the next Prestashop version
		global $currentIndex;

		parent::init();

		if (Tools::getValue('ajax'))
			$this->ajax = '1';

		/* Server Params */
		$protocol_link = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
		$protocol_content = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';

		$this->context->link = new Link($protocol_link, $protocol_content);

		if (isset($_GET['logout']))
			$this->context->employee->logout();
			
		if (isset(Context::getContext()->cookie->last_activity))
		{
			if ($this->context->cookie->last_activity + 900 < time())
				$this->context->employee->logout();
			else
				$this->context->cookie->last_activity = time();
		}

		if ($this->controller_name != 'AdminLogin' && (!isset($this->context->employee) || !$this->context->employee->isLoggedBack()))
		{
			if (isset($this->context->employee))
				$this->context->employee->logout();
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminLogin').((!isset($_GET['logout']) && $this->controller_name != 'AdminNotFound') ? '&redirect='.$this->controller_name : ''));
		}

		// Set current index
		$current_index = 'index.php'.(($controller = Tools::getValue('controller')) ? '?controller='.$controller : '');
		if ($back = Tools::getValue('back'))
			$current_index .= '&back='.urlencode($back);
		self::$currentIndex = $current_index;
		$currentIndex = $current_index;

		if ((int)Tools::getValue('liteDisplaying'))
		{
			$this->display_header = false;
			$this->display_footer = false;
			$this->content_only = false;
			$this->lite_display = true;
		}

		if ($this->ajax && method_exists($this, 'ajaxPreprocess'))
			$this->ajaxPreProcess();

		$this->context->smarty->assign(array(
			'table' => $this->table,
			'current' => self::$currentIndex,
			'token' => $this->token,
			'host_mode' => defined('_PS_HOST_MODE_') ? 1 : 0,
			'stock_management' => (int)Configuration::get('PS_STOCK_MANAGEMENT')
		));

		if ($this->display_header)
			$this->context->smarty->assign('displayBackOfficeHeader', Hook::exec('displayBackOfficeHeader', array()));
		
		$this->context->smarty->assign(array(
			'displayBackOfficeTop' => Hook::exec('displayBackOfficeTop', array()),
			'submit_form_ajax' => (int)Tools::getValue('submitFormAjax')
		));

		$this->initProcess();
		$this->initBreadcrumbs();
	}

	public function initShopContext()
	{
		if (!$this->context->employee->isLoggedBack())
			return;

		// Change shop context ?
		if (Shop::isFeatureActive() && Tools::getValue('setShopContext') !== false)
		{
			$this->context->cookie->shopContext = Tools::getValue('setShopContext');
			$url = parse_url($_SERVER['REQUEST_URI']);
			$query = (isset($url['query'])) ? $url['query'] : '';
			parse_str($query, $parse_query);
			unset($parse_query['setShopContext'], $parse_query['conf']);
			$this->redirect_after = $url['path'].'?'.http_build_query($parse_query, '', '&');
		}
		elseif (!Shop::isFeatureActive())
			$this->context->cookie->shopContext = 's-'.Configuration::get('PS_SHOP_DEFAULT');
		else if (Shop::getTotalShops(false, null) < 2)
			$this->context->cookie->shopContext = 's-'.$this->context->employee->getDefaultShopID();

		$shop_id = '';
		Shop::setContext(Shop::CONTEXT_ALL);
		if ($this->context->cookie->shopContext)
		{
			$split = explode('-', $this->context->cookie->shopContext);
			if (count($split) == 2)
			{
				if ($split[0] == 'g')
				{
					if ($this->context->employee->hasAuthOnShopGroup($split[1]))
						Shop::setContext(Shop::CONTEXT_GROUP, $split[1]);
					else
					{
						$shop_id = $this->context->employee->getDefaultShopID();
						Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
					}
				}
				else if (Shop::getShop($split[1]) && $this->context->employee->hasAuthOnShop($split[1]))
				{
					$shop_id = $split[1];
					Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
				}
				else
				{
					$shop_id = $this->context->employee->getDefaultShopID();
					Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
				}
			}
		}

		// Check multishop context and set right context if need
		if (!($this->multishop_context & Shop::getContext()))
		{
			if (Shop::getContext() == Shop::CONTEXT_SHOP && !($this->multishop_context & Shop::CONTEXT_SHOP))
				Shop::setContext(Shop::CONTEXT_GROUP, Shop::getContextShopGroupID());
			if (Shop::getContext() == Shop::CONTEXT_GROUP && !($this->multishop_context & Shop::CONTEXT_GROUP))
				Shop::setContext(Shop::CONTEXT_ALL);
		}

		// Replace existing shop if necessary
		if (!$shop_id)
			$this->context->shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
		elseif ($this->context->shop->id != $shop_id)
			$this->context->shop = new Shop($shop_id);
		
		// Replace current default country		
		$this->context->country = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
	}

	/**
	 * Retrieve GET and POST value and translate them to actions
	 */
	public function initProcess()
	{
		if (!isset($this->list_id))
			$this->list_id = $this->table;

		// Manage list filtering
		if (Tools::isSubmit('submitFilter'.$this->list_id) 
			|| $this->context->cookie->{'submitFilter'.$this->list_id} !== false
			|| Tools::getValue($this->list_id.'Orderby')
			|| Tools::getValue($this->list_id.'Orderway'))
			$this->filter = true;

		$this->id_object = (int)Tools::getValue($this->identifier);

		/* Delete object image */
		if (isset($_GET['deleteImage']))
		{
			if ($this->tabAccess['delete'] === '1')
				$this->action = 'delete_image';
			else
				$this->errors[] = Tools::displayError('You do not have permission to delete this.');
		}
		/* Delete object */
		elseif (isset($_GET['delete'.$this->table]))
		{
			if ($this->tabAccess['delete'] === '1')
				$this->action = 'delete';
			else
				$this->errors[] = Tools::displayError('You do not have permission to delete this.');
		}
		/* Change object statuts (active, inactive) */
		elseif ((isset($_GET['status'.$this->table]) || isset($_GET['status'])) && Tools::getValue($this->identifier))
		{
			if ($this->tabAccess['edit'] === '1')
				$this->action = 'status';
			else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}
		/* Move an object */
		elseif (isset($_GET['position']))
		{
			if ($this->tabAccess['edit'] == '1')
				$this->action = 'position';
			else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}
		elseif (Tools::isSubmit('submitAdd'.$this->table)
				 || Tools::isSubmit('submitAdd'.$this->table.'AndStay')
				 || Tools::isSubmit('submitAdd'.$this->table.'AndPreview')
				 || Tools::isSubmit('submitAdd'.$this->table.'AndBackToParent'))
		{
			// case 1: updating existing entry
			if ($this->id_object)
			{
				if ($this->tabAccess['edit'] === '1')
				{
					$this->action = 'save';
					if (Tools::isSubmit('submitAdd'.$this->table.'AndStay'))
						$this->display = 'edit';
					else
						$this->display = 'list';
				}
				else
					$this->errors[] = Tools::displayError('You do not have permission to edit this.');
			}
			// case 2: creating new entry
			else
			{
				if ($this->tabAccess['add'] === '1')
				{
					$this->action = 'save';
					if (Tools::isSubmit('submitAdd'.$this->table.'AndStay'))
						$this->display = 'edit';
					else
						$this->display = 'list';
				}
				else
					$this->errors[] = Tools::displayError('You do not have permission to add this.');
			}
		}
		elseif (isset($_GET['add'.$this->table]))
		{
			if ($this->tabAccess['add'] === '1')
			{
				$this->action = 'new';
				$this->display = 'add';
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to add this.');
		}
		elseif (isset($_GET['update'.$this->table]) && isset($_GET[$this->identifier]))
		{
			$this->display = 'edit';
			if ($this->tabAccess['edit'] !== '1')
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}
		elseif (isset($_GET['view'.$this->table]))
		{
			if ($this->tabAccess['view'] === '1')
			{
				$this->display = 'view';
				$this->action = 'view';
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to view this.');
		}
		elseif (isset($_GET['details'.$this->table]))
		{
			if ($this->tabAccess['view'] === '1')
			{
				$this->display = 'details';
				$this->action = 'details';
			}
			else
				$this->errors[] = Tools::displayError('You do not have permission to view this.');
		}
		elseif (isset($_GET['export'.$this->table]))
		{
			if ($this->tabAccess['view'] === '1')
				$this->action = 'export';
		}
		/* Cancel all filters for this tab */
		elseif (isset($_POST['submitReset'.$this->list_id]))
			$this->action = 'reset_filters';
		/* Submit options list */
		elseif (Tools::isSubmit('submitOptions'.$this->table) || Tools::isSubmit('submitOptions'))
		{
			$this->display = 'options';
			if ($this->tabAccess['edit'] === '1')
				$this->action = 'update_options';
			else
				$this->errors[] = Tools::displayError('You do not have permission to edit this.');
		}
		elseif (Tools::getValue('action') && method_exists($this, 'process'.ucfirst(Tools::toCamelCase(Tools::getValue('action')))))
			$this->action = Tools::getValue('action');
		elseif (Tools::isSubmit('submitFields') && $this->required_database && $this->tabAccess['add'] === '1' && $this->tabAccess['delete'] === '1')
			$this->action = 'update_fields';
		elseif (is_array($this->bulk_actions))
		{
			$submit_bulk_actions = array_merge(array(
				'enableSelection' => array(
					'text' => $this->l('Enable selection'),
					'icon' => 'icon-power-off text-success'
				),
				'disableSelection' => array(
					'text' => $this->l('Disable selection'),
					'icon' => 'icon-power-off text-danger'
				)
			), $this->bulk_actions);
			foreach ($submit_bulk_actions as $bulk_action => $params)
			{
				if (Tools::isSubmit('submitBulk'.$bulk_action.$this->table) || Tools::isSubmit('submitBulk'.$bulk_action))
				{
					if ($this->tabAccess['edit'] === '1')
					{
						$this->action = 'bulk'.$bulk_action;
						$this->boxes = Tools::getValue($this->table.'Box');
					}
					else
						$this->errors[] = Tools::displayError('You do not have permission to edit this.');
					break;
				}
				elseif (Tools::isSubmit('submitBulk'))
				{
					if ($this->tabAccess['edit'] === '1')
					{
						$this->action = 'bulk'.Tools::getValue('select_submitBulk');
						$this->boxes = Tools::getValue($this->table.'Box');
					}
					else
						$this->errors[] = Tools::displayError('You do not have permission to edit this.');
					break;
				}
			}
		}
		elseif (!empty($this->fields_options) && empty($this->fields_list))
			$this->display = 'options';
	}

	/**
	 * Get the current objects' list form the database
	 *
	 * @param integer $id_lang Language used for display
	 * @param string $order_by ORDER BY clause
	 * @param string $_orderWay Order way (ASC, DESC)
	 * @param integer $start Offset in LIMIT clause
	 * @param integer $limit Row count in LIMIT clause
	 */
	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
	{
		Hook::exec('action'.$this->controller_name.'ListingFieldsModifier', array(
			'select' => &$this->_select,
			'join' => &$this->_join,
			'where' => &$this->_where,
			'group_by' => &$this->_groupBy,
			'order_by' => &$this->_orderBy,
			'order_way' => &$this->_orderWay,
			'fields' => &$this->fields_list,
		));

		if (!isset($this->list_id))
			$this->list_id = $this->table;

		/* Manage default params values */
		$use_limit = true;
		if ($limit === false)
			$use_limit = false;
		elseif (empty($limit))
		{
			if (isset($this->context->cookie->{$this->list_id.'_pagination'}) && $this->context->cookie->{$this->list_id.'_pagination'})
				$limit = $this->context->cookie->{$this->list_id.'_pagination'};
			else
				$limit = $this->_default_pagination;
		}

		if (!Validate::isTableOrIdentifier($this->table))
			throw new PrestaShopException(sprintf('Table name %s is invalid:', $this->table));
		$prefix = str_replace(array('admin', 'controller'), '', Tools::strtolower(get_class($this)));
		if (empty($order_by))
		{
			if ($this->context->cookie->{$prefix.$this->list_id.'Orderby'})
				$order_by = $this->context->cookie->{$prefix.$this->list_id.'Orderby'};
			elseif ($this->_orderBy)
				$order_by = $this->_orderBy;
			else
				$order_by = $this->_defaultOrderBy;
		}

		if (empty($order_way))
		{
			if ($this->context->cookie->{$prefix.$this->list_id.'Orderway'})
				$order_way = $this->context->cookie->{$prefix.$this->list_id.'Orderway'};
			elseif ($this->_orderWay)
				$order_way = $this->_orderWay;
			else
				$order_way = $this->_defaultOrderWay;
		}

		$limit = (int)Tools::getValue($this->list_id.'_pagination', $limit);
		if (in_array($limit, $this->_pagination) && $limit != $this->_default_pagination)
			$this->context->cookie->{$this->list_id.'_pagination'} = $limit;
		else
			unset($this->context->cookie->{$this->list_id.'_pagination'});

		/* Check params validity */
		if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)
			|| !is_numeric($start) || !is_numeric($limit)
			|| !Validate::isUnsignedId($id_lang))
			throw new PrestaShopException('get list params is not valid');

		if (!isset($this->fields_list[$order_by]['order_key']) && isset($this->fields_list[$order_by]['filter_key']))
			$this->fields_list[$order_by]['order_key'] = $this->fields_list[$order_by]['filter_key'];

		if (isset($this->fields_list[$order_by]) && isset($this->fields_list[$order_by]['order_key']))
			$order_by = $this->fields_list[$order_by]['order_key'];

		/* Determine offset from current page */
		$start = 0;
		if ((int)Tools::getValue('submitFilter'.$this->list_id))
			$start = ((int)Tools::getValue('submitFilter'.$this->list_id) - 1) * $limit;
		elseif (empty($start) && isset($this->context->cookie->{$this->list_id.'_start'}) && Tools::isSubmit('export'.$this->table))
			$start = $this->context->cookie->{$this->list_id.'_start'};

		// Either save or reset the offset in the cookie
		if ($start)
			$this->context->cookie->{$this->list_id.'_start'} = $start;
		elseif (isset($this->context->cookie->{$this->list_id.'_start'}))
			unset($this->context->cookie->{$this->list_id.'_start'});

		/* Cache */
		$this->_lang = (int)$id_lang;
		$this->_orderBy = $order_by;

		if (preg_match('/[.!]/', $order_by))
		{
			$order_by_split = preg_split('/[.!]/', $order_by);
			$order_by = bqSQL($order_by_split[0]).'.`'.bqSQL($order_by_split[1]).'`';
		}
		elseif ($order_by)
			$order_by = '`'.bqSQL($order_by).'`';

		$this->_orderWay = Tools::strtoupper($order_way);

		/* SQL table : orders, but class name is Order */
		$sql_table = $this->table == 'order' ? 'orders' : $this->table;

		// Add SQL shop restriction
		$select_shop = $join_shop = $where_shop = '';
		if ($this->shopLinkType)
		{
			$select_shop = ', shop.name as shop_name ';
			$join_shop = ' LEFT JOIN '._DB_PREFIX_.$this->shopLinkType.' shop
							ON a.id_'.$this->shopLinkType.' = shop.id_'.$this->shopLinkType;
			$where_shop = Shop::addSqlRestriction($this->shopShareDatas, 'a', $this->shopLinkType);
		}

		if ($this->multishop_context && Shop::isTableAssociated($this->table) && !empty($this->className))
		{
			if (Shop::getContext() != Shop::CONTEXT_ALL || !$this->context->employee->isSuperAdmin())
			{
				$test_join = !preg_match('#`?'.preg_quote(_DB_PREFIX_.$this->table.'_shop').'`? *sa#', $this->_join);
				if (Shop::isFeatureActive() && $test_join && Shop::isTableAssociated($this->table))
				{
					$this->_where .= ' AND a.'.$this->identifier.' IN (
						SELECT sa.'.$this->identifier.'
						FROM `'._DB_PREFIX_.$this->table.'_shop` sa
						WHERE sa.id_shop IN ('.implode(', ', Shop::getContextListShopID()).')
					)';
				}
			}
		}

		/* Query in order to get results with all fields */
		$lang_join = '';
		if ($this->lang)
		{
			$lang_join = 'LEFT JOIN `'._DB_PREFIX_.$this->table.'_lang` b ON (b.`'.$this->identifier.'` = a.`'.$this->identifier.'` AND b.`id_lang` = '.(int)$id_lang;
			if ($id_lang_shop)
			{
				if (!Shop::isFeatureActive())
					$lang_join .= ' AND b.`id_shop` = 1';
				elseif (Shop::getContext() == Shop::CONTEXT_SHOP)
					$lang_join .= ' AND b.`id_shop` = '.(int)$id_lang_shop;
				else
					$lang_join .= ' AND b.`id_shop` = a.id_shop_default';
			}
			$lang_join .= ')';
		}

		$having_clause = '';
		if (isset($this->_filterHaving) || isset($this->_having))
		{
			$having_clause = ' HAVING ';
			if (isset($this->_filterHaving))
				$having_clause .= ltrim($this->_filterHaving, ' AND ');
			if (isset($this->_having))
				$having_clause .= $this->_having.' ';
		}

		$this->_listsql = '
		SELECT SQL_CALC_FOUND_ROWS
		'.($this->_tmpTableFilter ? ' * FROM (SELECT ' : '');
		
		if ($this->explicitSelect)
		{
			foreach ($this->fields_list as $key => $array_value)
			{
				// Add it only if it is not already in $this->_select
				if (isset($this->_select) && preg_match('/[\s]`?'.preg_quote($key, '/').'`?\s*,/', $this->_select))
					continue;
			
				if (isset($array_value['filter_key']))
					$this->_listsql .= str_replace('!', '.', $array_value['filter_key']).' as '.$key.',';
				elseif ($key == 'id_'.$this->table)
					$this->_listsql .= 'a.`'.bqSQL($key).'`,';
				elseif ($key != 'image' && !preg_match('/'.preg_quote($key, '/').'/i', $this->_select))
					$this->_listsql .= '`'.bqSQL($key).'`,';
			}
			$this->_listsql = rtrim($this->_listsql, ',');
		}
		else
			$this->_listsql .= ($this->lang ? 'b.*,' : '').' a.*';

		$this->_listsql .= '
		'.(isset($this->_select) ? ', '.rtrim($this->_select, ', ') : '').$select_shop.'
		FROM `'._DB_PREFIX_.$sql_table.'` a
		'.$lang_join.'
		'.(isset($this->_join) ? $this->_join.' ' : '').'
		'.$join_shop.'
		WHERE 1 '.(isset($this->_where) ? $this->_where.' ' : '').($this->deleted ? 'AND a.`deleted` = 0 ' : '').
		(isset($this->_filter) ? $this->_filter : '').$where_shop.'
		'.(isset($this->_group) ? $this->_group.' ' : '').'
		'.$having_clause.'
		ORDER BY '.((str_replace('`', '', $order_by) == $this->identifier) ? 'a.' : '').$order_by.' '.pSQL($order_way).
		($this->_tmpTableFilter ? ') tmpTable WHERE 1'.$this->_tmpTableFilter : '').
		(($use_limit === true) ? ' LIMIT '.(int)$start.','.(int)$limit : '');

		$this->_listTotal = 0;
		if (!($this->_list = Db::getInstance()->executeS($this->_listsql, true, false)))
			$this->_list_error = Db::getInstance()->getMsgError();
		else
			$this->_listTotal = Db::getInstance()->getValue('SELECT FOUND_ROWS() AS `'._DB_PREFIX_.$this->table.'`', false);

		Hook::exec('action'.$this->controller_name.'ListingResultsModifier', array(
			'list' => &$this->_list,
			'list_total' => &$this->_listTotal,
		));
	}
	
	public function getModulesList($filter_modules_list)
	{
		if (!is_array($filter_modules_list) && !is_null($filter_modules_list))
			$filter_modules_list = array($filter_modules_list);
		
		if (!count($filter_modules_list))
			return false; //if there is no modules to display just return false;
		
		$all_modules = Module::getModulesOnDisk(true);
		$this->modules_list = array();
		foreach ($all_modules as $module)
		{
			$perm = true;
			if ($module->id)
				$perm &= Module::getPermissionStatic($module->id, 'configure');
			else
			{
				$id_admin_module = Tab::getIdFromClassName('AdminModules');
				$access = Profile::getProfileAccess($this->context->employee->id_profile, $id_admin_module);
				if (!$access['edit'])
					$perm &= false; 
			}
			
			if (in_array($module->name, $filter_modules_list) && $perm)
			{
				$this->fillModuleData($module, 'array');
				$this->modules_list[array_search($module->name, $filter_modules_list)] = $module;
			}		
		}
		ksort($this->modules_list);

		if (count($this->modules_list))
			return true;

		return false; //no module found on disk just return false;
		
	}

	public function getLanguages()
	{
		$cookie = $this->context->cookie;
		$this->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
		if ($this->allow_employee_form_lang && !$cookie->employee_form_lang)
			$cookie->employee_form_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		
		$lang_exists = false;
		$this->_languages = Language::getLanguages(false);
		foreach ($this->_languages as $lang)
			if (isset($cookie->employee_form_lang) && $cookie->employee_form_lang == $lang['id_lang'])
				$lang_exists = true;

		$this->default_form_language = $lang_exists ? (int)$cookie->employee_form_lang : (int)Configuration::get('PS_LANG_DEFAULT');

		foreach ($this->_languages as $k => $language)
			$this->_languages[$k]['is_default'] = ((int)($language['id_lang'] == $this->default_form_language));

		return $this->_languages;
	}


	/**
	 * Return the list of fields value
	 *
	 * @param object $obj Object
	 * @return array
	 */
	public function getFieldsValue($obj)
	{
		foreach ($this->fields_form as $fieldset)
			if (isset($fieldset['form']['input']))
				foreach ($fieldset['form']['input'] as $input)
					if (!isset($this->fields_value[$input['name']]))
						if (isset($input['type']) && $input['type'] == 'shop')
						{
							if ($obj->id)
							{
								$result = Shop::getShopById((int)$obj->id, $this->identifier, $this->table);
								foreach ($result as $row)
									$this->fields_value['shop'][$row['id_'.$input['type']]][] = $row['id_shop'];
							}
						}
						elseif (isset($input['lang']) && $input['lang'])
							foreach ($this->_languages as $language)
							{
								$fieldValue = $this->getFieldValue($obj, $input['name'], $language['id_lang']);
								if (empty($fieldValue))
								{
									if (isset($input['default_value']) && is_array($input['default_value']) && isset($input['default_value'][$language['id_lang']]))
										$fieldValue = $input['default_value'][$language['id_lang']];
									elseif (isset($input['default_value']))
										$fieldValue = $input['default_value'];
								}
								$this->fields_value[$input['name']][$language['id_lang']] = $fieldValue;
							}
						else
						{
							$fieldValue = $this->getFieldValue($obj, $input['name']);
							if ($fieldValue === false && isset($input['default_value']))
								$fieldValue = $input['default_value'];
							$this->fields_value[$input['name']] = $fieldValue;
						}

		return $this->fields_value;
	}

	/**
	 * Return field value if possible (both classical and multilingual fields)
	 *
	 * Case 1 : Return value if present in $_POST / $_GET
	 * Case 2 : Return object value
	 *
	 * @param object $obj Object
	 * @param string $key Field name
	 * @param integer $id_lang Language id (optional)
	 * @return string
	 */
	public function getFieldValue($obj, $key, $id_lang = null)
	{
		if ($id_lang)
			$default_value = (isset($obj->id) && $obj->id && isset($obj->{$key}[$id_lang])) ? $obj->{$key}[$id_lang] : false;
		else
			$default_value = isset($obj->{$key}) ? $obj->{$key} : false;

		return Tools::getValue($key.($id_lang ? '_'.$id_lang : ''), $default_value);
	}

	/**
	 * Manage page display (form, list...)
	 *
	 * @param string $className Allow to validate a different class than the current one
	 */
	public function validateRules($class_name = false)
	{
		if (!$class_name)
			$class_name = $this->className;

		$object = new $class_name();

		if (method_exists($this, 'getValidationRules'))
			$definition = $this->getValidationRules();
		else
			$definition = ObjectModel::getDefinition($class_name);

		$default_language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

		foreach ($definition['fields'] as $field => $def)
		{
			$skip = array();
			if (in_array($field, array('passwd', 'no-picture')))
				$skip = array('required');

			if (isset($def['lang']) && $def['lang'])
			{
				if (isset($def['required']) && $def['required'])
				{
					$value = Tools::getValue($field.'_'.$default_language->id);
					if (empty($value))
						$this->errors[$field.'_'.$default_language->id] = sprintf(
								Tools::displayError('The field %1$s is required at least in %2$s.'),
								$object->displayFieldName($field, $class_name),
								$default_language->name
						);
				}

				foreach (Language::getLanguages(false) as $language)
				{
					$value = Tools::getValue($field.'_'.$language['id_lang']);
					if (!empty($value))
						if (($error = $object->validateField($field, $value, $language['id_lang'], $skip, true)) !== true)
							$this->errors[$field.'_'.$language['id_lang']] = $error;
				}
			}
			else
				if (($error = $object->validateField($field, Tools::getValue($field), null, $skip, true)) !== true)
					$this->errors[$field] = $error;
		}


		/* Overload this method for custom checking */
		$this->_childValidation();

		/* Checking for multilingual fields validity */
		if (isset($rules['validateLang']) && is_array($rules['validateLang']))
			foreach ($rules['validateLang'] as $field_lang => $function)
				foreach ($languages as $language)
					if (($value = Tools::getValue($field_lang.'_'.$language['id_lang'])) !== false && !empty($value))
					{
						if (Tools::strtolower($function) == 'iscleanhtml' && Configuration::get('PS_ALLOW_HTML_IFRAME'))
							$res = Validate::$function($value, true);
						else
							$res = Validate::$function($value);
						if (!$res)
							$this->errors[$field_lang.'_'.$language['id_lang']] = sprintf(
								Tools::displayError('The %1$s field (%2$s) is invalid.'),
								call_user_func(array($class_name, 'displayFieldName'), $field_lang, $class_name),
								$language['name']
							);
					}
	}

	/**
	 * Overload this method for custom checking
	 */
	protected function _childValidation()
	{
	}

	/**
	 * Display object details
	 */
	public function viewDetails()
	{
	}

	/**
	 * Called before deletion
	 *
	 * @param object $object Object
	 * @return boolean
	 */
	protected function beforeDelete($object)
	{
		return false;
	}

	/**
	 * Called before deletion
	 *
	 * @param object $object Object
	 * @return boolean
	 */
	protected function afterDelete($object, $oldId)
	{
		return true;
	}

	protected function afterAdd($object)
	{
		return true;
	}

	protected function afterUpdate($object)
	{
		return true;
	}

	/**
	 * Check rights to view the current tab
	 *
	 * @return boolean
	 */

	protected function afterImageUpload()
	{
		return true;
	}

	/**
	 * Copy datas from $_POST to object
	 *
	 * @param object &$object Object
	 * @param string $table Object table
	 */
	protected function copyFromPost(&$object, $table)
	{
		/* Classical fields */
		foreach ($_POST as $key => $value)
			if (array_key_exists($key, $object) && $key != 'id_'.$table)
			{
				/* Do not take care of password field if empty */
				if ($key == 'passwd' && Tools::getValue('id_'.$table) && empty($value))
					continue;
				/* Automatically encrypt password in MD5 */
				if ($key == 'passwd' && !empty($value))
					$value = Tools::encrypt($value);
				$object->{$key} = $value;
			}

		/* Multilingual fields */
		$rules = call_user_func(array(get_class($object), 'getValidationRules'), get_class($object));
		if (count($rules['validateLang']))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
				foreach (array_keys($rules['validateLang']) as $field)
					if (isset($_POST[$field.'_'.(int)$language['id_lang']]))
						$object->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
		}
	}

	/**
	 * Returns an array with selected shops and type (group or boutique shop)
	 *
	 * @param string $table
	 * @return array
	 */
	protected function getSelectedAssoShop($table)
	{
		if (!Shop::isFeatureActive() || !Shop::isTableAssociated($table))
			return array();

		$shops = Shop::getShops(true, null, true);
		if (count($shops) == 1 && isset($shops[0]))
			return array($shops[0], 'shop');

		$assos = array();
		if (Tools::isSubmit('checkBoxShopAsso_'.$table))
			foreach (Tools::getValue('checkBoxShopAsso_'.$table) as $id_shop => $value)
				$assos[] = (int)$id_shop;
		else if (Shop::getTotalShops(false) == 1)// if we do not have the checkBox multishop, we can have an admin with only one shop and being in multishop
			$assos[] = (int)Shop::getContextShopID();
		return $assos;
	}

	/**
	 * Update the associations of shops
	 *
	 * @param int $id_object
	 */
	protected function updateAssoShop($id_object)
	{
		if (!Shop::isFeatureActive())
			return;

		if (!Shop::isTableAssociated($this->table))
			return;

		$assos_data = $this->getSelectedAssoShop($this->table, $id_object);

		// Get list of shop id we want to exclude from asso deletion
		$exclude_ids = $assos_data;
		foreach (Db::getInstance()->executeS('SELECT id_shop FROM '._DB_PREFIX_.'shop') as $row)
			if (!$this->context->employee->hasAuthOnShop($row['id_shop']))
				$exclude_ids[] = $row['id_shop'];
		Db::getInstance()->delete($this->table.'_shop', '`'.$this->identifier.'` = '.(int)$id_object.($exclude_ids ? ' AND id_shop NOT IN ('.implode(', ', $exclude_ids).')' : ''));

		$insert = array();
		foreach ($assos_data as $id_shop)
			$insert[] = array(
				$this->identifier => $id_object,
				'id_shop' => (int)$id_shop,
			);
		return Db::getInstance()->insert($this->table.'_shop', $insert, false, true, Db::INSERT_IGNORE);
	}

	protected function validateField($value, $field)
	{
		if (isset($field['validation']))
		{
			$valid_method_exists = method_exists('Validate', $field['validation']);
			if ((!isset($field['empty']) || !$field['empty'] || (isset($field['empty']) && $field['empty'] && $value)) && $valid_method_exists)
			{
				if (!Validate::$field['validation']($value))
				{
					$this->errors[] = Tools::displayError($field['title'].' : Incorrect value');
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Can be overriden
	 */
	public function beforeUpdateOptions()
	{
	}

	/**
	 * Overload this method for custom checking
	 *
	 * @param integer $id Object id used for deleting images
	 * @return boolean
	 */
	protected function postImage($id)
	{
		if (isset($this->fieldImageSettings['name']) && isset($this->fieldImageSettings['dir']))
			return $this->uploadImage($id, $this->fieldImageSettings['name'], $this->fieldImageSettings['dir'].'/');
		elseif (!empty($this->fieldImageSettings))
			foreach ($this->fieldImageSettings as $image)
				if (isset($image['name']) && isset($image['dir']))
					$this->uploadImage($id, $image['name'], $image['dir'].'/');
		return !count($this->errors) ? true : false;
	}

	protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
	{
		if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
		{
			// Delete old image
			if (Validate::isLoadedObject($object = $this->loadObject()))
				$object->deleteImage();
			else
				return false;

			// Check image validity
			$max_size = isset($this->max_image_size) ? $this->max_image_size : 0;
			if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size)))
				$this->errors[] = $error;

			$tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
			if (!$tmp_name)
				return false;

			if (!move_uploaded_file($_FILES[$name]['tmp_name'], $tmp_name))
				return false;

			// Evaluate the memory required to resize the image: if it's too much, you can't resize it.
			if (!ImageManager::checkImageMemoryLimit($tmp_name))
				$this->errors[] = Tools::displayError('Due to memory limit restrictions, this image cannot be loaded. Please increase your memory_limit value via your server\'s configuration settings. ');

			// Copy new image
			if (empty($this->errors) && !ImageManager::resize($tmp_name, _PS_IMG_DIR_.$dir.$id.'.'.$this->imageType, (int)$width, (int)$height, ($ext ? $ext : $this->imageType)))
				$this->errors[] = Tools::displayError('An error occurred while uploading the image.');

			if (count($this->errors))
				return false;
			if ($this->afterImageUpload())
			{
				unlink($tmp_name);
				return true;
			}
			return false;
		}
		return true;
	}

	/**
	 * Delete multiple items
	 *
	 * @return boolean true if succcess
	 */
	protected function processBulkDelete()
	{
		if (is_array($this->boxes) && !empty($this->boxes))
		{
			$object = new $this->className();

			if (isset($object->noZeroObject))
			{
				$objects_count = count(call_user_func(array($this->className, $object->noZeroObject)));

				// Check if all object will be deleted
				if ($objects_count <= 1 || count($this->boxes) == $objects_count)
					$this->errors[] = Tools::displayError('You need at least one object.').
						' <b>'.$this->table.'</b><br />'.
						Tools::displayError('You cannot delete all of the items.');
			}
			else
			{
				$result = true;
				foreach ($this->boxes as $id)
				{
					$to_delete = new $this->className($id);
					$delete_ok = true;
					if ($this->deleted)
					{
						$to_delete->deleted = 1;
						if (!$to_delete->update())
						{
							$result = false;
							$delete_ok = false;
						}
					}
					else
						if (!$to_delete->delete())
						{
							$result = false;
							$delete_ok = false;
						}
					
					if ($delete_ok)
						PrestaShopLogger::addLog(sprintf($this->l('%s deletion', 'AdminTab', false, false), $this->className), 1, null, $this->className, (int)$to_delete->id, true, (int)$this->context->employee->id);
					else
						$this->errors[] = sprintf(Tools::displayError('Can\'t delete #%d'), $id);
				}
				if ($result)
					$this->redirect_after = self::$currentIndex.'&conf=2&token='.$this->token;
				$this->errors[] = Tools::displayError('An error occurred while deleting this selection.');
			}
		}
		else
			$this->errors[] = Tools::displayError('You must select at least one element to delete.');

		if (isset($result))
			return $result;
		else
			return false;
	}
	
	/**
	 * Enable multiple items
	 *
	 * @return boolean true if succcess
	 */
	protected function processBulkEnableSelection()
	{
		return $this->processBulkStatusSelection(1);
	}
	
	/**
	 * Disable multiple items
	 *
	 * @return boolean true if succcess
	 */
	protected function processBulkDisableSelection()
	{
		return $this->processBulkStatusSelection(0);
	}
	
	/**
	 * Toggle status of multiple items
	 *
	 * @return boolean true if succcess
	 */
	protected function processBulkStatusSelection($status)
	{
		$result = true;
		if (is_array($this->boxes) && !empty($this->boxes))
		{
			foreach ($this->boxes as $id)
			{
				$object = new $this->className((int)$id);
				$object->active = (int)$status;
				$result &= $object->update();
			}
		}
		return $result;
	}

	protected function processBulkAffectZone()
	{
		$result = false;
		if (is_array($this->boxes) && !empty($this->boxes))
		{
			$object = new $this->className();
			$result = $object->affectZoneToSelection(Tools::getValue($this->table.'Box'), Tools::getValue('zone_to_affect'));

			if ($result)
				$this->redirect_after = self::$currentIndex.'&conf=28&token='.$this->token;
			$this->errors[] = Tools::displayError('An error occurred while affecting a zone to the selection.');
		}
		else
			$this->errors[] = Tools::displayError('You must select at least one element to affect a new zone.');

		return $result;
	}

	/**
	 * Called before Add
	 *
	 * @param object $object Object
	 * @return boolean
	 */
	protected function beforeAdd($object)
	{
		return true;
	}

	/**
	 * prepare the view to display the required fields form
	 */
	public function displayRequiredFields()
	{
		if (!$this->tabAccess['add'] || !$this->tabAccess['delete'] === '1' || !$this->required_database)
			return;

		$helper = new Helper();
		$helper->currentIndex = self::$currentIndex;
		$helper->token = $this->token;
		return $helper->renderRequiredFields($this->className, $this->identifier, $this->required_fields);
	}

	/**
	 * Create a template from the override file, else from the base file.
	 *
	 * @param string $tpl_name filename
	 * @return Template
	 */
	public function createTemplate($tpl_name)
	{
		// Use override tpl if it exists
		// If view access is denied, we want to use the default template that will be used to display an error
		if ($this->viewAccess() && $this->override_folder)
		{
			if (file_exists($this->context->smarty->getTemplateDir(1).DIRECTORY_SEPARATOR.$this->override_folder.$tpl_name))
				return $this->context->smarty->createTemplate($this->override_folder.$tpl_name, $this->context->smarty);
			elseif (file_exists($this->context->smarty->getTemplateDir(0).'controllers'.DIRECTORY_SEPARATOR.$this->override_folder.$tpl_name))
				return $this->context->smarty->createTemplate('controllers'.DIRECTORY_SEPARATOR.$this->override_folder.$tpl_name, $this->context->smarty);
		}

		return $this->context->smarty->createTemplate($this->context->smarty->getTemplateDir(0).$tpl_name, $this->context->smarty);
	}

	/**
	 * Shortcut to set up a json success payload
	 *
	 * @param $message success message
	 */
	public function jsonConfirmation($message)
	{
		$this->json = true;
		$this->confirmations[] = $message;
		if ($this->status === '')
			$this->status = 'ok';
	}

	/**
	 * Shortcut to set up a json error payload
	 *
	 * @param $message error message
	 */
	public function jsonError($message)
	{
		$this->json = true;
		$this->errors[] = $message;
		if ($this->status === '')
			$this->status = 'error';
	}

	public function isFresh($file, $timeout = 604800000)
	{
		if (file_exists(_PS_ROOT_DIR_.$file) && filesize(_PS_ROOT_DIR_.$file) > 0)
			return ((time() - filemtime(_PS_ROOT_DIR_.$file)) < $timeout);
		return false;
	}

	protected static $is_prestashop_up = true;
	public function refresh($file_to_refresh, $external_file)
	{
		if (self::$is_prestashop_up && $content = Tools::file_get_contents($external_file))
			return (bool)file_put_contents(_PS_ROOT_DIR_.$file_to_refresh, $content);
		self::$is_prestashop_up = false;
		return false;
	}
	
	public function fillModuleData(&$module, $output_type = 'link', $back = null)
	{
		$obj = null;
		if ($module->onclick_option)
			$obj = new $module->name();
		// Fill module data
		$module->logo = '../../img/questionmark.png';

		if (@filemtime(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.basename(_PS_MODULE_DIR_).DIRECTORY_SEPARATOR.$module->name
			.DIRECTORY_SEPARATOR.'logo.gif'))
			$module->logo = 'logo.gif';
		if (@filemtime(_PS_ROOT_DIR_.DIRECTORY_SEPARATOR.basename(_PS_MODULE_DIR_).DIRECTORY_SEPARATOR.$module->name
			.DIRECTORY_SEPARATOR.'logo.png'))
			$module->logo = 'logo.png';
		$module->optionsHtml = $this->displayModuleOptions($module, $output_type, $back);
		$link_admin_modules = $this->context->link->getAdminLink('AdminModules', true);

		$module->options['install_url'] = $link_admin_modules.'&install='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.$module->name.'&anchor='.ucfirst($module->name);
		$module->options['update_url'] = $link_admin_modules.'&update='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.$module->name.'&anchor='.ucfirst($module->name);
		$module->options['uninstall_url'] = $link_admin_modules.'&uninstall='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.$module->name.'&anchor='.ucfirst($module->name);

		$module->options['uninstall_onclick'] = ((!$module->onclick_option) ?
			((empty($module->confirmUninstall)) ? 'return confirm(\''.$this->l('Do you really want to uninstall this module?').'\');' : 'return confirm(\''.addslashes($module->confirmUninstall).'\');') :
			$obj->onclickOption('uninstall', $module->options['uninstall_url']));

		if ((Tools::getValue('module_name') == $module->name || in_array($module->name, explode('|', Tools::getValue('modules_list')))) && (int)Tools::getValue('conf') > 0)
			$module->message = $this->_conf[(int)Tools::getValue('conf')];

		if ((Tools::getValue('module_name') == $module->name || in_array($module->name, explode('|', Tools::getValue('modules_list')))) && (int)Tools::getValue('conf') > 0)
		unset($obj);
	}
	
	/**
	 * Display modules list
	 *
	 * @param $module
	 * @param $output_type (link or select)
	 * @param $back 
	 *
	 * @return string
	 */
	protected $translationsTab = array();
	public function displayModuleOptions($module, $output_type = 'link', $back = null)
	{
		if (!isset($module->enable_device))
			$module->enable_device = Context::DEVICE_COMPUTER | Context::DEVICE_TABLET | Context::DEVICE_MOBILE;

		$this->translationsTab['confirm_uninstall_popup'] = (isset($module->confirmUninstall) ? $module->confirmUninstall : $this->l('Do you really want to uninstall this module?'));
		if (!isset($this->translationsTab['Disable this module']))
		{
			$this->translationsTab['Disable this module'] = $this->l('Disable this module');
			$this->translationsTab['Enable this module for all shops'] = $this->l('Enable this module for all shops');
			$this->translationsTab['Disable'] = $this->l('Disable');
			$this->translationsTab['Enable'] = $this->l('Enable');
			$this->translationsTab['Disable on mobiles'] = $this->l('Disable on mobiles');
			$this->translationsTab['Disable on tablets'] = $this->l('Disable on tablets');
			$this->translationsTab['Disable on computers'] = $this->l('Disable on computers');
			$this->translationsTab['Display on mobiles'] = $this->l('Display on mobiles');
			$this->translationsTab['Display on tablets'] = $this->l('Display on tablets');
			$this->translationsTab['Display on computers'] = $this->l('Display on computers');
			$this->translationsTab['Reset'] = $this->l('Reset');
			$this->translationsTab['Configure'] = $this->l('Configure');
			$this->translationsTab['Delete'] = $this->l('Delete');
			$this->translationsTab['Install'] = $this->l('Install');
			$this->translationsTab['Uninstall'] =  $this->l('Uninstall');
			$this->translationsTab['Would you like to delete the content related to this module ?'] =  $this->l('Would you like to delete the content related to this module ?');
			$this->translationsTab['This action will permanently remove the module from the server. Are you sure you want to do this?'] = $this->l('This action will permanently remove the module from the server. Are you sure you want to do this?');
		}

		$link_admin_modules = $this->context->link->getAdminLink('AdminModules', true);
		$modules_options = array();

		$configure_module = array(
			'href' => $link_admin_modules.'&configure='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.urlencode($module->name),
			'onclick' => $module->onclick_option && isset($module->onclick_option_content['configure']) ? $module->onclick_option_content['configure'] : '',
			'title' => '',
			'text' => $this->translationsTab['Configure'],
			'cond' => $module->id && isset($module->is_configurable) && $module->is_configurable,
			'icon' => 'wrench',
		);

		$desactive_module = array(
			'href' => $link_admin_modules.'&module_name='.urlencode($module->name).'&'.($module->active ? 'enable=0' : 'enable=1').'&tab_module='.$module->tab,
			'onclick' => $module->active && $module->onclick_option && isset($module->onclick_option_content['desactive']) ? $module->onclick_option_content['desactive'] : '' ,
			'title' => Shop::isFeatureActive() ? htmlspecialchars($module->active ? $this->translationsTab['Disable this module'] : $this->translationsTab['Enable this module for all shops']) : '',
			'text' => $module->active ? $this->translationsTab['Disable'] : $this->translationsTab['Enable'],
			'cond' => $module->id,
			'icon' => 'off',
		);
		$link_reset_module = $link_admin_modules.'&module_name='.urlencode($module->name).'&reset&tab_module='.$module->tab;

		$is_reset_ready = false;
		if (Validate::isModuleName($module->name))
			if (method_exists(Module::getInstanceByName($module->name), 'reset'))
				$is_reset_ready = true;

		$reset_module = array(
			'href' => $link_reset_module,
			'onclick' => $module->onclick_option && isset($module->onclick_option_content['reset']) ? $module->onclick_option_content['reset'] : '',
			'title' => '',
			'text' => $this->translationsTab['Reset'],
			'cond' => $module->id && $module->active,
			'icon' => 'undo',
			'class' => ($is_reset_ready ? 'reset_ready' : '')
		);

		$delete_module = array(
			'href' => $link_admin_modules.'&delete='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.urlencode($module->name),
			'onclick' => $module->onclick_option && isset($module->onclick_option_content['delete']) ? $module->onclick_option_content['delete'] : 'return confirm(\''.$this->translationsTab['This action will permanently remove the module from the server. Are you sure you want to do this?'].'\');',
			'title' => '',
			'text' => $this->translationsTab['Delete'],
			'cond' => true,
			'icon' => 'trash',
			'class' => 'text-danger'
		);

		$display_mobile = array(
			'href' => $link_admin_modules.'&module_name='.urlencode($module->name).'&'.($module->enable_device & Context::DEVICE_MOBILE ? 'disable_device' : 'enable_device').'='.Context::DEVICE_MOBILE.'&tab_module='.$module->tab,
			'onclick' => '',
			'title' => htmlspecialchars($module->enable_device & Context::DEVICE_MOBILE ? $this->translationsTab['Disable on mobiles'] : $this->translationsTab['Display on mobiles']),
			'text' => $module->enable_device & Context::DEVICE_MOBILE ? $this->translationsTab['Disable on mobiles'] : $this->translationsTab['Display on mobiles'],
			'cond' => $module->id,
			'icon' => 'mobile'
		);

		$display_tablet = array(
			'href' => $link_admin_modules.'&module_name='.urlencode($module->name).'&'.($module->enable_device & Context::DEVICE_TABLET ? 'disable_device' : 'enable_device').'='.Context::DEVICE_TABLET.'&tab_module='.$module->tab,
			'onclick' => '',
			'title' => htmlspecialchars($module->enable_device & Context::DEVICE_TABLET ? $this->translationsTab['Disable on tablets'] : $this->translationsTab['Display on tablets']),
			'text' => $module->enable_device & Context::DEVICE_TABLET ? $this->translationsTab['Disable on tablets'] : $this->translationsTab['Display on tablets'],
			'cond' => $module->id,
			'icon' => 'tablet'
		);

		$display_computer = array(
			'href' => $link_admin_modules.'&module_name='.urlencode($module->name).'&'.($module->enable_device & Context::DEVICE_COMPUTER ? 'disable_device' : 'enable_device').'='.Context::DEVICE_COMPUTER.'&tab_module='.$module->tab,
			'onclick' => '',
			'title' => htmlspecialchars($module->enable_device & Context::DEVICE_COMPUTER ? $this->translationsTab['Disable on computers'] : $this->translationsTab['Display on computers']),
			'text' => $module->enable_device & Context::DEVICE_COMPUTER ? $this->translationsTab['Disable on computers'] : $this->translationsTab['Display on computers'],
			'cond' => $module->id,
			'icon' => 'desktop'
		);

		if ($module->active)
		{
			$modules_options[] = $configure_module;
			$modules_options[] = $desactive_module;
			$modules_options[] = $display_mobile;
			$modules_options[] = $display_tablet;
			$modules_options[] = $display_computer;
		}
		else
		{
			$modules_options[] = $desactive_module;
			$modules_options[] = $configure_module;
		}
		
		$modules_options[] = $reset_module;
		$modules_options[] = $delete_module; 
		
		$return = '';
		foreach ($modules_options as $option_name => $option)
		{
			if ($option['cond'])
			{
				if ($output_type == 'link')
					$return .= '<li><a class="'.$option_name.' action_module" href="'.$option['href'].(!is_null($back) ? '&back='.urlencode($back) : '').'" onclick="'.$option['onclick'].'"  title="'.$option['title'].'"><i class="icon-'.(isset($option['icon']) && $option['icon'] ? $option['icon']:'cog' ).'"></i>&nbsp;'.$option['text'].'</a></li>';
				elseif ($output_type == 'array')
				{
					if (!is_array($return))
						$return = array();

					$html = '<a class="';

					if (isset($option['class']))
						$html .= $option['class'];
					if (count($return) == 0)
						$html .= ' btn btn-default';

					$html .= '" href="'.$option['href'].(!is_null($back) ? '&back='.urlencode($back) : '').'" onclick="'.$option['onclick'].'"  title="'.$option['title'].'"><i class="icon-'.(isset($option['icon']) && $option['icon'] ? $option['icon']:'cog' ).'"></i> '.$option['text'].'</a>';
					$return[] = $html;
				}
				elseif ($output_type == 'select')
					$return .= '<option id="'.$option_name.'" data-href="'.$option['href'].(!is_null($back) ? '&back='.urlencode($back) : '').'" data-onclick="'.$option['onclick'].'">'.$option['text'].'</option>';
			}
		}
		if ($output_type == 'select')
		{
			if (!$module->id)
				$return = '<option data-onclick="" data-href="'.$link_admin_modules.'&install='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.$module->name.'&anchor='.ucfirst($module->name).(!is_null($back) ? '&back='.urlencode($back) : '').'" >'.$this->translationsTab['Install'].'</option>'.$return;
			else
				$return .= '<option data-onclick=""  data-href="'.$link_admin_modules.'&uninstall='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.$module->name.'&anchor='.ucfirst($module->name).(!is_null($back) ? '&back='.urlencode($back) : '').'" >'.$this->translationsTab['Uninstall'].'</option>';
			$return = '<select id="select_'.$module->name.'">'.$return.'</select>';
		}
		else if ($output_type == 'array')
		{
			if ($module->id)
				$return[] = '<a href="'.$link_admin_modules.'&uninstall='.urlencode($module->name).'&tab_module='.$module->tab.'&module_name='.$module->name.'&anchor='.ucfirst($module->name).(!is_null($back) ? '&back='.urlencode($back) : '').'"
				onclick="'.(isset($module->onclick_option_content['uninstall']) ? $module->onclick_option_content['uninstall'] : 'return confirm(\''.$this->translationsTab['confirm_uninstall_popup'].'\');').'"
				title="'.$this->translationsTab['Uninstall'].'">
				<i class="icon-minus-sign-alt"></i>&nbsp;'.$this->translationsTab['Uninstall'].'</a>';
		}

		return $return;
	}
}
