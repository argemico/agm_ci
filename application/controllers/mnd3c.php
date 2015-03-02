<?php
class mnd3c extends CI_Controller {

public function __construct()
{
parent::__construct();
$this->load->model('mnd3m','Model');
$this->load->library('parser');
$this->load->helper('url');
$this->load->helper('form');
$this->load->library('form_validation');
$this->form_validation->set_error_delimiters('<span class="error_mess" style="color:red; font-weight:bold;">','</span>');
}


public function login()
//conectare utilizator sau retransmitere parola este folosita in 2 etape (2 apeluri)
  //1->se afiseaza forma de introducere date 
  //2->prelucrare date introduse
{
//seteaza regulile de validare
$this->form_validation->set_rules('user', 'User', 'required|callback_validuser');
$this->form_validation->set_rules('parola', '', 'callback_reqpass|min_length[2]');
$this->form_validation->set_message('reqpass','Completati parola!');
$this->form_validation->set_message('required', '%s este obligatoriu de completat!');
$this->form_validation->set_message('validuser','User inexistent!');
//
$data["baseurl"]=base_url();
$data["errmess"]="";
//testare respectare rules (la prima trecere, cand forma inca nu exista, se obtine False
if ($this->form_validation->run() === FALSE) {
  //1->afisare forma introducere date: se executa la prima trecere SAU daca se obtine eroare de validare in forma
   $this->load->view('mnd3v/mnd3_login.php',$data);
   }
else {
  //2->prelucrare date introduse (validare user): se executa la a n-a trecere (n>=2), cand nu avem eroare de validare in forma
  if ($this->input->post("btlogin")) {
      $rez=$this->Model->login();  
      //if($this->Model->prel_user())	//login executat cu succes
      switch($rez) {
      /*case 0:
      //login esuat, se reincarca forma login
         $data["errmess"]="Parola eronata!";
         $this->load->view('mnd3v/mnd3_login.php',$data);
         break;*/
      case 1:
         //S-a conectat un utilizator de tip Admin
         $this->users('');
         break;
      case 2:
         //S-a conectat un utilizator de tip User
         $this->users($this->input->post("user"));
         break;
      default:
         //eroare
         $data["errmess"]=$rez;
         $this->load->view('mnd3v/mnd3_login.php',$data);
      }
   }
   else {
      //Retransmite_parola
      $rez=$this->Model->retransmite();  
      $data["errmess"]=$rez;
      $this->load->view('mnd3v/mnd3_login.php',$data);
   }
}
}

//callback set_rules pt password
public function reqpass($fieldvalue) {
//parola este obligatorie numai daca s-a apasat pe butonul Login, nu si pt RetransmiteParola
if ($this->input->post("btlogin") and !$this->input->post("parola")) return FALSE;
else return TRUE;
}


//callback set_rules pt user
public function validuser($fieldvalue) {
//verifica existenta user din Post, dar inca nu s-a facut Post!
/*if ($this->Model->usercount()==0) return FALSE;
else return TRUE;*/
return TRUE;
}


public function users($ptipu) {
//afisare lista utilizatori in vederea editarii
   //daca butonul nu este de tip submit, sau daca submit s-a facut din functie JavaScript atunci nu avem setat $_POST['buton']
//if (! isset($_POST['submit']))   //{1-a trecere, afisare view
  //apelare in Model metoda generare date in tabela users
	//$baseurl=base_url();
	$baseurl_arr=array('base_url'=>base_url());
	$tipu_arr=array('tipucda'=>$ptipu);
	$userlist_arr=$this->getuserlist(0,"","-");
	$data=array_merge($baseurl_arr,$tipu_arr,$userlist_arr);
	$this->parser->parse('mnd3v/mnd3t_users.html', $data);
//else {//2-a trecere, prelucrare date}
}

public function getuserlist($nrcol,$sortorder,$filt) {
//defineste arrayul care va fi transmis fisierului view, apelat de users(),usersort(), 
$filt=urldecode($filt);
$users=$this->Model->users_sel($nrcol,$sortorder,$filt);	//returneaza tabela users
$users_arr=array('userlist'=>$users);	//array de array-uri cu liniile si coloanele din datatable
$tipu=$this->Model->tipu_sel();	//returneaza tabela tipuser
$tipu_arr=array('tipulist'=>$tipu);
$grupv=$this->Model->grupv_sel();	//returneaza tabela grupv
$grupv_arr=array('grupvlist'=>$grupv);
$userlist=array_merge($users_arr,$tipu_arr,$grupv_arr);	
return $userlist;
}

public function userlist($nrcol,$sortorder,$filt) {
$userlist_arr=$this->getuserlist($nrcol,$sortorder,$filt);
$this->parser->parse('mnd3v/mnd3t_ultp.html', $userlist_arr);
//ECHO 'AJAXXXXXXXX'.$par;
}

public function userrecord() {
$comanda=$_POST["comanda"];
array_shift($_POST);		//elimina primul elem din array ("comanda")
//foreach ($_POST as $postname=>$postval) {$mes=''; $mes=$mes."$postname=$postval; ";echo $mes;}
if ($comanda=='Adaugare') {
	$verifpk = $this->Model->usercount();
	if ($verifpk==0)	$resp=$this->Model->userinsert(); 
	else $resp="Userul introdus exista deja!";
	}
else {	//Modificare
	$resp=$this->Model->userupdate();
	}
echo $resp; 
}

public function grvrecord() {
$comanda=$_POST["comanda"];
if ($comanda=='Adaug') {
	$verifpk = $this->Model->grvcount();
	if ($verifpk==0)	$resp=$this->Model->grvinsert(); 
	else $resp="Cod GRV introdus exista deja!";
	}
else {	//Modificare
	$resp=$this->Model->grvupdate();
	}
echo $resp; 
}


public function grvlist() {
$grvlist_arr['grvlist']=$this->Model->grupv_sel();
//$grvlist_arr['grvlist']=$this->Model->getgrvlist();
//$this->parser->parse('mnd3v/mnd3t_grvtp.html', $grvlist_arr);
$this->load->view('mnd3v/mnd3_grvtp.php', $grvlist_arr);
}

public function grvselload($psel) {
//public function grvselload() {
$grvlist_arr['psel']=$psel;   
$grvlist_arr['grvlist']=$this->Model->grupv_sel();
$this->load->view('mnd3v/mnd3v_grvsel.php', $grvlist_arr);
}


public function userdelete() {
//stergere articol specificat prin _POST
$resp=$this->Model->userdelete();
echo $resp;	
}


public function grvdelete() {
//stergere articol specificat prin _POST
$resp=$this->Model->grvdelete();
echo $resp;	
}


}
