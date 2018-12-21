<script type="text/javascript">
$(document).ready(function(){
     $('#textarea3[maxlength]').keyup(function(){
      var max = parseInt($(this).attr(’maxlength’));
      if($(this).val().length > max){
       $(this).val($(this).val().substr(0, $(this).attr('maxlength')));
      }

      $(this).parent().find('.charsRemaining').html('You have ' + (max - $(this).val().length) + ' characters remaining');
     });
    });

			</script>
<form action="/_admin/do_add_project/" method="post" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" class="reg_tab" style="width:900px; margin:0 auto; ">
<caption>Добавить работу</caption>
<tr><td colspan="2"><?php echo validation_errors(); ?><?php echo @$error;?></td></tr>
<tr>
	<td><label for="title">Название<sup>*</sup></label></td>
	<td ><input type="text" value="<?php echo set_value('title'); ?>" name="title" id="title" class="reg_inp2"/></td>
</tr>
<tr>
	<td><label for="file3">Музыкальный файл</label></td>
	<td><input type="file" name="userfile3" id="file3" class="reg_inp2"/></td>
</tr>

<tr><td colspan="2"  class="reg_buttons"><input type="submit" value="Добавить" class="reg_subm"  /> </td></tr>

</table>
</form>