<div style="width:960px; margin:0 auto;">
<h1 class="adm-title">Список страниц</h1>
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
<table id="example" class="display" cellpadding="0" cellspacing="0">

    <thead>
        <tr>
            <th style="width:30px;">№</th>
            <th>название</th> 
          
			<th>изменить</th>	
   
        </tr>           
    </thead>
    <tbody>
        <?  
                foreach ($project->result_array() as $key)
					{
						if($key['id']%2) $color = "style=\"background:#f0f0f0;\""; else $color="";
                        echo '<tr '.$color.'><td>'.$key['id'].'</td>
                            <td>'.$key['title'].'</td>
							
							';                   
                       
                   							
							
													
							echo '<td><a href="/_admin/edit_project/'.$key['id'].'/"><img src="/img/edit.png" alt="Изменить" title="Изменить" width="20px"/></a></td>
                                                      
                           
                            
                            </tr>';
                    }
        ?>
    
    
    
    </tbody>

<tfoot>
        <tr>
            <td>№</td>
            <td>название</td>


            <td>изменить</td>
         </tr>    
        
    </tfoot>
</table>
</div>

