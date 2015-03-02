<?php
class mnd3m extends CI_Model {
/* Setare in config\database.php, pentru a nu se opri la eroare si astfel tratabil prin program
$db['default']['db_debug'] = FALSE;*/

public function __construct() {}


private function database_error() {
	return "EROARE BAZA DE DATE: ".$this->db->_error_number()." -> ".$this->db->_error_message();
}


public function login(){
/*$connstr=array("hostname"=>"localhost", "username"=>"root", "password"=>"", "database"=>"mnd3db1", "dbdriver"=>"mysql");
$conn=$this->load->database($connstr,TRUE);*/
//$this->load->database($connstr);  //nu merg sql-urile!!, baza de date trebuie specificata in database.php
//$this->load->database();		//autoload in config
$usern=$this->input->post("user");
$sel="SELECT password, codtipu FROM user WHERE username='".$usern."'";
//$query1=$conn->query($sel);
$query1=$this->db->query($sel);
if(!$query1) { 
	$rez=$this->database_error();
	return $rez;
   }
if ($query1->num_rows()==0) {
   $rez="User eronat, reintroduceti user";
   return $rez;
   //return '0';
   }
//verificare parola introdusa
$parola=md5($this->input->post("parola"));
$row=$query1->row_array();
if ($row["password"]!=$parola) {  
    $rez="Parola eronata, reintroduceti parola";
   return $rez;
   //return '0';
    }
else
   //returneaza cod tip utilizator (1=Admin, 2=User)
   return $row["codtipu"];
}


public function retransmite() {
//comanda "Retransmite_parola"
$usern=$this->input->post("user");
$sel="SELECT email FROM user WHERE username='".$usern."'";
$query1=$this->db->query($sel);
if(!$query1) { 
	$rez=$this->database_error();
	return $rez;
   }
if ($query1->num_rows()==0) {
   $rez="User eronat, reintroduceti user";
   return $rez;
   }
$row=$query1->row_array();
$email=$row["email"];
//genereaza un numar aleator
$nraleat=rand(10000,99999);
$newpass="P".$nraleat;
//creeaza si trimite parola
//$vr=mail($row["email"],"Parola",$newpass);
$vr=TRUE;
if ($vr) {
	//inreg parola
   $md5newpass=md5($newpass);
	$sql="UPDATE user SET password='".$md5newpass."' WHERE username='".$usern."'";
   $query1=$this->db->query($sql);
   if(!$query1)
      $rez=$this->database_error();
   else
      $rez="Noua parola a fost transmisa la adresa de email ".$email;
   }
else
   $rez="EROARE transmitere email";
return $rez;
}


public function users_sel($nrcol,$sortorder,$filt)
//selectare date din db tabel user - sortat si filtrat
{
//ultimele 2 campuri au fost adaugate pentru fereastra modala de modificare
$sel="SELECT username, name, email, phone, tipuser.dentipu, grupv.dengrupv, description, user.codtipu, user.codgrupv".
" FROM user, tipuser, grupv ";
$where=" WHERE user.codtipu=tipuser.codtipu and user.codgrupv=grupv.codgrupv ";
//sortare
if ($nrcol>0) $ordby=" ORDER BY ".$nrcol." ".$sortorder;
else $ordby="";
//filtrare
if ($filt!="-") $where=$where.$filt;
$sel=$sel.$where.$ordby;
$query=$this->db->query($sel);
if(!$query) return $this->database_error()."->".$sel;
$rez=$query->result_array();
return $rez;
}


public function tipu_sel() {
//selectare date din db tabel tipuser
$sel="SELECT codtipu, dentipu FROM tipuser ORDER BY 1";
$query=$this->db->query($sel);
$rez=$query->result_array();
return $rez;
}


public function grupv_sel() {
//selectare date din db tabel grupv
$sel="SELECT codgrupv, dengrupv FROM grupv ORDER BY 1";
$query=$this->db->query($sel);
$rez=$query->result_array();
return $rez;
}


public function usercount() {
/*$sel="SELECT * FROM user WHERE username='".$this->input->post("username")."'";
$query1=$this->db->query($sel);*/
$cwhere=array('username' => $this->input->post("username"));
$query1=$this->db->get_where('user',$cwhere);
return $query1->num_rows();
}


public function userinsert() {
foreach ($_POST as $postname=>$postval) {
	if ($postname!='password')	$this->db->set($postname, $postval); 
	else $this->db->set($postname, md5($postval));
	}
$qr=$this->db->insert('user'); 
if(!$qr) return $this->database_error();
else return "OK";
}


public function userupdate() {
foreach ($_POST as $postname=>$postval)
	if ($postname!='password') {
      if ($postname!='username') $this->db->set($postname, $postval);
         //username nu se poate modifica
      else $this->db->where('username',$postval);  //set clauza where
      }
   else 
      if ($postval!="") //nu se inreg parola daca nu s-a completat, la modificare user
         $this->db->set($postname, md5($postval));
$qr=$this->db->update('user'); 
if(!$qr) return $this->database_error();
else return "OK";
}

public function userdelete() {
$this->db->where('username',$this->input->post("username"));
$qr=$this->db->delete('user'); 
if(!$qr) return $this->database_error();
else return "OK";
}


public function grvcount() {
$cwhere=array('codgrupv' => $this->input->post("grvcode"));
$query1=$this->db->get_where('grupv',$cwhere);
return $query1->num_rows();
}


public function grvinsert() {
$this->db->set("codgrupv", $this->input->post("grvcode"));
$this->db->set("dengrupv", $this->input->post("grvname"));
$qr=$this->db->insert('grupv'); 
if(!$qr) return $this->database_error();
else return "OK";
}


public function grvupdate() {
$this->db->set("codgrupv", $this->input->post("grvcode"));
$this->db->set("dengrupv", $this->input->post("grvname"));
$this->db->where("codgrupv",$this->input->post("grvcode_w"));
$qr=$this->db->update('grupv'); 
if(!$qr) return $this->database_error();
else return "OK";  
}


public function grvdelete() {
$this->db->where('codgrupv',$this->input->post("grvcode"));
$qr=$this->db->delete('grupv'); 
if(!$qr) return $this->database_error();
else return "OK";
}


//vvvvvvvv---Urmatoarele functii nu se folosesc---vvvvvvvvvv

public function getgrvlist() {
$query=$this->db->get('grupv');
$rez=$query->result_array();
return $rez;
}

public function userinsert1() {
$vpassword='P00001';
$sql="INSERT INTO user (username,name,email,phone,description,codtipu,codgrupv,password) VALUES (";
$sql=$sql."'".$_POST["username"]."','".$_POST["name"]."','".$_POST["email"]."','".$_POST["phone"]."','".$_POST["description"]."',";
$sql=$sql.$_POST["codtipu"].",".$_POST["codgrupv"].",'".$vpassword."')";
$qr=$this->db->query($sql);
if ($qr) return "OK";
else return "ERR";
//return $qr;
}

public function userinsert2() {
$vpassword='P00001';
$vinsert=array("username"=>$_POST["username"], "name"=>$_POST["name"], "email"=>$_POST["email"],
	"phone"=>$_POST["phone"], "description"=>$_POST["description"], "password"=>$vpassword,
	"codtipu"=>$_POST["codtipu"], "codgrupv"=>$_POST["codgrupv"]);
$sql = $this->db->insert_string('user', $vinsert);
$qr=$this->db->query($sql);
if(!$qr) return $this->database_error();
else return "OK";
}


public function userinsert3() {
$vpassword='P00001';
$this->db->set("username", $_POST["username"]); 
$this->db->set("name", $_POST["name"]); 
$this->db->set("email", $_POST["email"]); 
$this->db->set("phone", $_POST["phone"]); 
$this->db->set("description", $_POST["description"]); 
$this->db->set("password", $vpassword); 
$this->db->set("codtipu", $_POST["codtipu"], FALSE); 
$this->db->set("codgrupv", $_POST["codgrupv"], FALSE); 
$qr=$this->db->insert('user'); 
if(!$qr) return $this->database_error();
else return "OK";
}




}