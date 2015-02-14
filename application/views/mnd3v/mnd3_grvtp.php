<table border='1'>
   <tr>
      <th>Marc</th>
      <th>Cod_grupa</th>
      <th>Grupa de varsta</th>
   </tr>
   <?php foreach ($grvlist as $grvitem): ?>
       <tr>
         <td><input type="radio" name="marc"
            onclick="setrbgrv(<?php echo $grvitem['codgrupv'] ?>)"
            title="Marcheaza pentru Adaugare sau Modificare"></td>
         <td><?php echo $grvitem['codgrupv'] ?></td>
         <?php echo "<td id='grvtp".trim($grvitem['codgrupv'])."' >".$grvitem['dengrupv']."</td>" ?>
      </tr>
   <?php endforeach ?>
</table>
