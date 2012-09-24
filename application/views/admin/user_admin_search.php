<script type="text/javascript" src="/js/libs/cal.js"></script> 
<link href="/css/calendar.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<!--查找用户-->
<div>
<form action="/admin/user_admin_search_result" method="get" id="search_form"> 
<div class="span-0">
<br />
</div>
	  <fieldset class="span-24" id="search"> 
	    <legend></legend> 
	    <p>
	    <h4>搜索用户</h4> 
	    </p>

    	 <p>
	 <a class="opt" href="#" onclick="$('#search_form').submit();">搜索</a>
	 </p>

	    <div class="span-8" id="search_user_by_user_info">
	     <p>
              <label for="cond">精确指定搜索关键词</label><br />
	      <select id="cond" name="cond">
		 <option value="user_id">用户ID</option>
		 <option value="nick">用户名</option>
		 <option value="domain">用户个性域名</option>
  	 	 <option value="sys_domain">用户系统域名</option>
	      </select>
	      </p>
	      <p>
	      <label for="key_word">关键词</label>
	      <div class="">
	      <input type="text" class="text" name="key_word" id="key_word" value="" /> 
	      </div>
	      </p>
	 	 
 	      <hr class="hr"/>
	      <p>
	 	 <label for="city">城市</label><br />
		 <select id="loc_province" style="width:80px;" name="province"></select>
		 <input name="loc_province" type="hidden" />

		 <select id="loc_city" style="width:100px;" name="city"></select>
	 	 <input name="loc_city" type="hidden" />

		 <!--select id="loc_town" style="width:120px;" name="town"></select>
	 	 <input name="loc_town" type="hidden" /-->
	      </p>
	   </div> 

	   <div class="span-8" id="search_user_by_follow_count">
 
		 <p>
		 <span>粉丝数量</span>
		 <p>
	 	 	 <label for="follow_count_min">最少</label>
	 	 	 <input type="text" class="text" name="follow_count_min" id="follow_count_min" /> 
		 </p>
		 <p>
	 	 	 <label for="follow_count_max">最多</label>
	 	 	 <input type="text" class="text" name="follow_count_max" id="follow_count_max" />
	 	 </p>
	   </div>

	   <div class="span-7" id="search_user_by_reg_time">
 
		 <p>
		 <span>注册时间</span>
		 <p>
	 	 	 <label for="reg_time_start">开始</label>
	 	 	 <input type="text" class="text" name="reg_time_start" id="reg_time_start" /> 
		 </p>
		 <p>
	 	 	 <label for="reg_time_end">结束</label>
	 	 	 <input type="text" class="text" name="reg_time_end" id="reg_time_end" />
	 	 </p>
	   </div>
 
 
	 </fieldset> 

<div class="span-0 last">
<br />
</div>
        </form> 
</div>

<script>
jQuery(document).ready(function () {

	showLocation();

	$('#reg_time_start').simpleDatepicker({ startdate: 1912, enddate: 2012 });
	$('#reg_time_end').simpleDatepicker({ startdate: 1912, enddate: 2012 });
});
</script>
