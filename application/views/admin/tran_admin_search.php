<script type="text/javascript" src="/js/libs/cal.js"></script> 
<link href="/css/calendar.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<!--查找交易-->
<div>
<form action="/admin/do_tran_admin_search" method="get" id="search_form"> 
<div class="span-0">
<br />
</div>
	<fieldset class="span-24" id="search"> 
	    <legend></legend> 
	    <p>
	    <h4>搜索交易</h4> 
	    </p>

   	 <p>
	 <a class="opt" href="#" onclick="$('#search_form').submit();">搜索</a>
	 </p>
	 
	 <div class="span-9" id="tran_search_form_container">
 	 <div class="span-8" id="search_tran_by_code">
 
		 <p>
		 <span>交易支付码</span>
		 </p>
	 	 <p>
	 	 
	 	 	 <label for="tran_code">支付码</label><br />
	 	 	 <input type="text" class="text" name="tran_code" id="tran_code" /> 
		 </p>

		 <hr class="hr"/>

		 <p>
		 <span>交易状态</span>
		 </p>
	 	 <p>
			 <label for="tran_status">请选择交易状态</label><br />
			 <select name="tran_status">
	 	 	 	 <option value=""></option>
				 <option value="bought">已购买</option>
				 <option value="paid">已支付</option>
	 	 	 	 <option value="expire">已过期</option>
	 	 	 </select>
		 </p>
	 </div>
 	 <div class="span-8" id="search_tran_by_holder_info">
 
		 <p>
		 <span>卖方信息</span>
		 </p>
	 	 <p>
			 <label for="holder_info">卖方信息</label><br />
	 	 	 <select name="holder_info_key">
				 <option value="user_id">ID</option>
				 <option value="nick">用户名</option>
				 <option value="sys_domain">系统域名</option>
				 <option value="domain">个性域名</option>
				 <option value="loc_city">城市</option>
	 	 	 </select>
	 	 	 <input type="text" class="text" name="holder_info_value" id="holder_info_value" /> 
		 </p>
	     	 <p>
	 		 <label for="city">城市</label><br />
		 	 <select id="loc_province_saler" style="width:80px;" name="province_saler"></select>
		 	 <input name="loc_province_saler" type="hidden" />

		 	 <select id="loc_city_saler" style="width:100px;" name="city_saler"></select>
	 	 	 <input name="loc_city_saler" type="hidden" />

			 <!--select id="loc_town" style="width:120px;" name="town"></select>
			 <input name="loc_town" type="hidden" /-->
	         </p>

		 <hr class="hr"/>

		 <p>
		 <span>买方信息</span>
		 </p>
	 	 <p>
			 <label for="buyer_info">买方信息</label><br />
	 	 	 <select name="buyer_info_key">
				 <option value="user_id">ID</option>
				 <option value="nick">用户名</option>
				 <option value="sys_domain">系统域名</option>
				 <option value="domain">个性域名</option>
				 <option value="loc_city">城市</option>
	 	 	 </select>
	 	 	 <input type="text" class="text" name="buyer_info_value" id="buyer_info_value" /> 
		 </p>
	     	 <p>
	 		 <label for="city">城市</label><br />
		 	 <select id="loc_province_buyer" style="width:80px;" name="province_buyer"></select>
		 	 <input name="loc_province_buyer" type="hidden" />

		 	 <select id="loc_city_buyer" style="width:100px;" name="city_buyer"></select>
	 	 	 <input name="loc_city_buyer" type="hidden" />

			 <!--select id="loc_town" style="width:120px;" name="town"></select>
			 <input name="loc_town" type="hidden" /-->
	         </p>

	 </div>
	 </div>
	 
 	 <div class="span-8 last" id="search_tran_by_bought_time">
 
		 <p>
		 <span>购买时间范围</span>
	 	 </p>
		 <p>
	 	 	 <label for="bought_time_start">开始</label><br />
	 	 	 <input type="text" class="text" name="bought_time_start" id="bought_time_start" /> 
		 </p>
		 <p>
	 	 	 <label for="bought_time_end">结束</label><br />
	 	 	 <input type="text" class="text" name="bought_time_end" id="bought_time_end" />
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

	showLocation( '_saler' );
	showLocation( '_buyer' );

	$('#bought_time_start').simpleDatepicker({ startdate: 1912, enddate: 2012 });
	$('#bought_time_end').simpleDatepicker({ startdate: 1912, enddate: 2012 });
});
</script>
