<?php
class ControllerExtensionModulenotificationTelegramChanel extends Controller {
	private $error = array();

	public function index() {


//        echo ($this->request->get['route']);

		$this->load->language('extension/module/notificationTelegramChanel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('notificationTelegramChanel', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}




		if (isset($this->error['notificationTelegramChanel_app_id'])) {
			$data['error_no_key_app_id'] = $this->error['notificationTelegramChanel_app_id'];
		} else {
			$data['error_no_key_app_id'] = '';
		}




		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/notificationTelegramChanel', 'user_token=' . $this->session->data['user_token'], true)
			);

		$data['action'] = $this->url->link('extension/module/notificationTelegramChanel', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


        $data['testUrl'] = htmlspecialchars_decode($this->url->link('extension/module/notificationTelegramChanel/test', 'user_token=' . $this->session->data['user_token'], 'SSL'));


        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting('notificationTelegramChanel');








		//الكي الخاص باالتطبيق
		if(isset($this->request->post['notificationTelegramChanel_app_id'])) {
			$data['notificationTelegramChanel_app_id'] = $this->request->post['notificationTelegramChanel_app_id'];
		} elseif ($this->config->get('notificationTelegramChanel_app_id')){
			$data['notificationTelegramChanel_app_id'] = $this->config->get('notificationTelegramChanel_app_id');
		} else{
			$data['notificationTelegramChanel_app_id'] = '';
		}





			//الكي الخاص باالتطبيق
		if(isset($this->request->post['notificationTelegramChanel_api_key'])) {
			$data['notificationTelegramChanel_api_key'] = $this->request->post['notificationTelegramChanel_api_key'];
		} elseif ($this->config->get('notificationTelegramChanel_api_key')){
			$data['notificationTelegramChanel_api_key'] = $this->config->get('notificationTelegramChanel_api_key');
		} else{
			$data['notificationTelegramChanel_api_key'] = '';
		}



			//الكي الخاص باالتطبيق
		if(isset($this->request->post['notificationTelegramChanel_status'])) {
			$data['notificationTelegramChanel_status'] = $this->request->post['notificationTelegramChanel_status'];
		} elseif ($this->config->get('notificationTelegramChanel_status')){
			$data['notificationTelegramChanel_status'] = $this->config->get('notificationTelegramChanel_status');
		} else{
			$data['notificationTelegramChanel_status'] = '';
		}



		$data['header'] =      $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] =      $this->load->controller('common/footer');


		$this->response->setOutput($this->load->view('extension/module/notificationTelegramChanel', $data));
	}



    public function uninstall() {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('Notificationonesignal');
    }



    /*
     * Send test message, to see if the push functionality is working
     */
    public function send(){



        $id = $_GET['id'];
        $msg = $_GET['msg'];
        $response = $this->sendMessage($id,$msg);
        $return["allresponses"] = $response;
        $return = json_encode($return);
        $data = json_decode($response, true);

        echo $response;

    }



 public function test(){

     $notificationTelegramChanel_api_key = $_GET["notificationTelegramChanel_api_key"];
     $notificationTelegramChanel_app_id = $_GET["notificationTelegramChanel_app_id"];



     $data = [
         'text' => "Test Chanel Telegram",
         'chat_id' => $notificationTelegramChanel_api_key
     ];

     echo  file_get_contents("https://api.telegram.org/bot$notificationTelegramChanel_app_id/sendMessage?" . http_build_query($data) );



 }

 public function getProduct(){

     global $loader, $registry;
     $id = (int) $_GET['id'];
     $loader->model('catalog/product');
     $model = $registry->get('model_catalog_product');
     $result = $model->getProduct($id);


     $nameProduct = $result["name"];
     $nameImage = 'https://'.$_SERVER['HTTP_HOST'].'/image/'.$result["image"];
     echo json_encode(array('text'=>$nameProduct,'imgUrl'=>$nameImage,'ID'=>$id));


    }



    function sendMessage() {


        $this->load->model('setting/setting');
        $setting = $this->model_setting_setting->getSetting('notificationTelegramChanel');




        $nameProduct = $_GET["name"];
        $ImageUrl = $_GET["imgUrl"];
        $id = $_GET["id"];




        $token = $setting['notificationTelegramChanel_app_id'];

      
	    $urlProduct = $this->url->link( 'catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $id, true );

        
        
        $data = [
            'text' => "$nameProduct \n  \n $urlProduct",
            'chat_id' => $setting['notificationTelegramChanel_api_key']
        ];

        return  file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) );



    }




    protected function validate() {

		if (!$this->user->hasPermission('modify', 'extension/module/notificationTelegramChanel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if (!$this->request->post['notificationTelegramChanel_app_id']) {
			$this->error['notificationTelegramChanel_app_id'] = $this->language->get('error_no_key_api_key');
		}

		if (!$this->request->post['notificationTelegramChanel_api_key']) {
			$this->error['notificationTelegramChanel_api_key'] = $this->language->get('error_no_key_app_id');
		}



		return !$this->error;
	}
}
