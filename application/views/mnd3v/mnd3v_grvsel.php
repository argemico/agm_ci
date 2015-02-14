<?php foreach ($grvlist as $grvitem): ?>
   <option value='<?php
   $vecho=trim($grvitem['codgrupv'])."'";
   if ($psel==$grvitem['codgrupv'])$vsel=" selected";
   else $vsel="";
   $vecho=$vecho.$vsel.">".$grvitem['dengrupv'];
   echo $vecho;
   ?>
   </option>
<?php endforeach ?>
