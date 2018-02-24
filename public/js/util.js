function falert(message, title, callback){
	callback = (typeof(callback)== "undefined")? function(){dclose();}:callback;
	title = (typeof(title)== "undefined")? "消息框":title;
	
	var callFn = function(){
		callback()
	};
	easyDialog.open({
		  container : {
		    header : title,
		    content : message,
		    yesFn : function(){dclose();}
		  },
		  callback : callFn,
		  overlay : true
		});
}


function showmsg(msg){
	easyDialog.open({
		  container : {
		    content : msg
		  },
		  autoClose : 1000,
		  overlay : false
		});
}

function fconfirm(message, callback){
	callback = (typeof(callback)== "undefined")? function(){}:callback;
	var btnFn = function(){
			callback();
			dclose();
			return false;
		};
		easyDialog.open({
		  container : {
		    header : '消息提醒',
		    content : message,
		    yesFn : btnFn,
		    noFn : true
		  }
		});  
}


function dclose(){
	easyDialog.close();
}

//jQuery的cookie扩展
$.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');

            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

jQuery.removeHtml = function(s){  
    return (s)? jQuery("<p>").append(s).text(): "";  
} 

/**
 * 使用ajax提交数据
 */
function ajax_post(the_url,the_param,succ_callback,ftype,async){
    ftype = (typeof(ftype)== "undefined")? "jsonp":ftype;
    async = (typeof(async)== "undefined")? "true":async;
	$.ajax({
		type	: 'POST',
		cache	: false,
		url		: the_url,
		data	: the_param,
		success	: succ_callback,
		beforeSend:loading,
		complete:complete,
        async:async,
		dataType: ftype,
		jsonp:"jsonpcallback",
		error	: function(html){
			//alert("提交数据失败，请稍候再试");
		}
	});
}
/**
 * 使用ajax获取数据
 */
function ajax_get(the_url,succ_callback,ftype,async){
    ftype = (typeof(ftype)== "undefined")? "jsonp":ftype;
    async = (typeof(async)== "undefined")? "true":async;
	$.ajax({
		type	: 'GET',
		cache	: false,
		url		: the_url,
		success	: succ_callback,
		beforeSend:loading,
		complete:complete,
		dataType: ftype,
        async:async,
		jsonp:"jsonpcallback",
		error	: function(html){
			//alert("获取数据失败，请稍候再试");
		}
	});
}

function loading(){
	$("#loading").show();
}

function complete(){
	$("#loading").hide();
}



function checkLength(which, maxChars, idName) { 
	if (which.value.length > maxChars) 
	which.value = which.value.substring(0,maxChars); 
	var curr = maxChars - which.value.length; 
	document.getElementById(idName).innerHTML = curr.toString(); 
}

function showModel(url,title){
    ajax_get(url,function(a){
        $("#mymodelContent").html(a);
        $("#myModalLabel").html(title);
        $('#myModal').modal({
            keyboard: true
        })
    },'text');
}

