//MND3JS

//var globale
var sFilt="-"	//expresia de filtrare (se foloseste si in procedura de sortare)
var gtipu=""		//indicator tip lansare pagina users: A=Admin, U=user

window.onload = function() {
var cuser=document.getElementById("tipucda").innerHTML
if (cuser=='') gtipu="A"
else gtipu="U"
if (gtipu=='A') {    //admin
	filt()		//incarca tabela cu lista de useri, nefiltrata
   grvlistload()  //incarca tabela cu lista de grupe varsta in fereastra modala grv
	}
else {               //user
	document.formfilt.username_filt.value=cuser
	filt()  //filtreaza dupa userul curent
	document.getElementById("filttable").style.visibility = "hidden"
	document.getElementById("apendbt").style.visibility = "hidden"
	document.getElementById("bt_grvedit").style.visibility = "hidden"
	document.getElementById("divseltipu").style.visibility = "hidden"
	}
};

function SetFilterColumnWidth() {
/*if(typeof window.getComputedStyle === 'function') alert ("function")
else if (typeof window.getComputedStyle === 'undefined') alert ("undefined")
 else alert(typeof window.getComputedStyle);  */
//urmatorul test deoarece getComputedStyle nu este definit in Internet Explorer v<9
if(typeof window.getComputedStyle === 'function') {
  function setwidth (t_par, f_par, pmarg) {
    compstyle=window.getComputedStyle(document.getElementById(t_par), null)
    //document.getElementById(f_par).style.width=compstyle.width
    swidth=compstyle.width
    nwidth=swidth.substr(0,swidth.length-2)-pmarg
    document.getElementById(f_par).style.width=nwidth+"px"
  }
  setwidth("t_username", "f_username",4)
  setwidth("t_name", "f_name",4)
  setwidth("t_email", "f_email",4)
  setwidth("t_phone", "f_phone",4)
  setwidth("t_dentipu", "f_dentipu",0)
  setwidth("t_dengrupv", "f_dengrupv",0)
  }
}

var aSort=[0,0,0,0,0,0]
var aHeader_m=['','','','','','']

//click pe <th> in user list table
function fsort(nrcol) {
//sortare ajax dupa coloana nrcol
var aHeader=['User','Nume','Email','Telefon','TipUser','GrupaVarsta']
for(vnrc = 1; vnrc<=6; vnrc++) 
	if (vnrc==nrcol) 
		if (aSort[vnrc-1]==0 || aSort[vnrc-1]==2) {
			sortord='ASC';
			aSort[vnrc-1]=1
			aHeader_m[vnrc-1]=aHeader[vnrc-1]+'/'
			}
		else {
			sortord='DESC';
			aSort[vnrc-1]=2
			aHeader_m[vnrc-1]=aHeader[vnrc-1]+'\\'
			}
	else {
		aSort[vnrc-1]=0
		aHeader_m[vnrc-1]=aHeader[vnrc-1]
		}
$('#ultp').load(gbase+'index.php/mnd3c/userlist/'+nrcol+'/'+sortord+"/"+escape(sFilt),
	function() {
		aHeaderId=["t_username","t_name","t_email","t_phone","t_dentipu","t_dengrupv"]
		for(vnrc = 1; vnrc<=6; vnrc++) document.getElementById(aHeaderId[vnrc-1]).innerHTML=aHeader_m[vnrc-1]
		SetFilterColumnWidth()
		}
	);
}

//click buton Filtru
function filt() {
//filtrare user dupa randul de filtrare - cu Ajax .load
//alert ("text:"+document.formfilt.dentipu_sel.value)
sFilt=""
if (document.formfilt.username_filt.value!="") sFilt+=" AND username LIKE '%"+document.formfilt.username_filt.value+"%'"
if (document.formfilt.name_filt.value!="") sFilt+=" AND name LIKE '%"+document.formfilt.name_filt.value+"%'"
if (document.formfilt.email_filt.value!="") sFilt+=" AND email LIKE '%"+document.formfilt.email_filt.value+"%'"
if (document.formfilt.phone_filt.value!="") sFilt+=" AND phone LIKE '%"+document.formfilt.phone_filt.value+"%'"
if (document.formfilt.dentipu_sel.value>0) sFilt+=" AND user.codtipu="+document.formfilt.dentipu_sel.value
if (document.formfilt.dengrupv_sel.value>0) sFilt+=" AND user.codgrupv="+document.formfilt.dengrupv_sel.value
if (sFilt.length ==0) sFilt="-"
if (sFilt=="-") document.getElementById("filtrare").style.backgroundColor="green"
else document.getElementById("filtrare").style.backgroundColor="red"
//var filtrow=$("#filtrare").html();	$("#filtrare").replaceWith(filtrow)   //NU merge!!
$('#ultp').load(gbase+'index.php/mnd3c/userlist/0/""/'+escape(sFilt),
	function() {
    SetFilterColumnWidth()
    //dupa filtrare se reface sortarea initiala
		aSort[0]=0
		fsort(1)	}
	)
}

function stergfilt() {
	//anuleaza filtrarea dupa o operatie de editare user
	document.formfilt.username_filt.value=""
	document.formfilt.name_filt.value=""
	document.formfilt.email_filt.value=""
	document.formfilt.phone_filt.value=""
	document.formfilt.dentipu_sel.value=0
	document.formfilt.dengrupv_sel.value=0
	filt()
}


//click pe row table userlist - pt Modificare
function userrowclick(pusername,pname,pemail,pphone,pcodtipu,pcodgrupv,pdescription) {
//lansat de evenimentul click pe row table userlist 
//alert(rowid+"->"+document.getElementById(rowid+"_username").innerHTML)
document.formuser.username.value=pusername
document.formuser.password.value=""
document.formuser.name.value=pname
document.formuser.email.value=pemail
document.formuser.phone.value=pphone.substr(0,3)+'-'+pphone.substr(3,3)+'-'+pphone.substr(6,4)
document.formuser.description.value=pdescription
document.formuser.dentipu_sel.value=pcodtipu
document.formuser.dengrupv_sel.value=pcodgrupv
//
document.getElementById("modaltitle").innerHTML="Modificare"
iniusermodal('M')
}


//click pe buton Adaugare
function userappend() {
//afiseaza forma modala pt adaugare user
//alert("usn:"+document.formuser["username"].value)
//$("#modaltitle").text("Adaugare") //modifica text element (paragraf modaltitle)
document.getElementById("modaltitle").innerHTML="Adaugare"
iniusermodal('A')
document.getElementById("modaluser").style.visibility = "visible"
}

//var gmod //mod adaugare(A)/modificare(M) user, folosit la validare in formuser (uservalid())

function iniusermodal(pmod) {
//gmod=pmod
if (pmod=='A')  {
  document.formuser.username.disabled=false
  //document.formuser.password.disabled=false
  document.formuser.username.value='',
  document.formuser.name.value=''
  document.formuser.password.value=''
  document.formuser.email.value=''
  document.formuser.phone.value=''
  document.formuser.description.value=''
  }
if (pmod=='M') {
  document.formuser.username.disabled=true
  //document.formuser.password.disabled=true
  }
document.getElementById("errmes").innerHTML="no message"
document.getElementById("errmes").style.color="Gray"
document.getElementById("modaluser").style.visibility = "visible"  
}


//click buton "Inregistrare"  din formuser
function userrecord() {
//apeleaza procedura de inregistrare user: validare si ajax-post pt inreg in baza de date
//validare date introduse in forma
if (!uservalid()) return
//apeleaza procedura controler cu AJAX si defineste functia callback
$.post(gbase+'index.php/mnd3c/userrecord',
	{comanda:$("#modaltitle").text(),
	username:document.formuser.username.value,
	password:document.formuser.password.value,
	name:document.formuser.name.value,
	email:document.formuser.email.value,
	phone:document.formuser.phone.value.replace(/-/g,""), //elimina liniutele
	description:document.formuser.description.value,
	codtipu:document.formuser.dentipu_sel.value,
	codgrupv:document.formuser.dengrupv_sel.value 	},
	function(data,status){
		if (status=="success") {
			$("#errmes").text(data)
			if (data.substr(0,2)=="OK") {
        document.getElementById("errmes").style.color="blue"
        //$("#errmes").effect( "pulsate",{times:50}, 3000 );
        $("#errmes").effect( "shake", {times:4}, 1000 );
        if (gtipu=='A')stergfilt()	//mod Admin: sterge filtru si reincarca lista useri
        else fsort(1)  //mod User: apeleaza sortare pentru reincarcare date in lista/tabela
        }
      else document.getElementById("errmes").style.color="red"
			}
		else $("#errmes").text(status)
    }
  )
}

//click buton Iesire din formuser
function iesmodal() {
document.getElementById("modaluser").style.visibility = "hidden"
}

//click Delete in tabela de useri
function fdel(puser) {
//sterge user curent
if (gtipu=="U") return	//userul obisnuit nu poate face stergere
resp=confirm('Stergem '+puser)
if (!resp) return
$.post(gbase+'index.php/mnd3c/userdelete',
	{username:puser},
	function(data,status){
		if (status=="success") 
			if (data.substr(0,2)=="OK") mess="Stergere efectuata cu succes"
			else mess=data
		else mess="Eroare AJAX Post"
		alert(mess)
		stergfilt()		//sterge filtru si reincarca lista useri
    }
  )
}

//click buton ""Editeaza grupe varsta" din forma formuser
function grvedit() {
document.getElementById("errmesgrv").innerHTML=""   
document.getElementById("modalgrv").style.visibility = "visible"
}

var rb_grv=0

//click radiobuton marc din forma grvlistform
function setrbgrv(pcodgrv) {
   //alert(pcodgrv)
   rb_grv=pcodgrv
}

//click buton Modific din forma grvlistform
function grvmodif() {
document.grvlistform.codgrv.value=rb_grv
document.grvlistform.dengrv.value=document.getElementById("grvtp"+rb_grv).innerHTML
document.grvlistform.cdagrv.value="Modific"
document.grvlistform.codgrv.focus()
}

//click buton Adaug din forma grvlistform
function grvappend() {
document.grvlistform.cdagrv.value="Adaug"
document.grvlistform.codgrv.focus()
}
   
//click buton Inregistrez din forma grvlistform
function grvrecord() {
  //trimite controlerului datele de inregistrat
$.post(gbase+'index.php/mnd3c/grvrecord',
	{comanda:document.grvlistform.cdagrv.value,
	grvcode:document.grvlistform.codgrv.value,
	grvname:document.grvlistform.dengrv.value,
   grvcode_w:rb_grv },
	function(data,status){
		if (status=="success") {
         document.getElementById("errmesgrv").innerHTML=data
			if (data.substr(0,2)=="OK") {
            document.getElementById("errmesgrv").style.color="blue"
            //reincarca tabela cu lista grv, prin mnd3_grvtp.php
            $('#grvtp').load(gbase+'index.php/mnd3c/grvlist')
            }   
         else document.getElementById("errmesgrv").style.color="red"
      }
		else $("#errmesgrv").text(status)
    }
  )  
}

//click buton Sterg din forma grvlistform
function grvdelete() {
resp=confirm('Stergem Grupa de varsta cu cod '+rb_grv)
if (!resp) return
$.post(gbase+'index.php/mnd3c/grvdelete',
	{grvcode:rb_grv},
	function(data,status){
		if (status=="success") {
         document.getElementById("errmesgrv").innerHTML=data
			if (data.substr(0,2)=="OK") {
            document.getElementById("errmesgrv").style.color="blue"
            //reincarca tabela cu lista grv, prin mnd3_grvtp.php
            $('#grvtp').load(gbase+'index.php/mnd3c/grvlist')
            }   
         else document.getElementById("errmesgrv").style.color="red"
      }
		else $("#errmesgrv").text(status)
      }
   )
}

function grvlistload() {
//incarca tabela cu lista de grupe varsta in fereastra modala grv
$('#grvtp').load(gbase+'index.php/mnd3c/grvlist')
}

//click buton Iesire din grvlistform
function iesgrvmodal() {
document.getElementById("modalgrv").style.visibility = "hidden"
//incarca select grupv si reface option curent prin parametrul transmis controlerului
$('#grvselid').load(gbase+'index.php/mnd3c/grvselload/'+document.formuser.dengrupv_sel.value)
}

function uservalid() {
  function alert_err(pnumecamp,pinputname) {
    alert (pnumecamp)
    document.formuser[pinputname].focus()
    return false
    }
  //if (gmod=="A") {  //adaugare in formuser
  if (document.getElementById("modaltitle").innerHTML=="Adaugare") {  //adaugare in formuser
    if($.trim(document.formuser.username.value)=="")
      return alert_err("UserName este obligatoriu de completat","username")
    if(document.formuser.password.value.trim=="")
      return alert_err("Parola este obligatorie de completat","password")
  }
  vpassword=$.trim(document.formuser.password.value)
  if(vpassword!="") {
    regex=/^(?=.*[A-Z])((?=.*[0-9])|(?=.*[_-]))/
    if (regex.test(vpassword)==false)
      return alert_err("Parola trebuie sa contina o litera mare si o cifra sau liniuta","password")
  }
  vphone=$.trim(document.formuser.phone.value)
  if(vphone!="") {
    regex=/^[2-9][0-9]{2}-[0-9]{3}-[0-9]{4}$/
    if (regex.test(vphone)==false)
      return alert_err("Numar telefon este in format 999-999-9999, si incepe cu 3 sau mai mare","phone")
  }
  vemail=$.trim(document.formuser.email.value)
  //if (vemail.indexOf("@")==-1)
  if (vemail!="") {
     regex=/.+@.+/
    if (regex.test(vemail)==false)
      return alert_err("Email trebuie sa contina caracterul @, precedat de user si urmat de host","email")
  }
  return true
/* phone number validate:
999-999-9999
^[0-9]{3}-[0-9]{3}-[0-9]{4}$
*/
}

