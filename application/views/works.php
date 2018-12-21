<div class="siteWrapper">
	
	<div class="site microfonheight">
		<h1>Примеры рекламы на радио в Кривом Роге:</h1>
		<p>Ниже вы можежте прослушать рекламные ролики, которые мы разработали и запустили в эфир на радоистанции для <a href="/main/client">наших клиентов</a>.
				</p>
		<table class="rolik">
		<?php foreach ($works->result_array() as $key)
		{
		if($key['id']%2==0){
			
			$color="#4f89bb";			
			$color1="4f89bb";
			$class="class=\"line\"";
		}else{
			$color = "#3F7AAE";
			$color1 = "3F7AAE";
			$class="";
		}
		?>
		<tr <?=$class?>>
			<td><?=$key['name']?>   </td>
			<td><?=$key['time']?></td>
			<td><?=round($key['file_size']/1000)?> кб</td>
			<td width="220">
			
			<object type="application/x-shockwave-flash" data="/player_mp3_maxi.swf" width="200" height="20" name="asd">
				<param name="movie" value="player_mp3_maxi.swf" />
				<param name="bgcolor" value="<?=$color?>" />
				<param name="FlashVars" value="mp3=/music/<?=$key['file_name']?>&amp;volume=75&amp;bgcolor1=336590&amp;bgcolor2=2d587e&amp;buttoncolor=ffffff" />
			</object>
		
			</td>
		</tr>
		<?php } ?>
		
	</table>
	<div class="microfon"></div>
	</div>
</div>
<!--/site-->

