<?php
class Home extends _adminController{
	public function __construct(){
		parent::__construct();
		$this->template = "panelbackend/main";
		$this->layout = "panelbackend/layout1";
	}
	function _actionIndex(){
		$this->data['page_title'] = 'Statistik Pengunjung';
		$this->data['visitors'] = $this->auth->statistikVisitor();
		$this->View("panelbackend/home");
	}

	function _actionProfile(){

		if($_SESSION[SESSION_APP]['is_admin']!=true){
				$this->Error403();
				exit();
		}
		$this->model = new SysUserModel();

		$id=1;
		$this->data['edited'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row'] && $id)
			$this->NoData();
		
		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$valid = $this->_isValidProfile();
			if(!$valid){
				$this->View('panelbackend/profile');
				return;
			}

			$record = array();
			$record['username'] = $this->post['username'];
			$record['name'] = $this->post['name'];

			if(!empty($this->post['password']))
			{
				$record['password']=sha1(md5($this->post['password']));
			}

            $this->setLogRecord($record,$id);

			if ($id) {
				$return = $this->model->Update($record, "user_id = $id");
				if ($return) {
					$this->SetFlash('suc_msg', $return['success']);
					URL::Redirect("panelbackend/home/profile");					
				}
				else {
					$this->data['row'] = $record;
					$this->data['err_msg'] = "Data gagal diubah";
				}
			}
		}
				
		$this->View('panelbackend/profile');
	}

	function _isValidProfile(){

		$rules = array(
		   array(
				 'field'   => 'username',
				 'label'   => 'Username',
				 'rules'   => 'required'
			  ),
		   array(
				 'field'   => 'name',
				 'label'   => 'name Lengkap',
				 'rules'   => 'required'
			  )
		);
		if($isnew=="true"){
			$rules[]=array(
				'field'   => 'password',
				'label'   => 'Password',
				'rules'   => 'required'
			);
		}

		$validation = new FormValidation($rules);

		$error_msg = '';
		if ($validation->run() == FALSE)
		{
			$error_msg .= $validation->GetError();
		}

		if($this->post['password']<>$this->post['confirmpassword']){
			if($error_msg) $error_msg.="<br/>";
			
			$error_msg .= "Konfirmasi password salah";
		}

		if($error_msg){
			$this->data['row'] = $this->post;
			$this->SetFlash('err_msg', $error_msg);	
			return false;
		}

		return true;
	}



	function _actionProfileUser(){

		$this->model = new PetugasModel();

		$id=$_SESSION[SESSION_APP]['id_petugas'];
		$this->data['edited'] = true;

		$this->data['row'] = $this->model->GetByPk($id);
		if (!$this->data['row'] && $id)
			$this->NoData();
		
		## EDIT HERE ##
		if ($this->post['act'] === 'save') {

			$valid = $this->_isValidProfileUser();
			if(!$valid){
				$this->View('panelbackend/profile_user');
				return;
			}

			$record = array();
			$record['username'] = $this->post['username'];
			$record['nama'] = $this->post['nama'];
			$record['telp'] = $this->post['telp'];
			$record['alamat'] = $this->post['alamat'];

			if(!empty($this->post['password']))
			{
				$record['password']=sha1(md5($this->post['password']));
			}

            $this->setLogRecord($record,$id);

			if ($id) {
				$return = $this->model->Update($record, "id_petugas = $id");
				if ($return) {
					$this->SetFlash('suc_msg', $return['success']);
					URL::Redirect("panelbackend/home/profile_user");					
				}
				else {
					$this->data['row'] = $record;
					$this->data['err_msg'] = "Data gagal diubah";
				}
			}
		}
				
		$this->View('panelbackend/profile_user');
	}

	function _isValidProfileUser(){

		$rules = array(
		   array(
				 'field'   => 'username',
				 'label'   => 'Username',
				 'rules'   => 'required'
			  ),
		   array(
				 'field'   => 'telp',
				 'label'   => 'Telepon',
				 'rules'   => 'required'
			  ),
		   array(
				 'field'   => 'alamat',
				 'label'   => 'Alamat',
				 'rules'   => 'required'
			  ),
		   array(
				 'field'   => 'nama',
				 'label'   => 'Nama',
				 'rules'   => 'required'
			  )
		);

		$validation = new FormValidation($rules);

		$error_msg = '';
		if ($validation->run() == FALSE)
		{
			$error_msg .= $validation->GetError();
		}

		if($this->post['password']<>$this->post['confirmpassword']){
			if($error_msg) $error_msg.="<br/>";
			
			$error_msg .= "Konfirmasi password salah";
		}

		if($error_msg){
			$this->data['row'] = $this->post;
			$this->SetFlash('err_msg', $error_msg);	
			return false;
		}

		return true;
	}
}
