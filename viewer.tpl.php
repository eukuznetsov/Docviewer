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
  $('#page_counter').removeClass('error');
}

$(
function() {
	
	var viewer = $('#viewer');
	var imgs = $('#viewer img');
	
	imgs.each(function(){$(this).click(); return false});
	
	(function(){
 
    var special = jQuery.event.special,
        uid1 = 'D' + (+new Date()),
        uid2 = 'D' + (+new Date() + 1);
 
    special.scrollstart = {
        setup: function() {
 
            var timer,
                handler =  function(evt) {
 
                    var _self = this,
                        _args = arguments;
 
                    if (timer) {
                        clearTimeout(timer);
                    } else {
                        evt.type = 'scrollstart';
                        jQuery.event.handle.apply(_self, _args);
                    }
 
                    timer = setTimeout( function(){
                        timer = null;
                    }, special.scrollstop.latency);
 
                };
 
            jQuery(this).bind('scroll', handler).data(uid1, handler);
 
        },
        teardown: function(){
            jQuery(this).unbind( 'scroll', jQuery(this).data(uid1) );
        }
    };
 
    special.scrollstop = {
        latency: 500,
        setup: function() {
 
            var timer,
                    handler = function(evt) {
 
                    var _self = this,
                        _args = arguments;
 
                    if (timer) {
                        clearTimeout(timer);
                    }
 
                    timer = setTimeout( function(){
 
                        timer = null;
                        evt.type = 'scrollstop';
                        jQuery.event.handle.apply(_self, _args);
 
                    }, special.scrollstop.latency);
 
                };
 
            jQuery(this).bind('scroll', handler).data(uid2, handler);
 
        },
        teardown: function() {
            jQuery(this).unbind( 'scroll', jQuery(this).data(uid2) );
        }
    };
})();
	
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
	
	var addr="<?php print $GLOBALS['base_root'] . request_uri();?>";
	
	viewer.bind('scrollstop', function(){
		$('#viewer img').each(function(){
				if($(this).offset().top>=viewer.offset().top){
					$(this).click();
					return false;
				}
			});
		});
		
	$('.pager_last').unbind('click').click(function(event){event.preventDefault();$('#page_<?php print $document->pages;?>').click(); window.location="<?php print $GLOBALS['base_root'] . request_uri() ."#page_". $document->pages?>";});
	
	$('.pager_next').unbind('click').click(function(event){
		event.preventDefault();
		var addr="<?php print $GLOBALS['base_root'] . request_uri();?>";
		imgs.each(function() {
			if($(this).attr('id')=='page_<?php print $document->pages; ?>') return false;
			if($(this).offset().top>=viewer.offset().top){
				var id=$(this).attr('id').replace(/[^0-9]*/g, '');
				window.location=addr+"#page_"+(parseInt(id)+1);
				return false;
			};
		});
	});
	
	$('.pager_prev').unbind('click').click(function(event){
		event.preventDefault();
		var addr="<?php print $GLOBALS['base_root'] . request_uri();?>";
		imgs.each(function() {
			if($(this).offset().top>=viewer.offset().top){
				if($(this).attr('id')=='page_1') return false;
				var id=$(this).attr('id').replace(/[^0-9]*/g, '');
				window.location=addr+"#page_"+(parseInt(id)-1);
				return false;
			};
		});
	});
		
});

</script>


<div class="resizable-textarea">
  <div class="resizable-area" style="border-bottom-style:none; border-width:1px; border-color: rgb(212, 212, 212);">

<div class="item-list"><ul class="pager">
<li class="pager-first first" id="page_first"><a href="#page_1" title="В начало" class="active">«</a></li>
<li><a class="pager_prev" href="#" title="На предыдущую страницу" class="active">‹</a></li>
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
<li><a class="pager_next" href="#" title="На следующую страницу" class="active">›</a></li>
<li><a class="pager_last" href="#page_last" title="В конец" class="active">»</a></li>
</ul></div>

    <div id="viewer" class="scrolling-area" style="overflow:auto; width:754px; height:500px; border-style: solid;  border-width: 1px; border-color: rgb(212, 212, 212); color: rgb(15, 15, 15);">
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
  echo '  <p><img style="float:left" src="'. base_path() . drupal_get_path('module', 'docviewer') .'/list-page.png" width="150px" id="page_';
  echo $page_no;
  echo '"';
  echo '  onClick="load_img(this,';
  echo "'". url("books/". $uid->uid ."/". $document->docid ."/page-". $page_no) ."',";
  echo $page_no;
  echo ')" onMouseOver="load_img(this,';
  echo "'". url("books/". $uid->uid ."/". $document->docid ."/page-". $page_no) ."',";
  echo $page_no;
  echo ')"><div width="100%"><h5 align="center" class="page_no">'. $page_no .'</h5></div>';
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
    Страница:&nbsp;<b>|<a href="#page_1" title="Начало" class="active">&lt;&lt;</a>&nbsp;&nbsp;<a href="#" class="pager_prev" title="Предыдущая страница">&nbsp;&nbsp;&lt;&nbsp;</a>&nbsp;<input title="Введите номер страницы" class="page_counter" value="1" type="text"
    style="width: 40px; height: 14px; margin-left: 3px; text-align: center;" id="page_counter">&nbsp;<a href="#" class="pager_next" title="Следующая страница" >&nbsp;&nbsp;&gt;&nbsp;&nbsp;</a>&nbsp;&nbsp;<a class="pager_last" href="#page_last" title="Конец" class="active">&gt;&gt;</a>|</b>
    </div>

  </div>
</div>
