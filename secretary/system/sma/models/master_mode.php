<?php defined('BASEPATH') OR exit('No direct script access allowed');

class master_mode extends CI_Model
{

    public function getToko() {
        $q = $this->db->query('select id,toko,alamat,nm_kota wilayah, no_telp,ppn from toko,kd_kota where `kd_kota`=`wilayah`');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function cekToko($nama)
	{
		$nama = trim($nama);
		if ($nama == "") return false;
		$q = $this->db->query('select id from toko where ucase(toko) =ucase("'.$nama.'")');
		if ($q->num_rows() > 0) return true;
		return false;
	}
    public function cekToP($nama)
	{
		$nama = trim($nama);
		if ($nama == "") return false;
		$q = $this->db->query('select id from termpayment where ucase(nama) =ucase("'.$nama.'")');
		if ($q->num_rows() > 0) return true;
		return false;
	}
	public function cekSatuan($nama)
	{
		$nama = trim($nama);
		if ($nama == "") return false;
		$q = $this->db->query('select id from satuan where ucase(satuan) =ucase("'.$nama.'")');
		if ($q->num_rows() > 0) return true;
		return false;
	}
    public function getgroup() {
        $q = $this->db->query('select * from sma_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getSales() {
        $q = $this->db->query('select * from group_sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }public function getSatuan() {
        $q = $this->db->query('select * from satuan');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getProduk() {
        $q = $this->db->query('select * from sma_products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getwilayah(){
        $q = $this->db->query('select * from kd_kota');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getwilayah_terpilih(){
        $q = $this->db->query('select distinct(wilayah) id,nm_kota from toko A, kd_kota B where A.wilayah = B.kd_kota');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getPromo(){
        $q = $this->db->query('select * from promo order by id desc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getExpedisi(){
        $q = $this->db->query('select * from expedisi order by nama asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getExpedisi1($id){
        $q = $this->db->query('select * from expedisi where id='.$id.'order by nama asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getTerm(){
        $q = $this->db->query('select * from termpayment order by nama asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function cekTerm($nama,$lamawaktu)
	{
		$nama = trim($nama);
		$lamawaktu = trim($lamawaktu);
		$q = $this->db->query('select id from termpayment where ucase(nama) =ucase("'.$nama.'") and ucase(lamawaktu) =ucase("'.$lamawaktu.'")');
		if ($q->num_rows() > 0) return true;
		return false;
	}
	public function getCustomer(){
        $q = $this->db->query('select A.id, A.nama,alamat,notelp,batas,B.nama top, catatan, lamawaktu, tagihan from customer A, termpayment B where A.top = B.id order by nama asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getSupplier(){
        $q = $this->db->query('select A.id, A.nama,alamat,notelp,B.nama top, catatan, lamawaktu, hutang from supplier A, termpayment B where A.top = B.id order by nama asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getAlamatKirim($id){
        $q = $this->db->query('select A.id, A.nama, B.nm_kota wilayah,alamat,expedisi from alamat_kirim A, kd_kota B where A.id_customer = '.$id.' and A.wilayah = B.kd_kota order by A.wilayah asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getHakAkses(){
        $q = $this->db->query('select * from sma_groups order by name asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getPengguna(){
		$q = $this->db->query('select * from sma_users A,sma_groups B, toko C where A.group_id = B.id and A.toko = C.id order by name asc');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getBahanUtama(){
		$q = $this->db->query('select * from bahan_utama A,satuan B where A.satuan = B.id order by kategori');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getBahanKaos(){
		$q = $this->db->query('select * from bahan_kaos A,satuan B where A.satuan = B.id order by A.nama');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}

}
