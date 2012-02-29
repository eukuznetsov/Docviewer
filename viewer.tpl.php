<?php
$user_id=$uid->uid; // Идентификатор пользователя //
$book_id=$document->docid; // Идентификатор книги //
?>

<script type="text/javascript">

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


var screen_height=screen.availHeight;
var screen_width=screen.availWidth;

function load_img(book_img, $url_img, $p_num){
var c_id = document.getElementById( "page_counter" );
  book_img.width = 300;
  book_img.src = $url_img;
  c_id.value = $p_num;
  book_img.width = 720;
}

$(
function() {
	
	$('#page_counter').change(function(){
		var addr = "<?php print $GLOBALS['base_root'] . request_uri(); ?>";
		if(isNumber($('#page_counter').val())&&(Math.floor($('#page_counter').val())>0)&&(Math.floor($('#page_counter').val())<=<?php print $document->pages; ?>)) {
			$('#page_counter').removeClass('error');
			window.location=addr+"#page_"+Math.floor($('#page_counter').val());
			$('#page_'+Math.floor($('#page_counter').val())).click().removeAttr('onclick').removeAttr('onmouseover');
		}else{
			$('#page_counter').addClass('error');
		}
		});
		
});
</script>


<div class="resizable-textarea">
  <div class="resizable-area" style="border-bottom-style:none; border-width:1px; border-color: rgb(212, 212, 212);">

<div class="item-list"><ul class="pager">
<li class="pager-first first" id="page_first"><a href="#page_first" title="В начало" class="active">«</a></li>
<?php
$page_count = $document->pages; 

$pager_dec =1+ floor($page_count/11);
if ($page_count > 200){
  $pager_dec = 10 * ceil($pager_dec/10);
}elseif ($page_count>70){
  $pager_dec = 5 * ceil($pager_dec/5);
}

for ($pager_no = $pager_dec; $pager_no <= $page_count; $pager_no += $pager_dec) {
  echo '<li class="pager-item"><a href="#page_';
  echo $pager_no;
  echo '" title="На страницу номер ';
  echo $pager_no;
  echo '" class="active">';
  echo $pager_no;
  echo '</a></li>';
}
?>
<li class="pager-next"><a href="#" title="На следующую страницу" class="active">›</a></li>
<li class="pager-last last"><a href="#page_last" title="В конец" class="active">»</a></li>
</ul></div>

    <div class="scrolling-area" style="overflow:auto; width:754px; height:500px; border-style: solid;  border-width: 1px; border-color: rgb(212, 212, 212); color: rgb(15, 15, 15);">
    <span>
<!-- p id="page_first" -->
<table border="1" bgcolor="silver" style="cursor: pointer;">
<?php
global $user;
if ($user->uid<1){
  echo '<tr><td><h3><font color="white">Для просмотра WEB-книги необходимо авторизоваться</font></h3></td></tr>';
}else{

  for ($page_no=1; $page_no<=$document->pages; $page_no++) {
  echo '<tr><td>';
  echo '  <p><img align="left" src="'. base_path() . drupal_get_path('module', 'docviewer') .'/list-page.png" width="150px" id="page_';
  echo $page_no;
  echo '"';
  echo '  onClick="load_img(this,';
  echo "'". url("books/". $uid->uid ."/". $document->docid ."/page-". $page_no) ."',";
  echo $page_no;
  echo ')" onMouseOver="load_img(this,';
  echo "'". url("books/". $uid->uid ."/". $document->docid ."/page-". $page_no) ."',";
  echo $page_no;
  echo ')"><div width="100%"><h4 align="center" class="page_no">'. $page_no .'</h4></div>';
  echo '</td></tr>';
  }
}

 ?>
</table>
<p id="page_last">
    </span>
    </div>
  </div>

<div class="grippie" style="margin-right: 6px; width: 754px;" onMouseOver=";">&nbsp;</div>

  <div class="bottom-area" style="overflow:auto; width:754px; height:50px; padding: 4px; border-top-style:none; border-width:1px; border-color: rgb(212, 212, 212);">

    <div style="width: 752px; height: 20px;">
    Страница:&nbsp;<b>|<a href="#page_first" title="Начало" class="active">&lt;&lt;</a>&nbsp;&nbsp;&lt;&nbsp;<input class="page_counter" value="1" type="text"
    style="width: 40px; height: 14px; margin-left: 3px; text-align: center;" id="page_counter">&nbsp;&gt;&nbsp;&nbsp;<a href="#page_last" title="Конец" class="active">&gt;&gt;</a>|</b>
    </div>

  </div>
</div>
