<?php
class ControllerPaymentallPayInvoice extends Controller 
{
	private $error = array(); 
	
	public function index() 
	{
		$this->load->language('payment/allpayinvoice');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('allpayinvoice', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_autoissue'] = $this->language->get('text_autoissue');
		//$data['text_invoice_url'] = $this->language->get('text_invoice_url');

		$data['entry_mid'] = $this->language->get('entry_mid');
		$data['entry_hashkey'] = $this->language->get('entry_hashkey');
		$data['entry_hashiv'] = $this->language->get('entry_hashiv');
		$data['entry_autoissue'] = $this->language->get('entry_autoissue');
		$data['entry_invoice_url'] = $this->language->get('entry_invoice_url');
		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
	
		if (isset($this->error['error_warning'])) {
			$data['error_warning'] = $this->error['error_warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['mid'])) {
			$data['error_mid'] = $this->error['mid'];
		} else {
			$data['error_mid'] = '';
		}
		if (isset($this->error['hashkey'])) {
			$data['error_hashkey'] = $this->error['hashkey'];
		} else {
			$data['error_hashkey'] = '';
		}
		if (isset($this->error['hashiv'])) {
			$data['error_hashiv'] = $this->error['hashiv'];
		} else {
			$data['error_hashiv'] = '';
		}
		if (isset($this->error['invoice_url'])) {
			$data['error_invoice_url'] = $this->error['invoice_url'];
		} else {
			$data['error_invoice_url'] = '';
		}
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
            		'text' => $this->language->get('heading_title'),
            		'href' => $this->url->link('payment/allpayinvoice', 'token=' . $this->session->data['token'], 'SSL')
        	); 
		
		$data['allpayinvoice_statuses'] = array();
		$data['allpayinvoice_statuses'][] = array(
			'value' => '1',
			'text' => $this->language->get('text_enabled')
		);
		$data['allpayinvoice_statuses'][] = array(
			'value' => '0',
			'text' => $this->language->get('text_disabled')
		);
		
		$data['allpayinvoice_autoissues'] = array();
		$data['allpayinvoice_autoissues'][] = array(
			'value' => '0',
			'text' => $this->language->get('text_disabled')
		);
		$data['allpayinvoice_autoissues'][] = array(
			'value' => '1',
			'text' => $this->language->get('text_enabled')
		);
		
		$data['action'] = $this->url->link('payment/allpayinvoice', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['allpayinvoice_mid'])) {
			$data['allpayinvoice_mid'] = $this->request->post['allpayinvoice_mid'];
		} else {
			$data['allpayinvoice_mid'] = $this->config->get('allpayinvoice_mid');
		}
		if (isset($this->request->post['allpayinvoice_hashkey'])) {
			$data['allpayinvoice_hashkey'] = $this->request->post['allpayinvoice_hashkey'];
		} else {
			$data['allpayinvoice_hashkey'] = $this->config->get('allpayinvoice_hashkey');
		}
		if (isset($this->request->post['allpayinvoice_hashiv'])) {
			$data['allpayinvoice_hashiv'] = $this->request->post['allpayinvoice_hashiv'];
		} else {
			$data['allpayinvoice_hashiv'] = $this->config->get('allpayinvoice_hashiv');
		}
		if (isset($this->request->post['allpayinvoice_autoissue'])) {
			$data['allpayinvoice_autoissue'] = $this->request->post['allpayinvoice_autoissue'];
		} else {
			$data['allpayinvoice_autoissue'] = $this->config->get('allpayinvoice_autoissue');
		}
		if (isset($this->request->post['allpayinvoice_status'])) {
			$data['allpayinvoice_status'] = $this->request->post['allpayinvoice_status'];
		} else {
			$data['allpayinvoice_status'] = $this->config->get('allpayinvoice_status');
		}
		if (isset($this->request->post['allpayinvoice_url'])) {
			$data['allpayinvoice_url'] = $this->request->post['allpayinvoice_url'];
		} else {
			$data['allpayinvoice_url'] = $this->config->get('allpayinvoice_url');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('payment/allpayinvoice.tpl', $data));
	}
	
	private function validate() 
	{
		if (!$this->user->hasPermission('modify', 'payment/allpayinvoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (empty($this->request->post['allpayinvoice_mid'])) {
			$this->error['mid'] = $this->language->get('error_mid');
		}
		
		if (empty($this->request->post['allpayinvoice_hashkey'])) {
			$this->error['hashkey'] = $this->language->get('error_hashkey');
		}
		
		if (empty($this->request->post['allpayinvoice_hashiv'])) {
			$this->error['hashiv'] = $this->language->get('error_hashiv');
		}
		
		if (empty($this->request->post['allpayinvoice_url'])) {
			$this->error['invoice_url'] = $this->language->get('error_invoice_url');
		}
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	// 手動開立發票
	public function createInvoiceNo()
	{
		$this->load->language('sale/order');
		
		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order'))
		{
			$json['error'] = $this->language->get('error_permission');
		}
		elseif (isset($this->request->get['order_id']))
		{
			if (isset($this->request->get['order_id']))
			{
				$order_id = $this->request->get['order_id'];
			}
			else
			{
				$order_id = 0;
			}
			
			$this->load->model('sale/order');
			
			
			// 判斷是否啟動ALLPAY電子發票開立
			$nInvoice_Status = $this->config->get('allpayinvoice_status');
			
			if($nInvoice_Status == 1)
			{
				// 1.參數初始化
				define('WEB_MESSAGE_NEW_LINE',	'|');	// 前端頁面訊息顯示換行標示語法
				$sMsg				= '' ;
				$sMsg_P2			= '' ;		// 金額有差異提醒
				$bError 			= false ; 	// 判斷各參數是否有錯誤，沒有錯誤才可以開發票
				
				// 2.取出開立相關參數
				
				// *連線資訊
				//$sAllpayinvoice_Url_Issue	= 'http://einvoice-stage.opay.tw/Invoice/Issue';		// 一般開立網址
				$sAllpayinvoice_Url_Issue	= $this->config->get('allpayinvoice_url');			// 一般開立網址
				$nAllpayinvoice_Mid 		= $this->config->get('allpayinvoice_mid') ;			// 廠商代號
				$sAllpayinvoice_Hashkey 	= $this->config->get('allpayinvoice_hashkey');			// 金鑰
				$sAllpayinvoice_Hashiv 		= $this->config->get('allpayinvoice_hashiv') ;			// 向量
				
				// *訂單資訊
				$aOrder_Info_Tmp 		= $this->model_sale_order->getOrder($order_id);			// 訂單資訊
				$aOrder_Product_Tmp  		= $this->model_sale_order->getOrderProducts($order_id);		// 訂購商品
				$aOrder_Total_Tmp  		= $this->model_sale_order->getOrderTotals($order_id);		// 訂單金額
				
				// *統編與愛心碼資訊
				$query = $this->db->query("SELECT * FROM invoice_info WHERE order_id = '" . (int)$order_id . "'" );
					
				// 3.判斷資料正確性
				if( $query->num_rows == 0 )
				{
					$bError = true ;
					$sMsg .= ( empty($sMsg) ? '' : WEB_MESSAGE_NEW_LINE ) . '開立發票資訊不存在。';
				}
				else
				{
					$aInvoice_Info = $query->rows[0] ;
				}
				
				// *URL判斷是否有值
				if($sAllpayinvoice_Url_Issue == '')
				{
					$bError = true ;
					$sMsg .= ( empty($sMsg) ? '' : WEB_MESSAGE_NEW_LINE ) . '請填寫發票傳送網址。';
				}
				
				// *MID判斷是否有值
				if($nAllpayinvoice_Mid == '')
				{
					$bError = true ;
					$sMsg .= ( empty($sMsg) ? '' : WEB_MESSAGE_NEW_LINE ) . '請填寫商店代號(Merchant ID)。';
				}
				
				// *HASHKEY判斷是否有值
				if($sAllpayinvoice_Hashkey == '')
				{
					$bError = true ;
					$sMsg .= ( empty($sMsg) ? '' : WEB_MESSAGE_NEW_LINE ) . '請填寫金鑰(Hash Key)。';
				}
				
				// *HASHIV判斷是否有值
				if($sAllpayinvoice_Hashiv == '')
				{
					$bError = true ;
					$sMsg .= ( empty($sMsg) ? '' : WEB_MESSAGE_NEW_LINE ) . '請填寫向量(Hash IV)。';
				}
				
				// 判斷是否開過發票
				if($aOrder_Info_Tmp['invoice_no'] != 0)
				{
					$bError = true ;
					$sMsg .= ( empty($sMsg) ? '' : WEB_MESSAGE_NEW_LINE ) . '已存在發票紀錄，請重新整理頁面。';
				}
	
				// 判斷商品是否存在
				if(count($aOrder_Product_Tmp) < 0)
				{
					$bError = true ;
					$sMsg .= ( empty($sMsg) ? '' : WEB_MESSAGE_NEW_LINE ) . ' 該訂單編號不存在商品，不允許開立發票。';
				}
				else
				{
					// 判斷商品是否含小數點
					foreach( $aOrder_Product_Tmp as $key => $value)
					{
						if ( !strstr($value['price'], '.00') )
						{
							$sMsg_P2 .= ( empty($sMsg_P2) ? '' : WEB_MESSAGE_NEW_LINE ) . '提醒：商品 ' . $value['name'] . ' 金額存在小數點，將以無條件進位開立發票。';
						}
					}
				}

				if(!$bError)
				{
					
					
					$sLove_Code 			= '' ;
					$nDonation			= '2' ;
					$nPrint				= '0' ;
					$sCustomerIdentifier		= '' ;
					
					if($aInvoice_Info['invoice_type'] == 1)
					{
						$nDonation 		= '2' ;					// 不捐贈
						$nPrint			= '0' ;
						$sCustomerIdentifier	= '' ;
					}
					elseif($aInvoice_Info['invoice_type'] == 2)
					{
						$nDonation 		= '2' ;					// 公司發票 不捐贈
						$nPrint			= '1' ;					// 公司發票 強制列印
						$sCustomerIdentifier	= $aInvoice_Info['company_write'] ;	// 公司統一編號
					}
					elseif($aInvoice_Info['invoice_type'] == 3)
					{
						$nDonation 		= '1' ;
						$nPrint			= '0' ;
						$sLove_Code 		= $aInvoice_Info['love_code'] ;
						$sCustomerIdentifier	= '' ;
					}
					else
					{
						$nDonation 		= '2' ;
						$nPrint			= '0' ;
						$sLove_Code 		= '' ;
						$sCustomerIdentifier	= '' ;	
					}
					
					
					
					// 4.送出參數
					try
					{
						include_once('AllPay_Invoice.php');
						$allpay_invoice = new AllInvoice ;
						
						// A.寫入基本介接參數
						$allpay_invoice->Invoice_Method 			= 'INVOICE' ;
						$allpay_invoice->Invoice_Url 				= $sAllpayinvoice_Url_Issue ;
						$allpay_invoice->MerchantID 				= $nAllpayinvoice_Mid ;
						$allpay_invoice->HashKey 				= $sAllpayinvoice_Hashkey ;
						$allpay_invoice->HashIV 				= $sAllpayinvoice_Hashiv ;
						
						// B.送出開立發票參數
						$aItems	= array();
						
						// *算出商品各別金額
						$nSub_Total_Real = 0 ;	// 實際無條進位小計
						
						foreach( $aOrder_Product_Tmp as $key => $value)
						{
							$nQuantity 	= ceil($value['quantity']) ;
							$nPrice		= ceil($value['price']) ;
							$nTotal		= $nQuantity * $nPrice	 ; 				// 各商品小計

							$nSub_Total_Real = $nSub_Total_Real + $nTotal ;				// 計算發票總金額
							
						 	$sProduct_Name 	= $value['name'] ;
						 	$sProduct_Note 	= $value['model'] . '-' . $value['product_id'] ;

						 	mb_internal_encoding('UTF-8');
						 	$nString_Limit 	= 10 ;
						 	$nSource_Length = mb_strlen($sProduct_Note);
						 	
						 	if ( $nString_Limit < $nSource_Length )
					                {
					                        $nString_Limit = $nString_Limit - 3;

					                        if ( $nString_Limit > 0 )
					                        {
					                                $sProduct_Note = mb_substr($sProduct_Note, 0, $nString_Limit) . '...';
					                        }
					                }
						 	
							array_push($allpay_invoice->Send['Items'], array('ItemName' => $sProduct_Name, 'ItemCount' => $nQuantity, 'ItemWord' => '批', 'ItemPrice' => $nPrice, 'ItemTaxType' => 1, 'ItemAmount' => $nTotal, 'ItemRemark' => $sProduct_Note )) ;
						}
						
						// *找出sub-total
						$nSub_Total = 0 ;
						foreach( $aOrder_Total_Tmp as $key2 => $value2)
						{
							if($value2['title'] == 'Sub-Total')
							{
								$nSub_Total = (int)$value2['value'];
								break;
							}	
						}
						
						// 無條件位後加總有差異
						if($nSub_Total != $nSub_Total_Real )
						{
							$sMsg_P2 .= ( empty($sMsg_P2) ? '' : WEB_MESSAGE_NEW_LINE ) . '歐付寶電子發票開立，實際金額 $' . $nSub_Total . '， 無條件進位後 $' . $nSub_Total_Real;
						}
						
						//$RelateNumber = 'ALLPAY'. date('YmdHis') . rand(1000000000,2147483647) ; // 產生測試用自訂訂單編號
						$RelateNumber	= $order_id ;
						
						$allpay_invoice->Send['RelateNumber'] 			= $RelateNumber ;
						$allpay_invoice->Send['CustomerID'] 			= '' ;
						$allpay_invoice->Send['CustomerIdentifier'] 		= $sCustomerIdentifier ;
						$allpay_invoice->Send['CustomerName'] 			= $aOrder_Info_Tmp['firstname'] ;
						$allpay_invoice->Send['CustomerAddr'] 			= $aOrder_Info_Tmp['payment_country'] . $aOrder_Info_Tmp['payment_postcode'] . $aOrder_Info_Tmp['payment_city'] . $aOrder_Info_Tmp['payment_address_1'] . $aOrder_Info_Tmp['payment_address_2'];
						$allpay_invoice->Send['CustomerPhone'] 			= $aOrder_Info_Tmp['telephone'] ;
						$allpay_invoice->Send['CustomerEmail'] 			= $aOrder_Info_Tmp['email'] ;
						$allpay_invoice->Send['ClearanceMark'] 			= '' ;
						$allpay_invoice->Send['Print'] 				= $nPrint ;
						$allpay_invoice->Send['Donation'] 			= $nDonation ;
						$allpay_invoice->Send['LoveCode'] 			= $sLove_Code ;
						$allpay_invoice->Send['CarruerType'] 			= '' ;
						$allpay_invoice->Send['CarruerNum'] 			= '' ;
						$allpay_invoice->Send['TaxType'] 			= 1 ;
						$allpay_invoice->Send['SalesAmount'] 			= $nSub_Total_Real ;	
						$allpay_invoice->Send['InvType'] 			= '07' ;
						$allpay_invoice->Send['vat'] 				= '' ;
						$allpay_invoice->Send['InvoiceRemark'] 			= 'OC2_allPayInvoice_1.0.0131' ;
						
						// C.送出與返回
						$aReturn_Info = $allpay_invoice->Check_Out();
									
		
					}catch (Exception $e)
					{
						// 例外錯誤處理。
						$sMsg = $e->getMessage();
					}
					
					// 5.有錯誤訊息或回傳狀態RtnCode不等於1 則不寫入DB
					if( $sMsg != '' || !isset($aReturn_Info['RtnCode']) || $aReturn_Info['RtnCode'] != 1 )
					{
						$sMsg .= '歐付寶電子發票手動開立訊息' ;
						$sMsg .= (isset($aReturn_Info)) ? print_r($aReturn_Info, true) : '' ; 
						
						$json['error'] 		= $sMsg;
						$json['invoice_no'] 	= '';
						
						// A.寫入LOG
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$aOrder_Info_Tmp['order_status_id'] . "', notify = '0', comment = '" . $this->db->escape($sMsg) . "', date_added = NOW()");
	
					}
					else
					{
						// 無條件進位 金額有差異，寫入LOG提醒管理員
						if( $sMsg_P2 != '' )
						{
							$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$aOrder_Info_Tmp['order_status_id'] . "', notify = '0', comment = '" . $this->db->escape($sMsg_P2) . "', date_added = NOW()");
						} 
						
						// A.更新發票號碼欄位
						$invoice_no 		= $aReturn_Info['InvoiceNumber'] ;
						$json['invoice_no'] 	= $invoice_no;
						
						// B.整理發票號碼並寫入DB
						$sInvoice_No_Pre 	= substr($invoice_no ,0 ,2 ) ;
						$sInvoice_No 		= substr($invoice_no ,2) ; 
						
						// C.回傳資訊轉陣列提供history資料寫入
						$sReturn_Info		= '歐付寶電子發票手動開立訊息' ;
						$sReturn_Info		.= print_r($aReturn_Info, true);
						
						//$sInvoice_No_Pre = 'TEST' ;
						//$sInvoice_No	= 0 ;
						
						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . $sInvoice_No . "', invoice_prefix = '" . $this->db->escape($sInvoice_No_Pre) . "' WHERE order_id = '" . (int)$order_id . "'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$aOrder_Info_Tmp['order_status_id'] . "', notify = '0', comment = '" . $this->db->escape($sReturn_Info) . "', date_added = NOW()");
					}
				}
				else
				{
					$json['error'] 	= $sMsg;	
				}	
			}
			else
			{
				
				$invoice_no = $this->model_sale_order->createInvoiceNo($order_id);
				
				if($invoice_no)
				{
					$json['invoice_no'] = $invoice_no;
				}
				else
				{
					$json['error'] = $this->language->get('error_action');
				}
			}

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function install() 
	{
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `invoice_info` (
			  `order_id` INT(11) NOT NULL,
			  `love_code` VARCHAR(50) NOT NULL,
			  `company_write` VARCHAR(10) NOT NULL,
			  `invoice_type` TINYINT(2) NOT NULL,
			  `createdate` INT(10)  NOT NULL
			) DEFAULT COLLATE=utf8_general_ci;");
			
		// 異動電子發票欄位型態
		$this->db->query(" ALTER TABLE `oc_order` CHANGE `invoice_no` `invoice_no` VARCHAR(10) NOT NULL DEFAULT '0'; ");	
		
		
		
		$sFieldName = 'code';
		$query = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "setting LIKE 'code'");
		if ( $query->num_rows == 0 )
		{
			$sFieldName = 'group';
		} 

		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0 , `" . $sFieldName . "` = 'allpayinvoice' , `key` = 'allpayinvoice_mid' , `value` = '2000132';");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0 , `" . $sFieldName . "` = 'allpayinvoice' , `key` = 'allpayinvoice_hashkey' , `value` = 'ejCk326UnaZWKisg';");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0 , `" . $sFieldName . "` = 'allpayinvoice' , `key` = 'allpayinvoice_hashiv' , `value` = 'q9jcZX8Ib9LM8wYk';");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0 , `" . $sFieldName . "` = 'allpayinvoice' , `key` = 'allpayinvoice_autoissue' , `value` = '0';");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0 , `" . $sFieldName . "` = 'allpayinvoice' , `key` = 'allpayinvoice_status' , `value` = '0';");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0 , `" . $sFieldName . "` = 'allpayinvoice' , `key` = 'allpayinvoice_url' , `value` = 'https://einvoice-stage.opay.tw/Invoice/Issue';");
	}
	
	public function uninstall() 
	{
	//	$this->db->query("DROP TABLE IF EXISTS `invoice_info`;");
		
	//	$this->db->query(" ALTER TABLE `oc_order` CHANGE `invoice_no` `invoice_no` INT(10) NOT NULL DEFAULT '0'; ");	

		
		
		
	}
}
