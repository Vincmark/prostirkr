
<div class="siteWrapper noback">
	
	<div class="site black" style="color:#000; overflow:hidden;">
	
	<h1>Контактная информация</h1>
  <div id="map_canvas" class="left" style="width: 500px; height: 300px; float:left; overflow:hidden; margin-right:30px;"></div>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAxud7TFxrwCZ3nK0NaKs4pBRBJ8epcw6iqlzW401vRKiCI9yifxSmrA87sLzRlPAefezWag87aE0_oQ" type="text/javascript"></script>
<script type="text/javascript">
    if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map_canvas"));
        var point = new GLatLng(47.900166, 33.405447);

        map.setCenter(point, 17);
        map.addOverlay(new GMarker(point));
        map.openInfoWindowHtml(point, 'Радиокомпания "Простір КР"<br/><br/><p style="margin:0">Адрес: Украина, Кривой Рог,<br/> ул. Косиора 32, 3й этаж</p>');
        map.setUIToDefault();
    }
</script>
<div>
<dl>
<dt><strong>Телефоны:</strong></dt>
<dd style="margin-bottom:0;">‎(096) 340-03-03</dd>
<dd>(050) 362-76-99</dd>
<dt><strong>E-mail:</strong></dt>
<dd style="margin-bottom:0;">radio@trkprostir.dp.ua</dd>
<dd>trkprostir@gmail.com</dd>
<dt>Адрес:</dt>
<dd>Кривой Рог , ул. Косиора 32 , 3й этаж</dd>
</dl>
</div>
	<?php	
		
		$row=$text->row();
		echo $row->text;
	
	
	?>
	</div>
</div>
<!--/site-->

