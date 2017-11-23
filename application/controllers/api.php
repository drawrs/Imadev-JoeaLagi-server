<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct(){
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
	}

	function detaillimbah(){
		$limbah = $this->input->post('id');


		$this->db->join("tb_user","tb_user.user_id = tb_limbah.limbah_user");

		$this->db->where('limbah_id',$limbah);

		$hasil = $this->db->get('tb_limbah');

		if($hasil -> num_rows()> 0 ){
				$data['data'] = $hasil->result();
				$data['result '] = true ;
				$data['pesan'] = "oke";
			}
			else{
				$data['result'] = false ;
				$data['pesan'] = "tidak oke";
			}
			echo json_encode($data);
	}

	function datalimbah(){
		$limbah = $this->input->post('type');

		$this->db->where('limbah_type',$limbah);

		$hasil = $this->db->get('tb_limbah');

		if($hasil -> num_rows()> 0 ){
				$data['data'] = $hasil->result();
				$data['result '] = true ;
				$data['pesan'] = "oke";
			}
			else{
				$data['result'] = false ;
				$data['pesan'] = "tidak oke";
			}
			echo json_encode($data);
	}


		function checkUser(){
			$user = $this->input->post('email');

			$email =$this->db->where('user_email',$user);

			$query = $this->db->get('tb_user',$email);

			if($query -> num_rows()> 0){
				$data['result '] = true ;
				$data['pesan'] = "oke";
			}
			else{
				$data['result'] = false ;
				$data['pesan'] = "tidak oke";
			}
			echo json_encode($data);

		}
	
	public function daftar($penjual = ''){ 
		$data = array();
		$nama = $this->input->post('nama');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$hp = $this->input->post('phone');
		$limbah = $this->input->post('limbah');

		
		//check email in di database
		$this->db->where('user_email', $email);
		$this->db->where_not_in('user_status', array(9));
		$q = $this->db->get('tb_user');

		if($q->num_rows() > 0) {
			$data['result'] = 'false';
			$data['msg'] = 'Email anda sudah terdaftar, silahkan untuk login.';
		}else{		
			$simpan = array();
			
			if($penjual != ''){
				$level = 2;
			}else{
				$level = 3;
			}
			$simpan['user_level'] = $level;
			$simpan['user_password'] = md5($password);
			$simpan['user_nama'] = $nama;
			$simpan['user_email'] = $email;
			$simpan['user_kebutuhan'] = $limbah ;
			$simpan['user_hp'] = $hp;

			$status = $this->db->insert('tb_user',$simpan);
			
			if($status){				
				$data['result'] = '1';
				$data['msg'] = 'Pendaftaran berhasil, silahkan untuk login';


				
			}else{
				$data['result'] = 'false';
				$data['msg'] = 'Pendafatran gagal, silahkan coba kembali';
			}

		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}


	public function send_notification($penerima, $message) {
        	$api_key = "AAAAUIHpOyU:APA91bE8VfUwbEdwJab0Bjr51Sd6pZ8W2r1BLP0RmxfgQKVORaAHwtEPVLSQcqPV4KY8JbUyzvsbXUUdMcIcgButdtgofY5VFGdt1f4hYoUWZ7Rj_0Ga_8fgHoZPs5PVYkhXlBzqsG_l";
	        
			$url = 'https://fcm.googleapis.com/fcm/send';
			$fields = array(
		                'registration_ids'  => $penerima,
		                'data'              => array( "message" => $message ),
		                );


				

			$headers = array(
							'Authorization: key=' . $api_key,
							'Content-Type: application/json');						
			//echo $headers ;			
							
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			

			curl_close($ch);
			
			return $result;
    }

    public function login(){ 
		$data = array();
		//$device = $this->input->post('device');
		$email =  $this->input->post("email");
		$password =  $this->input->post("password");

		//$email = 'riyadi.rb@gmail.com';
		//$password = '123456';
		
		if($email == '' || $password == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Silahkan isi email dan atau password anda.';
			echo json_encode($data);
			return;
			
		}
		
		$this->db->where('user_email', $email);
		$this->db->where('user_password', md5($password));
		$this->db->where('user_status', 1);
		$this->db->where('user_level', 3);
		$query = $this->db->get('tb_user');
		if($query->num_rows() > 0){
			$q = $query->row();

		
				$data['result'] = 'true';
				
				$data['data'] = $q;
				$data['msg'] = 'Login berhasil.';
				$data['idUser'] = $q->user_id;
			
		}else{			
			$data['result'] = 'false';
			$data['msg'] = 'Username atau password salah.';
			
		}		
		echo json_encode($data);
	}

    public function registerGcm(){ 
		$data = array();
		
		$this->db->where('id_user', $this->input->post("f_idUser"));
		$data_simpan['user_gcm'] = $this->input->post("f_gcm");
		$simpan = $this->db->update('user', $data_simpan);
		if($simpan){
			$data['result'] = 'true';
			$data['msg'] = 'gcm berhasil disimpan';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'id Gcm gagal disimpan.';
		}
				
		echo json_encode($data);
	}
	

	public function upload($folder = 'produk')
	{
	   $status = "gagal, upload file";
	   $file_element_name = $this->input->post('userfile');
	   #pre($_FILES);
	   $folder = 'img/'.$folder.'/';

	  	
	   	if (!empty($_FILES)) {
	   		buatDir($folder);
		    $file_path = $folder . basename( $_FILES['userfile']['name']);
		    //file_put_contents('f.txt',$file_path);
		    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path)) {
		        $status =  "success";
		    } else{
		        $status =  "fail";
		    }
		}
	   #pre($status);
	   return $status;
	}

	public function insert_booking(){ 
		$data = array();

		// if(!$this->check_sesi()){
		// 	$data['result'] = 'false';
		// 	$data['msg'] = 'Sesi login expired, silahkan login kembali';		
		// 	#pre($this->db->last_query());
		// 	echo json_encode($data);
		// 	return;
		// }

		$idUser = $this->input->post('f_idUser');
		$latAwal = $this->input->post('f_latAwal');
		$lngAwal = $this->input->post('f_lngAwal');
		$awal = $this->input->post('f_awal');
		$latAkhir = $this->input->post('f_latAkhir');
		$lngAkhir = $this->input->post('f_lngAkhir');
		$akhir = $this->input->post('f_akhir');
		$alamat = $this->input->post('f_alamat');
		$jarak = $this->input->post('f_jarak');	
		$tarifUser =$this->input->post('f_tarif');

		//$tarifUser = $jarak * 10000;
		// $tarifDriver = $jarak * 15000;
		$waktu = date('Y-m-d H:i:s');
		$simpan['booking_user'] = $idUser;
		$simpan['booking_tanggal'] = $waktu;
		$simpan['booking_from'] = $awal;
		$simpan['booking_from_lat'] = $latAwal;
		$simpan['booking_from_lng'] = $lngAwal;
		$simpan['booking_from_alamat'] = $alamat;
		$simpan['booking_tujuan'] = $akhir;
		$simpan['booking_tujuan_lat'] = $latAkhir;
		$simpan['booking_tujuan_lng'] = $lngAkhir;
		$simpan['booking_biaya_user'] = $tarifUser;
		$simpan['booking_biaya_driver'] = $tarifUser;
		$simpan['booking_jarak'] = $jarak;

		$status = $this->db->insert('booking',$simpan);
		
		if($status){	
			$idBooking = $this->db->insert_id();			
			$data['result'] = 'true';
			$data['msg'] = 'Booking berhasil';
			
			$data['id_booking'] = $idBooking;
			$data['waktu']=$waktu ;

			//kirimkan pushnotif kepada driver
			$this->push_notif($idBooking);
			//echo $idBooking ;
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Booking gagal, silahkan coba kembali';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function check_booking(){ 
		$data = array();

		$id = $this->input->post('id_booking');


		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		if($id == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Booking tidak dikenali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}
		 
		$this->db->where('booking_status', 2);
		$this->db->where('id_booking', $id);
		$status = $this->db->get('booking');
		
		if($status -> num_rows()> 0){				
			$data['result'] = 'true';
			$data['msg'] = 'data ada';
			$data['data'] = $status -> result();

		}else{
			$data['result'] = 'false';
			$data['msg'] = 'tunggu driver bang';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}


	public function cancel_booking(){ 
		$data = array();

		$id = $this->input->post('id_booking');


		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		if($id == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Booking tidak dikenali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}
		$simpan = array();
		$simpan['booking_status'] = 3; //3. Booking cancel
		$this->db->where('id_booking', $id);
		$status = $this->db->update('booking',$simpan);
		
		if($status){				
			$data['result'] = 'true';
			$data['msg'] = 'Booking berhasil dicancel';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Cancel Booking gagal.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_booking(){ 
		$data = array();

		// if(!$this->check_sesi()){
		// 	$data['result'] = 'false';
		// 	$data['msg'] = 'Sesi login expired, silahkan login kembali';		
		// 	#pre($this->db->last_query());
		// 	echo json_encode($data);
		// 	return;
		// }

		$status = $this->input->post('status');
		$idUser = $this->input->post('f_idUser');
		if($status == 1){
			$this->db->where_in('booking_status', array(1,2));
		}else if($status == 4){
			//$this->db->join('user', 'user.id_user=booking.booking_driver');
			$this->db->where('booking_status', $status);
		}else{
			$this->db->where('booking_status', $status);
		}
		
		$this->db->where('booking_user', $idUser);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking ');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data booking ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data booking tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	// ===================== xrb21 | riyadi.rb@gmail.com ======================
	// Training Android "5 Hari Membangun aplikasi Ojeg Online" IMASTUDIO Jogja
	// Yogyakarta, 8-12 Feb 2016

	// API for DRIVER
	public function login_driver(){ 
		$data = array();
		$device = $this->input->post('device');
		$email =  $this->input->post("f_email");
		$password =  $this->input->post("f_password");


		//$email = 'riyadi.rb@gmail.com';
		//$password = '123456';
		
		if($email == '' || $password == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Silahkan isi email dan atau password anda.';
			echo json_encode($data);
			return;
			
		}
		
		$this->db->where('user_email', $email);
		$this->db->where('user_password', md5x($password));
		$this->db->where('user_level', 2);
		$this->db->where('user_status', 1);
		$query = $this->db->get('user');
		if($query->num_rows() > 0){
			$q = $query->row();

			//delete semua sesi user ini sebelumnya
			$this->db->where('id_user' , $q->id_user);
			$this->db->update('sesi', array('sesi_status' => 9));					
			//create token
			$key = md5(date('Y-m-d H:i:s').$device);
			//masukkan kedlam tabel sesi
			$simpan = array();
			$simpan['sesi_key'] =  $key;
			$simpan['id_user'] = $q->id_user;
			$simpan['sesi_device'] = $device;
			$status = $this->db->insert('sesi', $simpan);
			if($status){
				$data['result'] = 'true';
				$data['token'] =  $key;
				$data['data'] = $q;
				$data['msg'] = 'Login berhasil.';
				$data['idUser'] = $q->id_user;
			}else{
				$data['result'] = 'false';
				$data['token'] = '';
				$data['idUser'] = '';
				$data['msg'] = 'Error create sesi login, Silahkan coba lagi.';
			}
		}else{			
			$data['result'] = 'false';
			$data['msg'] = 'Username atau password salah.';
			
		}		
		echo json_encode($data);
	}

	public function get_request_booking(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$this->db->join('user', 'user.id_user=booking.booking_user');
		$this->db->where('booking_status', 1);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data booking ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data booking tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

public function booking_Driver(){
	$data = array();
	//inputan id driver
	$iduser = $this->input->post('f_idUser');

	//get data table booking sesuai dengan id driver dan booking status 2
	$this->db->where('booking_driver',$iduser);
	$this->db->where('booking_status',2);
	$q =$this->db->get('booking');

	if($q-> num_rows>0){
		$data['result'] = 'false';
			$data['msg'] = 'silakan di complete orderan lama anda dulu';
	}
	else{
		$data['result'] = 'true';
			$data['msg'] = 'ok';
			

	}
	echo json_encode($data);


}

	public function take_booking(){ 
		$data = array();
		$id =$this->input->post('idbooking');
	$idUser = $this->input->post('f_idUser');
		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		if($id == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Booking tidak dikenali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		//check apakah booking sudah ada yang ambil atau tidak
		$this->db->where('id_booking', $id);
		$this->db->where('booking_status', 1);
		$q = $this->db->get('booking');
		if($q->num_rows() == 0){
			$data['result'] = 'false';
			$data['msg'] = 'Booking sudah ada yang take,coba booking yang lainnya.';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		
		$simpan = array();
		$simpan['booking_status'] = 2; //2. Booking diambil oleh driver
		$simpan['booking_driver'] = $idUser;
		$simpan['booking_take_tanggal'] = date('Y-m-d H:i:s');
		$this->db->where('id_booking', $id);
		$status = $this->db->update('booking',$simpan);
		
		if($status){				
			$data['result'] = 'true';
			$data['msg'] = 'Take Booking berhasil';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Take Booking gagal.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_handle_booking(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$this->db->join('user', 'user.id_user=booking.booking_user');
		$this->db->where('booking_status', 2);
		$this->db->where('booking_driver', $idUser);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data handle booking ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data handle booking tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

//LAYOUT DETAIL REQUEST
	//https://pastebin.com/Y8F2RaXj
	public function complete_booking(){ 
		$data = array();

		$id =$this->input->post('idbooking');
		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		if($id == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Booking tidak dikenali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		//check apakah booking sudah ada yang ambil atau tidak
		$idUser = $this->input->post('f_idUser');
		$this->db->where('id_booking', $id);
		$this->db->where('booking_status', 2);
		$this->db->where('booking_driver', $idUser);
		$q = $this->db->get('booking');
		if($q->num_rows() == 0){
			$data['result'] = 'false';
			$data['msg'] = 'Ini bukan data booking ada.';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$simpan = array();
		$simpan['booking_status'] = 4; //2. Booking diambil oleh driver
		$simpan['booking_complete_tanggal'] = date('Y-m-d H:i:s');
		$this->db->where('id_booking', $id);
		$status = $this->db->update('booking',$simpan);
		
		if($status){				
			$data['result'] = 'true';
			$data['msg'] = 'Booking Completed';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Complete booking gagal.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_complete_booking(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$this->db->join('user', 'user.id_user=booking.booking_user');
		$this->db->where('booking_status', 4);
		$this->db->where('booking_driver', $idUser);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data complete ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data complete tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	//send totikasi kepada driver
	public function push_notif($idBooking){
		$last = $idBooking;
		if (isCurl()){
			//ambil semua id dari data gcm
			$datax = array();
			$this->db->join('user', 'user.id_user=booking.booking_user');
			$this->db->where('booking_status', 1);
			$this->db->where('id_booking', $idBooking);
			$q = $this->db->get('booking');
			
			if($q->num_rows() > 0){				
				$datax['result'] = 'true';
				$datax['msg'] = 'Data booking ada';
				$datax['data'] = $q->row();
			}else{
				$datax['result'] = 'false';
				$datax['msg'] = 'Data booking tidak ada.';
			}


		//echo json_encode($datax);
			
			$this->db->where('user_level', 2);
			$this->db->where('user_status', 1);
			$this->db->where_not_in('user_gcm', array(""));
			$qq = $this->db->get('user');
			#pre($datax);	
			if($qq->num_rows() > 0){

				$receivers = array();
				$message = array("datax" => $datax);

				//echo $message ; 
				foreach ($qq->result() as $r) {
					$receivers[] = $r->user_gcm;
				}
				#pre($receivers);
				$hasil = $this->send_notification($receivers, $message);
				#pre($hasil);
			}else{
				echo "data tidak ada";
			}
		}else{
			$pesan .= ' Curl tidak aktif tidak bisa kirim notifikasi berita ke user.';
		}
	}

	public function insert_posisi(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$lat = $this->input->post('f_lat');
		$lng = $this->input->post('f_lng');
		
		$waktu = date('Y-m-d H:i:s');
		$simpan['tracking_driver'] = $idUser;
		$simpan['tracking_waktu'] = $waktu;
		$simpan['tracking_lat'] = $lat;
		$simpan['tracking_lng'] = $lng;

		$status = $this->db->insert('tracking',$simpan);
		
		if($status){	
			$data['result'] = 'true';
			$data['msg'] = 'input tracking berhasil';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'input tracking gagal, silahkan coba kembali';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_driver(){ 
		$data = array();

		$iddriver = $this->input->post('id');

		//$this->db->join('user', 'user.id_user=tracking.tracking_driver');
		$this->db->where('tracking_status', 1);
		$this->db->where('tracking_driver',$iddriver);
		$this->db->order_by('id_tracking', 'DESC');
		$this->db->limit(1);
		
		$q = $this->db->get('tracking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data driver ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data driver tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function insert_review(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$driver = $this->input->post('f_driver');
		$idBooking = $this->input->post('f_idBooking');
		$rating = $this->input->post('f_ratting');
		$comment = $this->input->post('f_comment');
		
		$waktu = date('Y-m-d H:i:s');
		$simpan['review_driver'] = $driver;
		$simpan['review_user'] = $idUser;
		$simpan['review_waktu'] = $waktu;
		$simpan['review_rating'] = $rating;
		$simpan['review_komentar'] = $comment;
		$simpan['review_booking'] = $idBooking;

		$status = $this->db->insert('review',$simpan);
		
		if($status){	
			$data['result'] = 'true';
			$data['msg'] = 'input review berhasil';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'input review gagal, silahkan coba kembali';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}


	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */