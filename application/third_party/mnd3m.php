<?php
class mnd3m extends CI_Model {

public function __construct()
{
//$this->load->database();	//nu recunoaste database in metode, acolo trebuie lansat din nou?;
//daca se completeaza $autoload['libraries']=array('database') in autoload.php, recunoaste si in metode
//$conne = mysqli_connect("localhost", "root", "", "mnd3db1");
//if (empty($conne)) die("Eroare de conectare la baza de date mnd3db : " . mysqli_connect_error());
//return TRUE;
}


public function login()
{
//$connstr=array("hostname"=>"localhost", "username"=>"root", "password"=>"", "database"=>"mnd3db1", "dbdriver"=>"mysql");
//$conn=$this->load->database($connstr,TRUE);
//$this->load->database($connstr);  //nu merg sql-urile!!, baza de date trebuie specificata in database.php
//$this->load->database();
$usern="arba_g";
$usern=$this->input->post("user");
/*$seldb="use mnd3db1";
$query1=$this->db->query($seldb);*/
//
$sel="SELECT password, codtipu, email FROM user WHERE username='".$usern."'";
//$query1=$conn->query($sel);
$query1=$this->db->query($sel);
/*if(!$query1) die($this->db->_error_message());
else die("ok");*/
if ($query1->num_rows()==0) {
  //mesaj simplu deocamdata !!!!!!!!!!!!!!!!
  echo "User eronat";
  return '0';
  }
$row=$query1->row_array();
//testare tip comanda
//daca butonul nu este de tip submit, sau daca submit s-a facut din functie JavaScript atunci nu avem setat $_POST['buton']
//foreach ($_POST as $postname=>$postval) echo "<p>$postname=$postval</p>";
if ( isset($_POST['btretrpar'])) {
  //comanda "Retransmite_parola"
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
		//$conn->query($sql);	
    $this->db->query($sql);
    echo "Noua parola a fost transmisa la adresa de email";
		}
  else echo "Eroare transmitere email";
    return '0';
  }
else {
  //comanda "Login"
  $parola=md5($this->input->post("parola"));
  if ($row["password"]!=$parola) {
    //mesaj simplu deocamdata !!!!!!!!!
    echo $parola."Parola eronata !! ".$row["password"];
    return '0';
    }
  return $row["codtipu"];
  }
}


public function users_sel($nrcol,$sortorder,$filt)
//selectare date din db tabel user
{
$sel="SELECT username, name, email, phone, tipuser.dentipu, grupv.dengrupv, description ".
" FROM user, tipuser, grupv ";
$where=" WHERE user.codtipu=tipuser.codtipu and user.codgrupv=grupv.codgrupv ";
if ($nrcol>0) $ordby=" ORDER BY ".$nrcol." ".$sortorder;
else $ordby="";
if ($filt!="-") $where=$where.$filt;
$sel=$sel.$where.$ordby;
//$query1=$conn->query($sel);
$query=$this->db->query($sel);
$rez=$query->result_array();
return $rez;
}


public function tipu_sel()
//selectare date din db tabel tipuser
{
$sel="SELECT codtipu, dentipu FROM tipuser ORDER BY 1";
$query=$this->db->query($sel);
$rez=$query->result_array();
return $rez;
}


public function grupv_sel()
//selectare date din db tabel tipuser
{
$sel="SELECT codgrupv, dengrupv FROM grupv ORDER BY 1";
$query=$this->db->query($sel);
$rez=$query->result_array();
return $rez;
}


public function userrecord()
//
{
$rez=$_POST["name"];
return $rez; 
}


//|||||||||||||||||||||||||||||||||||||||
public function prel_user0()
{
$connstr=array("hostname"=>"localhost", "username"=>"root", "password"=>"", "database"=>"mnd3db1", "dbdriver"=>"mysql");
$conn=$this->load->database($connstr,TRUE);
//$this->load->database($connstr);
if (empty($conn)) die("Eroare de conectare la baza de date g71db ");
//else die("corect");
$usern="arba_g";
$usern=$this->input->post("user");
$sel="SELECT password, codtipu, email FROM user WHERE username='".$usern."'";
$query1=$conn->query($sel);
if (empty($query1)) die("Eroare query");
if ($query1->num_rows()==0) {
  echo "User eronat";
  return '0';
  }
$row=$query1->row_array();
//testare tip comanda
//daca butonul nu este de tip submit, sau daca submit s-a facut din functie JavaScript atunci nu avem setat $_POST['buton']
//foreach ($_POST as $postname=>$postval) echo "<p>$postname=$postval</p>";
if ( isset($_POST['bnretrpar'])) {
  //comanda "Retransmite_parola"
  $email=$row["email"];
  //genereaza un numar aleator
  $nraleat=rand(10000,99999);
  $newpass="P".$nraleat;
  //die("email:".$row["email"]);
  $vr=mail($row["email"],"Parola",$newpass);
  if ($vr) die("mail ok");
  else die("mail err");
//creeaza si trimite parola
  //inreg parola
  }
else {
  //comanda "Login"
  $parola=md5($this->input->post("parola"));
  if ($row["password"]!=$parola) {
    echo "Parola eronata !! ";
    return '0';
    }
  return $row["codtipu"];
  }
}




//||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
public function validuser()
{
/*verificare parola utilizator (+inreg user in cookie)*/
$vuser=$_POST['user'];
$vparola=$_POST['parola'];
/*validare utilizator din tabela de useri*/
//conectare la baza de date
$conn = mysqli_connect("localhost", "root", "", "g71db");
if (empty($conn)) die("Eroare de conectare la baza de date g71db : " . mysqli_connect_error());
//cautare user
$selsql="SELECT user, parola FROM g71utiliz WHERE user='$vuser'";
$result = $conn->query($selsql);
//$result este empty numai daca SELECT are eroare de sintaxa
$row = $result->fetch_row();
//$row este empty daca select nu returneazanici 1 linie
if (empty($row)) die("Utilizator inexistent $vuser " );
if ($row[1]!=$vparola) {echo "Parola eronata<br />"; return;}
//die("OK");
$userv=0;
if($userv==0)
    {
    /*inreg utilizator in cookie, */
    $vtex=time();
    $vtex=$vtex+60*60*24*30;
    $vl=setcookie("user",$vuser,$vtex);
    if(!$vl) die("Eroare inregistrare user in cookie!");
    echo "Bine ai venit ".$vuser." !";
    //include 'g71meniu.html';
	return TRUE;
    }
else
    {
    $line='<h4 style="color:red;">'.$meser.'</h4>';
    echo $line;
    $line='<br /><A href="g71prez.html">Prezentare utilizator</A>';
    echo $line;
return FALSE;
    }
return;
}



public function get_news($slug = FALSE)
{
  $conn = mysqli_connect("localhost", "root", "", "g71db");
  if (empty($conn)) die("Eroare de conectare la baza de date g71db : " . mysqli_connect_error());
  if ($slug === FALSE)
  {
    //$query = $this->db->get('news');
    //return $query->result_array();
  }
  //$query = $this->db->get_where('news', array('slug' => $slug));
  $selsql="SELECT user, parola FROM g71utiliz WHERE user='$slug'";
  $result = $conn->query($selsql);
  $row = $result->fetch_row();
  //$row2=array($row['user'], $row['parola']);	-->da eroare, fetch_row nu stie sa creeze index NumeCamp
  return $row;
  //return $query->row_array();
}

public function set_news()
{ 
//echo "set_news() function";
$this->load->helper('url');
$slug = url_title($this->input->post('title'), 'dash', TRUE);
$data = array( 'title' => $this->input->post('title'), 'slug' => $slug, 'text' => $this->input->post('text') ); 
$vecho= "Am adaugat: titlu=".$data['title'].", slug=".$data['slug'].", text=".$data['text']  ;
//$vecho="set_news() function";
echo $vecho;
}


}