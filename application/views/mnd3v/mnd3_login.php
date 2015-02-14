<html>
<head>
<link href="<?php echo $baseurl; ?>/bootstrap332dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?php echo $baseurl; ?>/js/jquery-1.11.2.js"></script>
<style type="text/css" >
h3 {margin-left:20mm}
p {margin-left:20mm}
</style>
<title>Adaug_Cont_Utilizator</title>
</head>
<body>
<H3>Identificare utilizator</H3>
<?php
//echo validation_errors();  //se scoate afara atunci cand se foloseste form_error() pt plasarea mesajului de eroare
$form_atr=array('name'=>'flogin', 'class'=>'form-horizontal');	//creeaza atribute forma
//param lui form_open() indica adresa URL folosita de Submit - adica functia de controler lansata, care va fi aceeasi cu functia care a lansat acest view, adica mnd3_login.php; al doilea param adauga atribute elementului form
echo form_open('mnd3c/login',$form_atr);
?>
  <!--input type="submit" name="btlogin" value="LOGIN" onclick="return reqpassword()" /-->
  <P>Utilizator: <input type="text" name="user" autofocus/>  <?php echo form_error('user'); ?> </P>
  <P>Parola: <input type="password" name="parola" /> <?php echo form_error('parola'); ?> </P>
  <!-- Bootstrap horizontal form merge pe Chrome dar nu merge pe Explorer 9
  <div class="form-group">
  <label class="control-label col-sm-1" for="iduser">Utilizator</label>
  <div class="col-sm-2">
    <input type="text" class="form-control" id="iduser" placeholder="User" name="user" value="arba_g" >
  </div> </div>
  <div class="form-group">
  <label class="control-label col-sm-1" for="idpass">Parola</label>
  <div class="col-sm-2">
    <input type="password" id="idpass" name="parola" value="AG1234" >
  </div> </div>-->
  <p><input type="submit" name="btlogin" value="LOGIN" class="btn btn-primary" style="margin-top:5mm; margin-left:12mm;" />  </p>
  <p><input type="submit" name="btretrpar" value="Retransmite_parola" class="btn btn-link" 
    title="Creeaza o noua parola si o transmite in mail-ul userului" style="margin-top:5mm" /> </p>

</form>
</body>
