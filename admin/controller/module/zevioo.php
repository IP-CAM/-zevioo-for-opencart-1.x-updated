<?php
/**
 * @package       REVE Chat
 * @license       GNU/GPL 2 or later
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */

class ControllerModuleZevioo extends Controller {
    private $error = array();
	
    public function index() {
        // Language
        $this->load->language('module/zevioo');

        // Page Title
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_zevioo', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

        // BreadCrumbs
        $this->data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
                'separator' => false
            ),
            array(
                'text' => $this->language->get('text_extension'),
                'href' => $this->url->link('extension/module', 'token='.$this->session->data['token'], 'SSL'),
                'separator' => '::'
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('module/zevioo', 'token='.$this->session->data['token'], 'SSL'),
                'separator' => '::'
            )
        );


        $text = array('heading_title',
            'text_username',
            'text_password',
			'text_edit',
			'text_enabled',
			'text_disabled',
			'entry_status',
			'button_save',
			'button_cancel',
			'text_canceled_status'
        );

        foreach($text as $key){
            $this->data[$key] = $this->language->get($key);
        }


        if (isset($this->request->post['module_zevioo_username'])) {
			$this->data['zevioo_username'] = $this->request->post['module_zevioo_username'];
		} else {
			$this->data['zevioo_username'] = $this->config->get('module_zevioo_username');
		}
		if (isset($this->request->post['module_zevioo_password'])) {
			$this->data['zevioo_password'] = $this->request->post['module_zevioo_password'];
		} else {
			$this->data['zevioo_password'] = $this->config->get('module_zevioo_password');
		}
		
		if (isset($this->request->post['module_zevioo_status'])) {
			$this->data['module_zevioo_status'] = $this->request->post['module_zevioo_status'];
		} else {
			$this->data['module_zevioo_status'] = $this->config->get('module_zevioo_status');
		}
		
		if (isset($this->request->post['module_zevioo_canceled_status_id'])) {
			$this->data['module_zevioo_canceled_status_id'] = $this->request->post['module_zevioo_canceled_status_id'];
		} else {
			$this->data['module_zevioo_canceled_status_id'] = $this->config->get('module_zevioo_canceled_status_id');
		}
		
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        //$this->data['header'] = $this->load->controller('common/header');
        //$this->data['column_left'] = $this->load->controller('common/column_left');
       // $this->data['footer'] = $this->load->controller('common/footer');
       // $this->data['token'] = $this->session->data['token'];
        
        // Buttons
        $this->data['action'] = $this->url->link('module/zevioo', 'token='.$this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
        // Display Page
        //$this->response->setOutput($this->load->view('module/zevioo.tpl', $data));
		//$this->load->model('design/layout');
		
		//$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/zevioo.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/zevioo')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
		
		if (!$this->request->post['module_zevioo_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['module_zevioo_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

        return !$this->error;
    }
}