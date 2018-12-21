
<div class="siteWrapper noback">
	
	<div class="site black" style="color:#000;">
	<?php	
		$row=$text->row();
		echo $row->text;
	
	if($menu=="main"):
	?>
	<div class="box clear">
	
	 <form id="contact_form" method="post" action="/send_mail/" >
      
        <div>
            <label for="user_name"><span class="required">*</span> Имя:</label><br />
            <input type="text" id="user_name" name="user_name" />
        </div>

      
        <div>
            <label for="user_phone"><span class="required">*</span> Телефон:</label><br />
            <input type="text" id="user_phone" name="user_phone" />
        </div>

        <p class="help">
            <span class="required">*</span> - обязательное поле для ввода<br/>
        </p>

        <input type="hidden" name="action" value="send_question" />
		<button type="submit" class="button" name="submit"><span>Отправить запрос</span></button>
    </form>
	</div>
	<? endif;?>
	</div>
</div>
<!--/site-->

