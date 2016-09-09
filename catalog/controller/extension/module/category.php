<?php
class ControllerExtensionModuleCategory extends Controller {
	public function index() {
		static $module = 1;

		$this->load->language('extension/module/category_menu');
		$this->document->addScript('catalog/view/javascript/jquery/category_menu/category-menu.js');
		$this->document->addScript('catalog/view/javascript/jquery/category_menu/jquery.menu-aim.js');

		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/category-menu.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/category-menu.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/category-menu.css');
		}

			$data['title_status'] = '1';
			$data['heading_title'] = $this->language->get('heading_title');


	
	
			$data['position'] = 'column-left';
	


			$data['style'] = 'collapsible';
	



	
			$data['image'] = '';
	

			$data['image_width'] = '170';
	

			$data['image_height'] = '106';



			$data['icon'] = '';



			$data['icon_width'] = '16';


	
			$data['icon_height'] = '16';



			$data['category_selected'] = array();








			$data['box_class'] = 'box';



			$data['tag'] = 'div';


			$data['heading_class'] = 'box-heading';
	


			$data['content_class'] = 'box-content';


		$setting['layout_id'] = '3';

		// if ($setting['layout_id'] == '3') {
		// 	$featured_categories = explode('_', $this->request->get['path']);
		// 	$featured_category = array_pop($featured_categories);
		// 	if (!isset($setting['featured_cid']) || !in_array($featured_category, $setting['featured_cid'])) {
		// 		return;
		// 	}
		// }

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		if (isset($parts[2])) {
			$data['child2_id'] = $parts[2];
		} else {
			$data['child2_id'] = 0;
		}

		if (isset($parts[3])) {
			$data['child3_id'] = $parts[3];
		} else {
			$data['child3_id'] = 0;
		}

		if (isset($parts[4])) {
			$data['child4_id'] = $parts[4];
		} else {
			$data['child4_id'] = 0;
		}
		$data['toggle'] = '';

		$this->load->model('catalog/category_menu');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/information');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');





	


	


		$data['categories'] = array();
		$setting['featured_categories'] = 'all';
		if ($setting['featured_categories'] == 'all') {
			$categories = $this->model_catalog_category_menu->getMenuCategories(0);

			foreach ($categories as $category) {
				$children_data = array();
				$children = $this->model_catalog_category_menu->getMenuCategories($category['category_id']);

				foreach ($children as $child) {
					$children2_data = array();
					$children2 = $this->model_catalog_category_menu->getMenuCategories($child['category_id']);

					foreach ($children2 as $child2) {
						$children3_data = array();
						$children3 = $this->model_catalog_category_menu->getMenuCategories($child2['category_id']);

						foreach ($children3 as $child3) {
							$children4_data = array();
							$children4 = $this->model_catalog_category_menu->getMenuCategories($child3['category_id']);

							foreach ($children4 as $child4) {
								$filter_data = array(
									'filter_category_id'  => $child4['category_id'],
									'filter_sub_category' => true
									);

								$children4_data[] = array(
									'category_id'  => $child4['category_id'],
									'name'         => $child4['name'],
									'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id'] . '_' . $child3['category_id'] . '_' . $child4['category_id']),
									'active'       => in_array($child4['category_id'], $parts),
									'cid'          => $category['category_id'] . '-' . $child['category_id'] . '-' . $child2['category_id'] . '-' . $child3['category_id'] . '-' . $child4['category_id']
									);
							}

							$filter_data = array(
								'filter_category_id'  => $child3['category_id'],
								'filter_sub_category' => true
								);

							$children3_data[] = array(
								'category_id'  => $child3['category_id'],
								'name'         => $child3['name'],
								'child4_id'    => $children4_data,
								'cid'          => $category['category_id'] . '-' . $child['category_id'] . '-' . $child2['category_id'] . '-' . $child3['category_id'],
								'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id'] . '_' . $child3['category_id']),
								'active'       => in_array($child3['category_id'], $parts),
								'thumb'        => $this->model_tool_image->resize($child3['image'], $data['image_width'], $data['image_height'])
								);
						}

						$filter_data = array(
							'filter_category_id'  => $child2['category_id'],
							'filter_sub_category' => true
							);

						$children2_data[] = array(
							'category_id'  => $child2['category_id'],
							'name'         => $child2['name'],
							'child3_id'    => $children3_data,
							'cid'          => $category['category_id'] . '-' . $child['category_id'] . '-' . $child2['category_id'],
							'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id']),
							'active'       => in_array($child2['category_id'], $parts),
							'thumb'        => $this->model_tool_image->resize($child2['image'], $data['image_width'], $data['image_height'])
							);
					}

					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
						);

					$children_data[] = array(
						'category_id'  => $child['category_id'],
						'name'         => $child['name'],
						'child2_id'    => $children2_data,
						'cid'          => $category['category_id'] . '-' . $child['category_id'],
						'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
						'active'       => in_array($child['category_id'], $parts),
						'thumb'        => $this->model_tool_image->resize($child['image'], $data['image_width'], $data['image_height'])
						);
				}

				$filter_data = array(
					'filter_category_id'  => $category['category_id'],
					'filter_sub_category' => true
					);

				$data['categories'][] = array(
					'category_id'  => $category['category_id'],
					'name'         => $category['name'],
					'children'     => $children_data,
					'href'         => $this->url->link('product/category', 'path=' . $category['category_id']),
					'active'       => in_array($category['category_id'], $parts),
					'thumb'        => $this->model_tool_image->resize($category['image'], $data['image_width'], $data['image_height'])
					// 'icon_thumb'   => $this->model_tool_image->resize($category['menu_icon'], $data['icon_width'], $data['icon_height'])
					);
			}

		} else {

			foreach ($categories as $category_id) {
				$category = $this->model_catalog_category_menu->getMenuCategory($category_id);

				if ($category) {
					$children_data = array();
					$children = $this->model_catalog_category_menu->getMenuCategories($category['category_id']);

					foreach ($children as $child) {
						$children2_data = array();
						$children2 = $this->model_catalog_category_menu->getMenuCategories($child['category_id']);

						foreach ($children2 as $child2) {
							$children3_data = array();
							$children3 = $this->model_catalog_category_menu->getMenuCategories($child2['category_id']);

							foreach ($children3 as $child3) {
								$children4_data = array();
								$children4 = $this->model_catalog_category_menu->getMenuCategories($child3['category_id']);

								foreach ($children4 as $child4) {
									$filter_data = array(
										'filter_category_id'  => $child4['category_id'],
										'filter_sub_category' => true
										);

									$children4_data[] = array(
										'category_id'  => $child4['category_id'],
										'name'         => $child4['name'],
										'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id'] . '_' . $child3['category_id'] . '_' . $child4['category_id']),
										'active'       => in_array($child4['category_id'], $parts),
										'cid'          => $category['category_id'] . '-' . $child['category_id'] . '-' . $child2['category_id'] . '-' . $child3['category_id'] . '-' . $child4['category_id']
										);
								}

								$filter_data = array(
									'filter_category_id'  => $child3['category_id'],
									'filter_sub_category' => true
									);

								$children3_data[] = array(
									'category_id'  => $child3['category_id'],
									'name'         => $child3['name'],
									'child4_id'    => $children4_data,
									'cid'          => $category['category_id'] . '-' . $child['category_id'] . '-' . $child2['category_id'] . '-' . $child3['category_id'],
									'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id'] . '_' . $child3['category_id']),
									'active'       => in_array($child3['category_id'], $parts),
									'thumb'        => $this->model_tool_image->resize($child3['image'], $data['image_width'], $data['image_height'])
									);
							}

							$filter_data = array(
								'filter_category_id'  => $child2['category_id'],
								'filter_sub_category' => true
								);

							$children2_data[] = array(
								'category_id'  => $child2['category_id'],
								'name'         => $child2['name'],
								'child3_id'    => $children3_data,
								'cid'          => $category['category_id'] . '-' . $child['category_id'] . '-' . $child2['category_id'],
								'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child2['category_id']),
								'active'       => in_array($child2['category_id'], $parts),
								'thumb'        => $this->model_tool_image->resize($child2['image'], $data['image_width'], $data['image_height'])
								);
						}

						$filter_data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
							);

						$children_data[] = array(
							'category_id'  => $child['category_id'],
							'name'         => $child['name'],
							'child2_id'    => $children2_data,
							'cid'          => $category['category_id'] . '-' . $child['category_id'],
							'href'         => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
							'active'       => in_array($child['category_id'], $parts),
							'thumb'        => $this->model_tool_image->resize($child['image'], $data['image_width'], $data['image_height'])
							);
					}

					$filter_data = array(
						'filter_category_id'  => $category['category_id'],
						'filter_sub_category' => true
						);

					$data['categories'][] = array(
						'category_id'  => $category['category_id'],
						'name'         => $category['name'],
						'children'     => $children_data,
						'href'         => $this->url->link('product/category', 'path=' . $category['category_id']),
						'active'       => in_array($category['category_id'], $parts),
						'sort_order'   => $category['sort_order'],
						'thumb'        => $this->model_tool_image->resize($category['image'], $data['image_width'], $data['image_height']),
						'icon_thumb'   => $this->model_tool_image->resize($category['menu_icon'], $data['icon_width'], $data['icon_height'])
						);
				}

				$sort_order = array();

				foreach ($data['categories'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $data['categories']);
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/category_menu', $data);
	}
}
?>