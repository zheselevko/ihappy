<?php
class ControllerExtensionModuleCategoryMenu extends Controller {
	private $error = array();

	public function index() {
		
		$menu_icon = $this->db->query("DESC " . DB_PREFIX . "category menu_icon");
		if (!$menu_icon->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` ADD `menu_icon` varchar(255) NULL default '' AFTER image;");
		}

		$this->language->load('module/category_menu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/category_menu/category_menu.css');

		$this->load->model('setting/setting');

	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('category_menu', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}
		$data['heading_title'] = $this->language->get('heading_title');
		$data['default_store'] = $this->config->get('config_name');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');

		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_featured'] = $this->language->get('text_featured');
		$data['text_flyout'] = $this->language->get('text_flyout');
		$data['text_accordion'] = $this->language->get('text_accordion');
		$data['text_collapsible'] = $this->language->get('text_collapsible');
		$data['text_dimension'] = $this->language->get('text_dimension');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_featured_cat'] = $this->language->get('entry_featured_cat');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_count'] = $this->language->get('entry_count');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_items'] = $this->language->get('entry_items');
		$data['entry_style'] = $this->language->get('entry_style');
		$data['entry_toggle'] = $this->language->get('entry_toggle');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_cat_icon'] = $this->language->get('entry_cat_icon');
		$data['entry_icon'] = $this->language->get('entry_icon');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_link_title'] = $this->language->get('entry_link_title');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_box_class'] = $this->language->get('entry_box_class');
		$data['entry_heading_class'] = $this->language->get('entry_heading_class');
		$data['entry_content_class'] = $this->language->get('entry_content_class');

		$data['tab_module'] = $this->language->get('tab_module');
		$data['tab_module_setting'] = $this->language->get('tab_module_setting');
		$data['tab_menu_setting'] = $this->language->get('tab_menu_setting');
		$data['tab_other'] = $this->language->get('tab_other');
		$data['tab_categories'] = $this->language->get('tab_categories');
		$data['tab_information'] = $this->language->get('tab_information');
		$data['tab_manufacturers'] = $this->language->get('tab_manufacturers');
		$data['tab_links'] = $this->language->get('tab_links');

		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

	
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/category_menu', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/category_menu', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		$data['modules'] = array();

		if (isset($this->request->post['category_menu_module'])) {
			$data['modules'] = $this->request->post['category_menu_module'];
		} elseif ($this->config->get('category_menu_module')) { 
			$data['modules'] = $this->config->get('category_menu_module');
		}

		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('catalog/category_menu');
		$data['categories'] = $this->model_catalog_category_menu->getCategoryMenuCategories(0);

		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = $this->model_catalog_manufacturer->getManufacturers();

		$this->load->model('catalog/information');
		$data['informations'] = $this->model_catalog_information->getInformations();

		$this->load->model('tool/image');
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

			if (isset($this->request->post['category_menu_status'])) {
			$data['category_menu_status'] = $this->request->post['category_menu_status'];
		} else {
			$data['category_menu_status'] = $this->config->get('category_menu_status');
		}

		
	$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
$this->response->setOutput($this->load->view('extension/module/category_menu.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/category_menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>