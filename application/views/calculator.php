<div class="siteWrapper">
	
	<div class="site" style="overflow:hidden">
		<a id="calculat"></a>
		<h1>Расчет стоимости рекламы на радио в Кривом Роге</h1>
		<script>
	$(function() {
		$( "#slider-range-min" ).slider({
			range: "min",
			value: <?=$s1?>,
			min: 1,
			max: 240,
			slide: function( event, ui ) {
				$( "#amount"  ).val( ui.value);
			}
		});
		$( "#amount" ).val( $( "#slider-range-min" ).slider( "value" ) );
                $( "#slider-prime-min" ).slider({
			range: "min",
			value: <?=$s2?>,
			min: 1,
			max: 10,
			slide: function( event, ui ) {
				$( "#prime"  ).val( ui.value );
			}
		});
		$( "#prime" ).val( $( "#slider-prime-min" ).slider( "value" ));
                $( "#slider-notprime-min" ).slider({
			range: "min",
			value: <?=$s3?>,
			min: 1,
			max: 30,
			slide: function( event, ui ) {
				$( "#notprime"  ).val( ui.value );
			}
		});
		$( "#notprime" ).val( $( "#slider-notprime-min" ).slider( "value" ) );
				$( "#format" ).buttonset();
		$( "input:submit").button();

	});
</script>
<div class="newCalcWrap" style="float:left; overflow:hidden;">
          <form action="#calculat" method="post" name="calc">
    <p> 
            <label for="amount" class="foramount">Продолжительность ролика:</label> 
            <input type="text" name="amount" id="amount" style="width:38px" /> cек.
    </p> 

    <div id="slider-range-min"></div>
    
    <div class="timecalc">
        <h2>Количество выходов в день:</h2>
        <div class="prime">
            <p> 
                <label for="amount" class="time_title">В прайм-тайм:</label> 
                <input type="text" name="prime" id="prime"  /> 
            </p> 

            <div id="slider-prime-min"></div>
            <span class="small_pod">Прайм-тайм с 7:00 до 10:00 и с 16:00 до 20:00 каждый день, включая выходные </span>  
            
        </div>
        <div class="notprime">
            <p> 
                <label for="amount" class="time_title">Обычное время:</label> 
                <input type="text" id="notprime" name="notprime"  /> 
            </p> 

            <div id="slider-notprime-min"></div>
        </div>
    </div>
	<h2>Продолжительность рекламной кампании:</h2>
	<div class="mar_10"><input type="text" style="height:26px; width:100px; font-size:16px; padding:2px;" name="days" class="text ui-widget-content ui-corner-all" value="<?=$s4?>"> дней</div>
	<h2>Выберите одну или несколько радиостанций*:</h2>

          
<div id="format">
	<input type="checkbox" id="check1" name="radio[0]" value="100" <?=($radio[0]==100)?'checked':''?> /><label for="check1">Хит FM <span class="price_hit">100 грн/мин</span></label>
	<input type="checkbox" id="check2" name="radio[1]" value="100" <?=($radio[1]==100)?'checked':''?> /><label for="check2">Русское Радио <span class="price_hit">100 грн/мин</span></label>
	<input type="checkbox" id="check3" name="radio[2]" value="80"  <?=($radio[2]==80)?'checked':''?> /><label for="check3">Радио Relax <span class="price_hit">80 грн/мин</span></label>
</div>
<span class="small_pod" style="font-size:11px;""> * При размещении рекламы одновременно на трех радиостанциях, стоимость - 210 грн/мин</span>
<br>      <br>      
<input type="submit" name="btn" value="Посчитать рекламную компанию" style="font-size:16px; margin-left:95px;">
  </form>
</div>  
                
                
                
                
               
				
				<?php
					if($price!=null){ ?><div class="price_end" > Cтоимость рекламной компании: <br> <br><span style="font-size:28px;"><?=$price?> грн.</span></div><?php }
					else if(!empty($error)){?><div class="price_end" >
    <?php
						foreach($error as $ms){
							echo "* ".$ms." <br>";
						}
					?></div>
                                          <?php      
                                        }
					?>
				
			
	</div>
</div>
<!--/site-->

