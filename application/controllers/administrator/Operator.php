<?php

class Operator extends CI_Controller{

private function is_loggedIn() {
        if (!isset($this->session->userdata['username'])){
            $this->session->set_flashdata('pesan','<div class="alert alert-warning alert-danger dismissible fade show" role="alert">
                Anda Belum Login!
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
                 </button>
                </div>');
            redirect('administrator/auth');
        }
    }

    private function is_admin() {
        if($this->session->userdata['level'] !== 'admin'){
            $this->session->set_flashdata('pesan','<div class="alert alert-warning alert-danger dismissible fade show" role="alert">
                Anda Belum Login!
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
                 </button>
                </div>');
            redirect('administrator/auth');
        }
    }

    public function index()
    {
        $data['title'] = "data operator";
        $data['operator']    = $this->operator_model->tampil_data()->result();
        $this->load->view('template_administrator/header');
        $this->load->view('template_administrator/sidebar');
        $this->load->view('administrator/operator',$data);
        $this->load->view('template_administrator/footer');
    }

    public function input()
    {
        $data = array(
            'tanggal'  => set_value('tanggal'),
            'tgl'  => set_value('tgl'),
			'no'  => set_value('no'),
            'waktu'  => set_value('waktu'),
            'pulang'  => set_value('pulang'),
			'nama'   => set_value('nama'),
			'bidang'   => set_value('bidang'),
			'kegiatan'   => set_value('kegiatan'),
            'lokasi'   => set_value('lokasi'),
			'dokumentasi'   => set_value('dokumentasi'),
        );
        $this->load->view('template_administrator/header');
        $this->load->view('template_administrator/sidebar');
        $this->load->view('administrator/operator_form',$data);
        $this->load->view('template_administrator/footer');
    }
    public function input_aksi()
    {
        // rules loaded
        $this->_rules();

        // check if form not valid / return FALSE
        if($this->form_validation->run() == FALSE) {
            echo validation_errors();
            $this->input();
        }

        // form is valid
        else 
        {
            echo "form valid\n";
            // assign form input values to variables
            $nama 			= $this->input->post('nama');
			$tgl 			= $this->input->post('tgl');
            $lokasi         = $this->input->post('lokasi');
            $waktu          = $this->input->post('waktu');
            $pulang         = $this->input->post('pulang');
			$bidang 		= $this->input->post('bidang');
			$kegiatan 		= $this->input->post('kegiatan');

            // codeigniter's upload config
            $config['upload_path'] = './assets/upload/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2000; 

            // initialize upload with predefined config
            $this->upload->initialize($config);

            // check if upload is successful
            if(!$this->upload->do_upload('dokumentasi')) {
                echo "upload gagal";
                $this->input();
            }
            else {
                $dokumentasi = $this->upload->data('file_name');
                $data = array(
                    'waktu' => $waktu,
                    'tgl' => $tgl,
                    'pulang' => $pulang,
                    'nama' => $nama,
                    'bidang' => $bidang,
                    'kegiatan' => $kegiatan,
                    'lokasi' => $lokasi,
                    'dokumentasi' => $dokumentasi,
                );
                $this->operator_model->input_data($data);
                $this->session->set_flashdata('pesan',
                    '<div 
                        class=" alert 
                                alert-warning 
                                alert-danger 
                                dismissible 
                                fade 
                                show
                                " 
                        role="alert">
                    Data Berhasil Ditambahkan!
                    <button 
                        type="button" 
                        class="close" 
                        data-dismiss="alert" 
                        aria-label="Close">
                    <span 
                        aria-hidden="true">
                    &times;
                    </span>
                    </button>
                    </div>');
                    redirect('administrator/operator');
            }
        }
    }
            /*
            $dokumentasi    = $_FILES['dokumentasi'];
            if($dokumentasi['size'] == 0) { 
                echo "file empty\n";
            } 
            elseif($dokumentasi['error'] > 0) { 
                if ($dokumentasi['error'] == 1) {
                    echo "filesize exceeds php maxlimit\n";
                }
                elseif ($dokumentasi['error'] == 2) {
                    echo "filesize exceeds html form maxlimit\n";
                }
                elseif ($dokumentasi['error'] == 3) {
                    echo "file partially uploaded\n";
                }
                elseif ($dokumentasi['error'] == 4) {
                    echo "No file uploaded\n";
                }
                elseif ($dokumentasi['error'] == 6) {
                    echo "Missing temp folder\n";
                }
                elseif ($dokumentasi['error'] == 7) {
                    echo "failed write file to disk\n";
                }
                elseif ($dokumentasi['error'] == 8) {
                    echo "upload process stopped\n";
                }
            }
            else {
                echo "file not empty and uploaded with success\n";
                echo $_FILES['dokumentasi']['name']."\n";
                $config['upload_path'] = './assets/upload/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2000; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('dokumentasi'))
                {
                    echo "Upload Gagal\n"; 
                } 
                else {
                    echo "upload berhasil\n";
                    $dokumentasi = $this->upload->data('file_name');
                    $data = array(
                        'nama'          => $nama,
                        'bidang'        => $bidang,
                        'kegiatan'      => $kegiatan,
                        'dokumentasi'   => $dokumentasi
                    );
                    $this->kinerja_model->input_data($data);
                }
            }   
             */
            /*$this->session->set_flashdata('pesan','<div class="alert alert-warning alert-danger dismissible fade show" role="alert">
                    Data Berhasil Ditambahkan!
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                    </div>');
             */
            //redirect('administrator/operator');
    
    public function _rules()
    {
        $this->form_validation->set_rules('nama','nama','required',['required' => 'Nama Wajib Diisi']);
        $this->form_validation->set_rules('waktu','waktu','required',['required' => 'Nama Wajib Diisi']);
        $this->form_validation->set_rules('bidang','bidang','required',['required' => 'Bidang Wajib Diisi']);
    }
    
    public function print_form(){

        $this->is_loggedIn();
        $this->is_admin();

        $data['driver']= $this->user_model->getDriver()->result();
        $this->load->view('template_administrator/header.php');
        $this->load->view('template_administrator/sidebar.php');
        $this->load->view('administrator/operator_print_form',$data);
        $this->load->view('template_administrator/footer.php');
    }

    public function print_dinas() {
        $this->is_loggedIn();
        $this->is_admin();
        $this->_rules_print(); 
        if($this->form_validation->run() == FALSE){
            $this->print_form();
        } else {
            $this->load->library('Pdf');

            $name = $this->input->post('username');
            $startDate = $this->input->post('starting_date');
            $endDate = $this->input->post('end_date');
            $data = $this->operator_model->getSpecificDriver($name,$startDate,$endDate)->result();

            $pdf = new Pdf();
            $pdf->AddPage("L");

            $pdf->SetFont('Times','BU',14);
            $pdf->Cell(0,0,'Kinerja PJLP Bidang Pengemudi Operasional Lapangan',0,1,'C');
            $pdf->ln(5);
            
            $pdf->Nama($this->input->post('username'));
            $pdf->Jabatan('pengemudi operasional lapangan');
            $pdf->Tanggal($this->input->post('starting_date'),$this->input->post('end_date'));
            $header = ['Tanggal','Waktu','Kegiatan','Lokasi'];
            // total width = 205
            $pdf->TabelKinerja($header,$data,[30,15,100,60]);

            $pdf->Output();
        }
    }

    // obosolete
    public function print(){

        $this->is_loggedIn();
        $this->is_admin();

        $data['operator']   = $this->operator_model->tampil_data("operator")->result();
        $this->load->view('administrator/print_operator',$data);
    }

    public function excel(){
        $data['operator']   = $this->operator_model->tampil_data("operator")->result();

        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

        $object = new PHPExcel();

        $object->getProperties()->setCreator("kitsui");
        $object->getProperties()->setLastModifiedBy("kitsui");
        $object->getProperties()->setTitle("Data Operator");
// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
    $style_col = array(
      'font' => array('bold' => true), // Set font nya jadi bold
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ),
      'borders' => array(
        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
      )
    );
    // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
    $style_row = array(
      'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ),
      'borders' => array(
        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
      )
    );
    $object->setActiveSheetIndex(0)->setCellValue('F1', "DAFTAR KEGIATAN HARIAN PJLP "); // Set kolom A1 dengan tulisan "DATA SISWA"
    $object->getActiveSheet()->getStyle('F1')->getFont()->setBold(TRUE); // Set bold kolom A1
    $object->getActiveSheet()->getStyle('F1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
    $object->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
     $object->setActiveSheetIndex(0)->setCellValue('F2', "UNIT ALKAL DINAS BINA MARGA PROVINSI DKI JAKARTA "); // Set kolom A1 dengan tulisan "DATA SISWA"
    $object->getActiveSheet()->getStyle('F2')->getFont()->setBold(TRUE); // Set bold kolom A1
    $object->getActiveSheet()->getStyle('F2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
    $object->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
     $object->setActiveSheetIndex(0)->setCellValue('F3', "2021"); // Set kolom A1 dengan tulisan "DATA SISWA"
    $object->getActiveSheet()->getStyle('F3')->getFont()->setBold(TRUE); // Set bold kolom A1
    $object->getActiveSheet()->getStyle('F3')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
    $object->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
    $object->getActiveSheet()->setAutoFilter('A4:G4');
        $object->setActiveSheetIndex(0);
        $object->getActiveSheet()->setCellValue('A4', 'Tgl/Hari');
        $object->getActiveSheet()->setCellValue('B4', 'No');
        $object->getActiveSheet()->setCellValue('C4', 'Nama');
        $object->getActiveSheet()->setCellValue('D4', 'Waktu');
        $object->getActiveSheet()->setCellValue('E4', 'Bidang');
        $object->getActiveSheet()->setCellValue('F4', 'Kegiatan');
        $object->getActiveSheet()->setCellValue('G4', 'Lokasi');
	   $object->getActiveSheet()->setCellValue('H4', 'Keterangan');

        $baris = 5;
        $no = 1;

        foreach ($data['operator'] as $k) {
            $object->getActiveSheet()->setCellValue('B'.$baris, $no++);
            $object->getActiveSheet()->setCellValue('A'.$baris, $k->tgl);
            $object->getActiveSheet()->setCellValue('C'.$baris, $k->nama);
            $object->getActiveSheet()->setCellValue('D'.$baris, $k->waktu);
            $object->getActiveSheet()->setCellValue('E'.$baris, $k->bidang);
            $object->getActiveSheet()->setCellValue('F'.$baris, $k->kegiatan);
            $object->getActiveSheet()->setCellValue('G'.$baris, $k->lokasi);
            

            $baris++;
        }

        $filename="Data_Operator".'.xlsx';

        $object->getActiveSheet()->setTitle("Data Operator");

        header('Content-Type: application/vnd.openxmlformats-spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename. '"');
        header('Cache-Control: max-age=0');

        $writer=PHPExcel_IOFactory::createwriter($object, 'Excel2007');
        $writer->save('php://output');

        exit;
    }

    public function hapus($no)
    {
        $data = array('no'=>$no);
        $this->operator_model->hapus_data($data, 'operator');
        redirect('administrator/operator');
    }
    public function update($no)
    {
        $where = array('no' => $no);
        $data['operator'] = $this->operator_model->edit_data($where,'operator')->result();
        $this->load->view('template_administrator/header');
        $this->load->view('template_administrator/sidebar');
        $this->load->view('administrator/operator_update',$data);
        $this->load->view('template_administrator/footer');
    }

    public function update_aksi()
    {
        $no = $this->input->post('no');
        $waktu = $this->input->post('waktu');
        $nama = $this->input->post('nama');
        $bidang = $this->input->post('bidang');
        $kegiatan = $this->input->post('kegiatan');
        $lokasi = $this->input->post('lokasi');
        
        $data = array(
        'nama' => $nama,
        'waktu' => $waktu,
        'bidang' => $bidang,
        'kegiatan' => $kegiatan,
        'lokasi' => $lokasi,
        'dokumentasi' => $dokumentasi,
                );
        $where = array(
            'no'  => $no
        );

        $this->operator_model->update_data($where,$data,'operator');
        $this->session->set_flashdata('pesan','<div class="alert alert-warning alert-danger dismissible fade show" role="alert">
                    Data Berhasil Diupdate!
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                    </div>');
        redirect('administrator/operator');
    }
    
     public function _rules_print(){
		$this->form_validation->set_rules('username','username','required',['required' => 'Nama Wajib Diisi']);
		$this->form_validation->set_rules('starting_date','starting_date','required',['required' => 'Tanggal Awal Wajib Diisi']);
		$this->form_validation->set_rules('end_date','end_date','required',['required' => 'Tanggal Akhir Wajib Diisi']);
    }
}
