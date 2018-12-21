<div style="width:960px; margin:0 auto;">
<h1 class="adm-title">Наши работы:</h1>
<script type="text/javascript">
	$(document).ready(function(){
	  $("#example").dataTable({
	    "oLanguage": {
	      "sUrl": "/datatables/language/ru_RU.txt"
	    },
	    "iDefaultSortIndex": 0,
	    "sDefaultSortDirection": "desc"
	  });
	});
	</script>
    <p class="error"><?=@$err?></p>
	<br>
	<p><a href="/_admin/insert_music">Добавить музыку</a></p>
<table id="example" class="display" cellpadding="0" cellspacing="0">

    <thead>
        <tr>
            <th style="width:30px;">№</th>
            <th>название ролика</th>
			<th>длительность</th>			
			<th>размер</th>			
			<th>play</th>		
          	<th>изменить</th>	
			<th>Удалить</th>
   
        </tr>           
    </thead>
    <tbody>
        <?  
                foreach ($project->result_array() as $key)
					{
						if($key['id']%2) $color = "style=\"background:#f0f0f0;\""; else $color="";
                        echo '<tr '.$color.'><td>'.$key['id'].'</td>
                            <td>'.$key['name'].'</td>
							<td>'.$key['time'].'</td>
							<td>'.$key['file_size'].'</td>
							';                   
                             ?>
                   					<td>
			
			<object type="application/x-shockwave-flash" data="/player_mp3_maxi.swf" width="26" height="20">
				<param name="movie" value="player_mp3_maxi.swf" />
				<param name="FlashVars" value="mp3=/music/<?=$key['file_name']?>&amp;width=26&amp;volume=75&amp;showslider=0" />
			</object>
			
			</td>			
							<?php
													
							echo '<td><a href="/_admin/edit_music/'.$key['id'].'/"><img src="/img/edit.png" alt="Изменить" title="Изменить" width="20px"/></a></td>
                            <td><a href="/_admin/do_del_exp/'.$key['id'].'/" ' ?> onclick="return confirm('Вы уверены что хотите удалить интервью?.Продолжить?')" ><? echo 'удалить</a></td>                         
                           
                            
                            </tr>';
                    }
        ?>
    
    
    
    </tbody>


</table>
</div>

