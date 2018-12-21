<div style="width:960px; margin:10px auto;">

<form action="/_admin/do_add_admin/" method="post" >
<table class="reg_tab" style="width:310px; margin:20px auto;">
<caption style="margin-bottom:20px; padding-top:0;">Добавить администратора</caption>
<tr><td style="height:30px;">Логин:</td><td style="height:30px;"> <input type="text" value="" name="name" class="reg_inp" /></td></tr>
<tr><td style="height:30px;">Пароль:</td><td style="height:30px;">  <input type="password" value="" name="pass"  class="reg_inp"/></td></tr>
<tr><td style="height:30px;">Повтор пароля:</td><td style="height:30px;">  <input type="password" value="" name="pass2"  class="reg_inp"/></td></tr>
<tr><td colspan="2" align="center" class="reg_buttons"><input type="submit" value="Добавить" class="reg_subm"/></td></tr>

</table></form>

<?=@$err?>
<script type="text/javascript">
	$(document).ready(function(){
	  $("#example").dataTable({
	    "oLanguage": {
	      "sUrl": "/datatables/language/ru_RU.txt"
	    },
	    "iDefaultSortIndex": 0,
	    "sDefaultSortDirection": "asc"
	  });
	});
	</script>

<table id="example"  class="display"> 
	<thead><tr><th>Логин</th><th width="110">изменить</th><th width="100">удалить</th></tr></thead>
	<tbody>
<?
	$q=$this->db->get('admin');
		$id=1;
		foreach ($q->result_array() as $key)
			{
			$id++;
			if($id%2) $color =""; else $color="style=\"background:#f0f0f0;\"";
				echo '<tr '.$color.'><td align="center">'.$key['admin'].'</td>
					<td align="center"><a href="/_admin/edit_admin/'.$key['id_admin'].'">изменить пароль </a></td>
					<td align="center"><a href="/_admin/del_admin/'.$key['id_admin'].'/"'; ?> onclick="return confirm('Вы уверены что хотите удалить администратора ?.Продолжить?')" <?echo '>удалить</a></td></tr>';		
			
			}
?>
</tbody>
</table>
</div>