<script type="text/javascript" src="/js/libs/cal.js"></script> 
<link href="/css/calendar.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<!--查找用户-->
<div id="search">
<form action="/admin/solution_admin_search_result" method="get" id="search_from"> 
<div class="span-0">
<br />
</div>
	 <fieldset class="span-24" id="search"> 
	 	 <legend></legend> 
	 	 <p>
	 	 	 <h4>搜索方案</h4> 
		 </p>

    	 	 <p class="span-24">
	 	 <a class="opt" href="#" onclick="$('#search_from').submit();">搜索</a>
	 	 </p>
	 
	 	 <!--按卖方信息-->
		 <div class="span-8" id="search_solution_by_saler_info">
		 <p>
	 	 <span>根据卖方信息搜索</span>
	 	 </p>
	 	 <p>
	 	 <label for="cond">搜索范围</label><br />
			 <select id="" name="saler_info_cond">
				 <option value="user_id">卖方ID</option>
				 <option value="nick">卖方用户名</option>
				 <option value="domain">卖方个性域名</option>
				 <option value="sys_domain">卖方系统域名</option>	 
			 </select>
		 <p>
		 	 <label for="by_saler_key_word">关键词</label>
			 <div class="">
		 	 <input type="text" class="text" name="by_saler_key_word" id="" value="" /> 
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

		 <hr class="hr"/>

	 	 <p>
		 <span>卖方注册时间</span>
		 <p>
	 	 	 <label for="saler_reg_time_start">起始时间</label>
			 <input type="text" class="text" name="saler_reg_time_start" id="saler_reg_time_start" /> 
		 </p>
		 <p>
	 	 	 <label for="saler_reg_time_end">结束时间</label>
	 	 	 <input type="text" class="text" name="saler_reg_time_end" id="saler_reg_time_end" /> 
	 	 </p>
	 	 </p>
		 </p>

		 </div>

 		 <!--买方信息-->
		 <div class="span-8" id="search_solution_by_buyer_info">
	 	 <p>
	 	 	 <span>根据买方信息搜索</span>
	 	 </p>
	 	 <p>
	 	 <label for="buyer_info_cond">搜索范围</label><br />
			 <select name="buyer_info_cond">
				 <option value="user_id">买方ID</option>
				 <option value="nick">买方用户名</option>
				 <option value="domain">买方个性域名</option>
				 <option value="sys_domain">买方系统域名</option>	 
			 </select>
		 <p>
		 	 <label for="by_buyer_key_word">关键词</label>
			 <div class="">
		 	 <input type="text" class="text" name="by_buyer_key_word" id="by_buyer_key_word" value="" /> 
		 	 </div>
		 </p>

	 	 <hr class="hr"/>

	 	 <p>
		 <span>买方注册时间</span>
		 <p>
	 	 	 <label for="buyer_reg_time_start">起始时间</label>
	 	 	 <input type="text" class="text" name="buyer_reg_time_start" id="buyer_reg_time_start" /> 
		 </p>
		 <p>
	 	 	 <label for="buyer_reg_time_end">结束时间</label>
	 	 	 <input type="text" class="text" name="buyer_reg_time_end" id="buyer_reg_time_end" />
	 	 </p>
	 	 </p>
		 </p>

		 </div>

 		 <!--方案信息-->
		 <div class="span-7" id="search_solution_by_solution_info">
		 <p>
	 	 <span>根据方案信息搜索</span>
	 	 </p>
	 	 <p>
	 	 <label for="solution_info_cond">搜索范围</label><br />
			 <select name="solution_info_cond">
				 <option value="id">方案ID</option>
				 <option value="title">方案标题</option>
				 <option value="describe">方案描述</option>	 
			 </select>
		 </p>
	 	 <p>
		 <label for="by_solution_key_word">关键词</label>
		 <div class="">
		 <input type="text" class="text" name="by_solution_key_word" /> 
		 </div>
		 </p>

		 <hr class="hr"/>

		 <p>
		 <span>方案发布时间</span>
		 <p>
	 	 	 <label for="solution_post_time_start">起始时间</label>
	 	 	 <input type="text" class="text" name="solution_post_time_start" id="solution_post_time_start"/> 
		 </p>
		 <p>
	 	 	 <label for="solution_post_time_end">结束时间</label>
	 	 	 <input type="text" class="text" name="solution_post_time_end" id="solution_post_time_end" /> 
	 	 </p>
		 </p>

		 <hr class="hr"/>

		 <p>
		 <span>折扣区间</span>
		 <p>
	 	 	 <label for="solution_discount_min">最大</label>
	 	 	 <input type="text" class="text" name="solution_discount_min" /> 
		 </p>
		 <p>
	 	 	 <label for="solution_discount_max">最小</label>
	 	 	 <input type="text" class="text" name="solution_discount_max" /> 
	 	 </p>
		 </p>

		 <hr class="hr"/>

		 <p>
		 <span>购买次数</span>
		 <p>
	 	 	 <label for="solution_has_bought_min">最少</label>
	 	 	 <input type="text" class="text" name="solution_has_bought_min" /> 
		 </p>
		 <p>
	 	 	 <label for="solution_has_bought_max">最多</label>
	 	 	 <input type="text" class="text" name="solution_has_bought_max" /> 
	 	 </p>
		 </p>

		 <hr class="hr"/>

		 <p>
		 <span>支付次数</span>
		 <p>
	 	 	 <label for="solution_has_paid_min">最少</label>
	 	 	 <input type="text" class="text" name="solution_has_paid_min" /> 
		 </p>
		 <p>
	 	 	 <label for="solution_has_paid_max">最多</label>
	 	 	 <input type="text" class="text" name="solution_has_paid_max" /> 
	 	 </p>
	 	 </p>
	 	 
	 	 </div>
	       
	 </fieldset> 

        </form> 
</div>

<script>
jQuery(document).ready(function () {

	showLocation();

	$('#solution_post_time_start').simpleDatepicker({ startdate: 1912, enddate: 2012 });
	$('#solution_post_time_end').simpleDatepicker({ startdate: 1912, enddate: 2012 });
	$('#buyer_reg_time_start').simpleDatepicker({ startdate: 1912, enddate: 2012 });
	$('#buyer_reg_time_end').simpleDatepicker({ startdate: 1912, enddate: 2012 });
	$('#saler_reg_time_start').simpleDatepicker({ startdate: 1912, enddate: 2012 });
	$('#saler_reg_time_end').simpleDatepicker({ startdate: 1912, enddate: 2012 });
});
</script>
