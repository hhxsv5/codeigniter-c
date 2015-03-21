var order_id_pattern = /^\d{14,22}$/,
	phone_pattern = /^[\+]*[\d]{0,3}1[\d]{10}$/,
	passwd_pattern = /^[a-zA-Z0-9_\-~!@#\$%\^&\*\(\)\+\|]{6,18}$/,
	emp_id_pattern = /^[a-zA-Z]{2,6}[\d]{4,8}$/;
(function($) {
	/**
	 * loading库
	 * show: bool, true/show, false/hide
	 */
	$.fn.loading = function(show) {
		var tpl = [], $t = $(this);
		
		tpl.push('<div class="mask"></div>');
		tpl.push('<img class="loading" src="');
		tpl.push(BASE_URL);
		tpl.push('static/img/loading.gif"');
		tpl.push('>');
		tpl = tpl.join('');
		
		if (show) {
			$t.dialogClose();
			$t.find('.mask').length || $t.append(tpl);// 显示
			$t.find('.mask').height(document.body.scrollHeight);
			$t.css({'overflow': 'hidden', 'height': document.body.scrollHeight +'px'});
		} else {
			$t.find('.mask, .loading').remove(); // 隐藏
			$t.css({'overflow': 'auto', 'height': '100%'});
		}
	}
	
	var LODOP = null;
	/**
	 * 打印条码
	 */
	$.extend({
		printBarcode : function(type, receiver_name, receiver_phone, area, address, order_id) {
			if(LODOP == null){
				LODOP = getLodop();
			}
			LODOP.PRINT_INITA("0mm","0mm","40mm","27.99mm","");
			LODOP.ADD_PRINT_RECT("0mm","-0.26mm","39.16mm","24mm",2,0);
			LODOP.ADD_PRINT_TEXT("1.01mm","-0.05mm","36.01mm","2.01mm","姓名：" + receiver_name);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
			LODOP.ADD_PRINT_TEXT("4mm","-0.05mm","36.01mm","2.01mm","手机：" + receiver_phone + area);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
			LODOP.ADD_PRINT_TEXT("7.01mm","-0.05mm","36.01mm","4mm","地址：" + address);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
			LODOP.SET_PRINT_STYLEA(0,"SpacePatch",1);
			LODOP.ADD_PRINT_BARCODE("12.99mm","0.21mm","36.51mm","9mm","128Auto",order_id);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
			LODOP.SET_PRINT_STYLEA(0,"Horient",3);
			LODOP.SET_PRINT_STYLEA(0,"Vorient",3);
			LODOP.ADD_PRINT_TEXT(7,124,20,20, type);
			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
			LODOP.ADD_PRINT_RECT(3,120,20,20,0,1);
			LODOP.PRINT();
			//LODOP.PREVIEW();
			// LODOP.PRINT_DESIGN();
		}
	});
	
	$.extend({
		fixPrintLongBarcode : function(type,receiver_name, receiver_phone, area, address, order_id){
			if(LODOP == null){
				LODOP = getLodop();
			}

			LODOP.PRINT_INITA("0mm","0mm","300.3mm","20.11mm","");
			LODOP.ADD_PRINT_RECT("0.95mm","70.22mm","219.63mm","18.18mm",2,0);
			LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
			LODOP.ADD_PRINT_TEXT("7.67mm","120.12mm","69.06mm","6.09mm","姓名：" + receiver_name);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT("2.12mm","120.12mm","69.32mm","6.09mm","手机：" + receiver_phone + " (" + area + ")");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.ADD_PRINT_TEXT("13.23mm","120.12mm","76.73mm","6.09mm","地址：" + address);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
			LODOP.SET_PRINT_STYLEA(0,"SpacePatch",1);
			LODOP.ADD_PRINT_BARCODE("3.18mm","214.18mm","74.88mm","14.55mm","128Auto", order_id);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",7);
			LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
			LODOP.ADD_PRINT_LINE(-12,150,124,150,1,1);
			LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
			LODOP.ADD_PRINT_TEXT("2.91mm","98.95mm","16.14mm","15.88mm",area);
			LODOP.SET_PRINT_STYLEA(0,"FontName","黑体");
			LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
			LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
			LODOP.ADD_PRINT_ELLIPSE(14,306,50,50,0,1);
			LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
			LODOP.ADD_PRINT_TEXT(25,323,46,30,type);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",15);
			LODOP.SET_PRINT_STYLEA(0,"Bold",1);
			LODOP.SET_PRINT_STYLEA(0,"Vorient",2);
			LODOP.PRINT();
			//LODOP.PREVIEW();
			//LODOP.PRINT_DESIGN();
		}
	});


	/**
	 * 全局提示框库
	 * //type: success/info/warning/danger; text: some texts; timeout: 自动关闭时间(秒), 0 立即关闭, false 永不关闭; callback: 关闭dialog后的回调函数
	 */
	$.fn.dialog = function(type, text, timeout, callback) {
		var tpl = [], $t = $(this);
		tpl.push('<div class="mask"></div>');
		tpl.push('<div class="alert alert-' + type + ' was-diaolog" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only" title="关闭">关闭</span></button>');
		tpl.push(text);
		tpl.push('</div>');
		tpl = tpl.join('');
		
		$t.find('.was-diaolog').length || $t.append(tpl);// 显示
		$t.find('.was-diaolog').on('click', function(){
			$t.dialogClose(callback);
		});
		$t.find('.mask').height(document.body.scrollHeight);
		$t.css({'overflow': 'hidden', 'height': document.body.scrollHeight +'px'});
		
		(timeout === undefined) && (timeout = 3);//default 3secs
		
		
		if(timeout === 0){
			$t.dialogClose(callback);
		}else if(timeout === false){
			//永不不关闭
		}else if(!isNaN(timeout)){
			setTimeout(function(){
				$t.dialogClose(callback);
			}, timeout * 1000);
		}
	}
	
	/**
	 * 全局提示框关闭库
	 * callback: 关闭dialog后的回调函数
	 */
	$.fn.dialogClose = function(callback) {
		var $t = $(this);
		if($t.find('.was-diaolog').length){
			$t.find('.mask, .was-diaolog').fadeOut('slow', function(){
				$(this).remove();
			});// 隐藏
			$t.css({'overflow': 'auto', 'height': '100%'});
			if($.type(callback) === 'function'){
				callback();	
			}
		}
	}
	
	/**
	 * 全局确认框库
	 */
	$.fn.confirm = function(text, ok, cancel){
		var tpl = [], $t = $(this);
		tpl.push('<div class="modal fade was-confirm" tabindex="-1" data-action="" role="dialog" aria-labelledby="confirmModalTitle_123" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span title="关闭" aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button><h4 class="modal-title" id="confirmModalTitle_123">操作确认</h4></div><div class="modal-body">');
		tpl.push(text);
		tpl.push('</div><div class="modal-footer"><button type="button" class="btn btn-primary">确定</button><button type="button" class="btn btn-default" data-dismiss="modal">取消</button></div></div></div></div>');
		tpl = tpl.join('');
		
		var $confirm = $t.find('.was-confirm');
		$t.find('.was-confirm').length && $confirm.remove();
		$t.append(tpl);
		$confirm = $t.find('.was-confirm');
		$confirm.modal('show');// 显示
		if($.type(ok) === 'function'){
			$confirm.find('.btn-primary').off().on('click', function(e){
				ok(e);
				//$confirm.modal('hide');//隐藏
				$confirm.remove();
			});
		}
		
		if($.type(cancel) === 'function'){
			$confirm.find('.btn-default').off().on('click', function(e){
				cancel(e);
				//$confirm.modal('hide');//隐藏
				$confirm.remove();
			});
		}
	}
	
	/**
	 * AJAX全局设置
	 */
	$.ajaxSetup({
		global: true,
		timeout : 60000,
		beforeSend : function(XMLHttpRequest) {
			$('body').loading(true);
		},
		complete : function(XMLHttpRequest, textStatus) {
			//$('body').loading(false);
		},
		dataFilter: function (data, type) {
			$('body').loading(false);
			// 对Ajax返回的原始数据进行预处理
			return data;// 返回处理后的数据
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			// 通常 textStatus 和 errorThrown 之中
			// 只有一个会包含信息
			$('body').loading(false);
			if(errorThrown && errorThrown != ''){
				alert(textStatus + ', ' + errorThrown);
			}
		}
	});
	
	$(document).ready(function() {
		$('#userLi').on('click', function() {
			$('#userModal').modal('toggle');
		});
		//全局用户信息拉取
		$('#userModal').on('show.bs.modal', function(e){
			//拉取数据
			//$('button').attr('disabled', true);
			$.ajax({
				url : BASE_URL + 'web/emp/info',
				type : 'GET',
				data : {},
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					//$('button').attr('disabled', false);
					if(data.hasOwnProperty('code')){
						if(data.code == 0){
							var $userInput = $('#userForm .form-group');
							data = data.data;
							$userInput.eq(0).find('p').html(data.emp_id);
							$userInput.eq(1).find('p').html(data.type);
							$userInput.eq(2).find('p').html(data.name);
							$userInput.eq(3).find('input').val(data.phone);
							$userInput.eq(4).find('input').val(data.address);
							$userInput.eq(5).find('input').val('');
							$userInput.eq(6).find('input').val('');
							$userInput.eq(7).find('input').val('');
							$userInput.eq(8).find('p').html(data.last_login);
						}else {
							$('body').dialog('danger', data.msg);
						}
					}
				}
			});
		});
		
		//全局用户信息保存
		$('#saveUserBtn').on('click', function(){
			var phone = $.trim($('#phoneInput').val()),
			address = $.trim($('#addressInput').val()),
			oldpasswd = $.trim($('#oldPasswdInput').val()),
			newpasswd = $.trim($('#newPasswdInput').val()),
			repasswd = $.trim($('#rePasswdInput').val());
			
			if(phone == ''){
				$('body').dialog('warning', '手机号不能为空');
				return false;
			}
			
			if(address == ''){
				$('body').dialog('warning', '地址不能为空');
				return false;
			}
			
			if(!(phone_pattern.test(phone))){
				$('body').dialog('warning', '手机号格式不正确');
				return false;
			}
			
			
			if(oldpasswd != ''){
				if(!(passwd_pattern.test(oldpasswd))){
					$('body').dialog('warning', '旧密码格式不正确');
					return false;
				}
				if(newpasswd == ''){
					$('body').dialog('warning', '新密码不能为空');
					return false;
				}
				
				if(!(passwd_pattern.test(newpasswd))){
					$('body').dialog('warning', '新密码格式不正确');
					return false;
				}
				
				if(repasswd == ''){
					$('body').dialog('warning', '确认新密码不能为空');
					return false;
				}
				
				if(newpasswd != repasswd){
					$('body').dialog('warning', '两次输入密码不一致');
					return false;
				}
			}
			
			if(newpasswd != '' && oldpasswd == ''){
				$('body').dialog('warning', '旧密码不能为空');
				return false;
			}
			
			if(repasswd != ''){
				if(oldpasswd == ''){
					$('body').dialog('warning', '旧密码不能为空');
					return false;
				}
				if(newpasswd == ''){
					$('body').dialog('warning', '新密码不能为空');
					return false;
				}
			}
			
			if(oldpasswd != '' && oldpasswd == newpasswd){
				$('body').dialog('warning', '新旧密码不能相同');
				return false;
			}
			
			//保存用户数据
			$.ajax({
				url : BASE_URL + 'web/emp/save',
				type : 'POST',
				data : {
					phone: phone,
					address: address,
					old_passwd: oldpasswd,
					new_passwd: newpasswd
				},
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					if(data.hasOwnProperty('code')){
						if(data.code == 0){
							$('body').dialog('success', '保存成功！');
							$('#userModal').modal('toggle');
						}else {
							$('body').dialog('danger', data.msg);
						}
					}
				}
			});
		});
		
		if(WEAK){
			//弱口令提示
			$('body').confirm('<strong style="color: red;">你的密码强度太弱</strong>，是否立即修改密码？',function(){
				$('#userLi').trigger('click');
			});
		}
	});
})(jQuery)