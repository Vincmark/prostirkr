
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
<? $row=$proj->row();?>
<form action="/_admin/do_edit_music/<?=$row->id;?>/" method="post" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" class="reg_tab" style="width:900px; margin: 0 auto; margin-bottom:100px;  ">
<caption>Изменить проект</caption>
<tr><td colspan="2"><?php echo validation_errors(); ?><?php echo @$error;?></td></tr>
<tr>
	<td><label for="title">Название<sup>*</sup></label></td>
	<td ><input type="text" value="<?php if ($row->name!='') echo $row->name; else echo set_value('name'); ?>" name="name" id="title" class="reg_inp2"/></td>
</tr>

<tr><td colspan="2"  class="reg_buttons"><input type="submit" value="Сохранить" class="reg_subm"  /> </td></tr>

</table>
</form>
