<div style="width:960px; margin:0 auto;">
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
<form action="/_admin/do_edit_project/<?=$row->id;?>/" method="post" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" class="reg_tab" style="width:900px; margin-bottom:100px; ">
<caption>Изменить проект</caption>
<tr><td colspan="2"><?php echo validation_errors(); ?><?php echo @$error;?></td></tr>
<tr>
	<td><label for="title">Название<sup>*</sup></label></td>
	<td ><input type="text" value="<?php if ($row->title!='') echo $row->title; else echo set_value('title'); ?>" name="title" id="title" class="reg_inp2"/></td>
</tr>

<tr>
	<td colspan="2"><label for="content2">Технические характеристики<sup>*</sup>(ВНИМАНИЕ!! Информация о технической характерисктике должна вводиться маркированным списком)</label>
					<textarea  id="content2" name="content2"><?  if ($row->text!='') echo $row->text; else echo set_value('text'); ?></textarea><?php echo display_ckeditor($ckeditor); ?>	
	</td>
</tr>
<tr><td colspan="2"  class="reg_buttons"><input type="submit" value="Сохранить" class="reg_subm"  /> </td></tr>

</table>
</form>
</div>