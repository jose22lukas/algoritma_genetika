<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Enduser extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->helper('url', 'form');
		$this->load->model('Golongan_model');
		$this->load->model('dinas');
		$this->load->model('jabatan');
		$this->load->model('User_model');

		ini_set('display_error','off');
		error_reporting(0);
	}

    public function index(){
		
		$this->load->library('form_validation');
		$this->load->view('enduser/home');
		
	}

	public function hitungBiaya(){

		$mygolongan = $this->Golongan_model->get_by_id($this->session->userdata('id_golongan'));
		$myjabatan = $this->jabatan->get_by_id($this->session->userdata('id_jabatan'));

	
		if($myjabatan->uang_dinas > $mygolongan->uang_dinas){
			$biaya = array('uang_dinas'=>$myjabatan->uang_dinas);
		}else{
			$biaya = array('uang_dinas'=>$mygolongan->uang_dinas);
		}
		return $biaya;

	}

	public function pilih(){

		$data = array(
			'name' => $this->session->userdata('name'), 
			'nip' => $this->session->userdata('nip'), 
			'nama_hotel' => $this->input->post('nama_hotel'), 
			'alamat_hotel' => $this->input->post('location'), 
			'lama' => $this->input->post('lama'), 
			'tanggal' => $this->input->post('tanggal'), 
			'harga' => $this->input->post('harga'), 
			'total' => $this->input->post('total'), 
			'stars' => $this->input->post('stars'), 
		);
		$this->dinas->insertDinas($data);
		// redirect(site_url('dinas/index.php/enduser'));
		echo '<script type="text/javascript">alert("Hotel berhasil dipilih")</script>';
		redirect('Enduser','refresh');
		// var_dump($data);
	}
		
public function search(){

	$this->load->helper('url', 'form');
	$lokasi = $this->input->post('lokasi');
	$checkin = date("Y-m-d", strtotime($this->input->post('checkin')));
	$checkout = date("Y-m-d", strtotime($this->input->post('checkout')));

	$plafon = $this->input->post('plafon');

	$dataAnggaran = $this->hitungBiaya();

	$dataHotel = json_decode($this->curlHotel($lokasi,$checkin,$checkout));

	$reko_hotel=$this->rekomendasi_hotel($dataHotel, $dataAnggaran );


	$date1=date_create($checkout);
	$date2=date_create($checkin);
	$diff=date_diff($date1,$date2,true);

	$d = array(
		0 => $reko_hotel['hotel'][0],
		1 => $reko_hotel['hotel'][1],
		2 => $reko_hotel['hotel'][2],

	);
	
	$data['hotel_total'] = $reko_hotel['hotel']['hotel_total'];
	$data['fitness'] = $reko_hotel['hotel']['fitness'];
	$data['probabilitas'] = $reko_hotel['hotel']['probabilitas'];
	$data['akumulatif'] = $reko_hotel['hotel']['akumulatif'];

	$data['hotel_reko_view'] = $d ;
	$data['checkout'] = $checkout;
	$data['checkin'] = $checkin;
	$data['lama'] = $diff->days;
	$this->load->view('enduser/hotel',$data);
	
}

public function curlHotel($lokasi, $checkin, $checkout) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
  		CURLOPT_URL => 'http://engine.hotellook.com/api/v2/cache.json?location='.$lokasi.'&checkIn='.$checkin.'&checkOut='.$checkout.'&currency=idr&limit=1000',
  		CURLOPT_RETURNTRANSFER => true,
  		CURLOPT_ENCODING => "",
  		CURLOPT_MAXREDIRS => 10,
  		CURLOPT_TIMEOUT => 30,
  		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  		CURLOPT_CUSTOMREQUEST => "GET",
  		CURLOPT_POSTFIELDS => "",
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
  		echo "cURL Error #:" . $err;
		} else {
			return $response;
		}

}

public function rand_float($st_num,$end_num)
	{
		if ($st_num>$end_num) return false;
		return mt_rand($st_num*1000000, $end_num*1000000)/1000000;
	}

public function rekomendasi_hotel($dataHotel, $dataAnggaran){

	// $data = $this->User_model->get_all();
	// $data = json_decode($this->curlHotel());
	$data = $dataHotel;
	// print_r($data);

	// $dataAnggaran = $this->Golongan_model->get_all();

	// $dataAnggaran = $this->Golongan_model->get_by_id($this->session->userdata('id_golongan'));
	// data = array(

	// 	'priceAvg' => $data,
		
	// );



	shuffle($data);
	unset($param);
	$param = array();
	$temp = array();
	$count_max = 0;
	$tot_hitung = 0;
	$hitung_akumulatif = 0;
	$hasil_seleksi = 0;
	$random_parent = array();
	$parent = array();
	$parent_mutation = array();

	if (empty($param)) {			
			for($i = 0; $i< count($data); $i++){
				if ($count_max < 3) {
					array_push($temp, $data[$i]);
					$count_max++;
				}
				else{
					array_push($param, $temp);
					unset($temp);
					$temp = array();
					$count_max = 0;
					array_push($temp, $data[$i]);
					$count_max++;
				}
			}
		}

	//cari nilai fitness
	for($gen = 0; $gen<50; $gen++){ //jumlah generasi yang ditentukan
		for($i = 0; $i <count($param); $i++){
			$hotel = 0;
			$harga_hotel= $data[$i]->priceAvg;
			for($a=0; $a<=2; $a++){
				$hotel += $param[$i][$a]->priceAvg;
			}
			$param[$i]['hotel_total'] = $hotel;
			$param[$i]['fitness'] = 1/(($dataAnggaran['uang_dinas']-$data[$i]->priceAvg)+1);
			$tot_hitung += $param[$i]['fitness'];
		}

	//akumulatif probabilitas

	for($i = 0; $i <count($param); $i++){ 

		$param[$i]['probabilitas'] = $param[$i]['fitness']/$tot_hitung;
		if ($hitung_akumulatif == 0) {
			$hitung_akumulatif = $param[$i]['probabilitas'];
		}else{
			$hitung_akumulatif += $param[$i]['probabilitas'];
		}
		$param[$i]['akumulatif'] = $hitung_akumulatif;
	}

	//seleksi roulette wheel

	for($i = 0; $i <count($param); $i++){
		$hasil = array();
		for($j = 0; $j <count($param); $j++){
			$selisih = $param[$j]['akumulatif']-$this->rand_float(0,1);
			$hasil[$j] = $selisih;
		}
		$values = array_filter($hasil, function($v) { return $v >= 0; });
		$min = min($values);
		$replace = array_search($min, $hasil);
		$param[$i] = $param[$replace];

	}

	//cari kandidat parent untuk crossover dan mutasi

	for($i = 0; $i <count($param); $i++){
			$random_parent[$i] = $this->rand_float(0,1);
		}		

	//filter dari probabilitas crossover

	for($i = 0; $i <count($param); $i++){
		if ($random_parent[$i] < 0.8) {
			array_push($parent, array_search($random_parent[$i], $random_parent));
		}
	}

	//crossover
	if(!empty($parent)){
		for($i = 0; $i<count($parent);$i++){
			$shuffle_parent = array();
			array_push($shuffle_parent, $parent);
			shuffle($shuffle_parent);
			$posisi_cross = rand(0, 1);
			for($j = 0; $j<=$posisi_cross; $j++){
				if ($posisi_cross[$i]==0) {
					$sp = $shuffle_parent[0][$i];
					$pc = 0;
					$p = $parent[$i];
					$param[$sp][$pc] = $param[$p][$pc];
				}
				else{
					$sp = $shuffle_parent[0][$i];
					$pc = 1;
					$p = $parent[$i];
					$param[$sp][$pc] = $param[$p][$pc];
				}
				
			}

		}
	}
	//filter dari probabilitas mutasi

	for($i = 0; $i <count($param); $i++){
		if ($random_parent[$i] < (1/count($param))) {
			array_push($parent_mutation, array_search($random_parent[$i], $random_parent));
		}
	}

	//mutasi

	if(!empty($parent_mutation)){
		for($i = 0; $i<count($parent_mutation); $i++){
			$posisi_gen = rand(0, 2);
			for($j = 0; $j<=$posisi_gen; $j++){
				if ($posisi_gen[$i]==0) {
					$pc = 0;
					$p = $parent_mutation[$i];
					$hotel_mutasi = $param[$p][$pc]->priceAvg;
					$binary = decbin($hotel_mutasi);
					$arr_bin = str_split(str_pad($binary, 8, 0, STR_PAD_LEFT));
					$posisi_bit = rand(0, 7);
					if($arr_bin[$posisi_bit]=="0"){
						$arr_bin[$posisi_bit] = "1";
						$final = bindec(implode('', $arr_bin));
						$param[$p][$pc]->priceAvg = $final;						}
					else{
						$arr_bin[$posisi_bit] = "0";
						$final = bindec(implode('', $arr_bin));
						$param[$p][$pc]->priceAvg = $final;
					}
				}
				else if($posisi_gen[$i]==1){
					$pc = 1;
					$p = $parent_mutation[$i];
					$hotel_mutasi = $param[$p][$pc]->priceAvg;
					$binary = decbin($hotel_mutasi);
					$arr_bin = str_split(str_pad($binary, 8, 0, STR_PAD_LEFT));
					$posisi_bit = rand(0, 7);
					if($arr_bin[$posisi_bit]=="0"){
						$arr_bin[$posisi_bit] = "1";
						$final = bindec(implode('', $arr_bin));
						$param[$p][$pc]->priceAvg = $final;
					}
					else{
						$arr_bin[$posisi_bit] = "0";
						$final = bindec(implode('', $arr_bin));
						$param[$p][$pc]->priceAvg = $final;
					}
				}
				else{
					$pc = 2;
					$p = $parent_mutation[$i];
					$hotel_mutasi = $param[$p][$pc]->priceAvg;
					$binary = decbin($hotel_mutasi);
					$arr_bin = str_split(str_pad($binary, 8, 0, STR_PAD_LEFT));
					$posisi_bit = rand(0, 7);
					if($arr_bin[$posisi_bit]=="0"){
						$arr_bin[$posisi_bit] = "1";
						$final = bindec(implode('', $arr_bin));
						$param[$p][$pc]->priceAvg = $final;
					}
					else{
						$arr_bin[$posisi_bit] = "0";
						$final = bindec(implode('', $arr_bin));
						$param[$p][$pc]->priceAvg = $final;
					}
				}										
			}
		}
	}

	//elitisme       

			for($i = 0; $i < count($param); $i++){
				$temp_kandidat[$i] = $param[$i]['fitness'];
			}
				$kandidat = array_search(max($temp_kandidat), $temp_kandidat);
				$eliminasi = array_search(min($temp_kandidat), $temp_kandidat);
				if(empty($elitisme)){
				$elitisme['hotel'] = $param[$kandidat];
				}
				else{
					if($elitisme['hotel']['fitness']<$param[$kandidat]['fitness']){
						$elitisme['hotel'] = $param[$kandidat];
					}
					else{
						$param[$eliminasi] = $elitisme['hotel'];
					}
				}
			
		}
		// if (empty($this->session->userdata('elitisme'))) {
		// 	$this->session->set_userdata('elitisme', $elitisme);
		// }

		return $elitisme;	
		// $this->load->view('enduser/hotel.php');
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('Login');
	}

}
