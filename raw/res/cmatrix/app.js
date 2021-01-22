// --- --- --- --- ---
var App = {
    
    init : function(){
        window.onbeforeunload = function (event) {
            var ret = App.unloader();
            if(!ret) return 'Есть открытые окна редактора';
        };
    },

	// генератор уникального случайного числа
	hid : function(){
		return Math.floor((Math.random()*1000000000000)+1);
	},

	// генератор id тега с суффиксом
	id : function(id,suffix){
		return id + (suffix === undefined ? "" : "-"+suffix);
	},

	// получение узла с конкретным id и конкретным суффиксом
	node : function(id,suffix){
		return $('#' + App.id(id,suffix));
	},

	// подготовка ajax данных
	ajaxdata : function(data,level){
		level = level || 0;
		if(level > 4) return data;
		else level = level + 1;
		
		if(data === undefined) return;
		
		var _data = {};
		for(var i in data){
			if(data[i] === undefined) continue;
			if(data[i] === null) _data[i] = '';
			else{
				switch(typeof data[i]){
					case 'function' : break;
					case 'boolean' : _data[i] = data[i] * 1; break;
					case 'object' : _data[i] = App.ajaxdata(data[i],level); break;
					default : if(data[i].jquery === undefined) _data[i] = data[i];
				}
			}
		}
		return _data;
	},

};

App.page = {
    
    /** 
     * прегрузка текущей страницы
     */
    reload : function(){
        document.location.href = document.location.href;
    },
    
    /**
     * перенаправление на другую страницу
     * @param string url - url для перенаправления
     * @param bool isNew - если в текущем окне, то false, если в новом - true
     */
    location : function(url,isNew){
        isNew = isNew || false;
        
        if(isNew) jQuery('<form action="' +url+ '" method="GET" target="_blank"></form>').appendTo('body').submit().remove();
        else location.replace(url);
    }
};    

App.page.location.new = function(url,param){
    var form = '';
    if(typeof param === 'object') for(var i in param) form += '<input type="hidden" name="' +i+ '" value="' +param[i]+ '"></input>';
    jQuery('<form action="' +url+ '" method="POST" target="_blank">' +form+ '</form>').appendTo('body').submit().remove();
};

/**
 * Счётчик ддя unloader
 */

App.unloader = function(){
    if(App.unloader.counter) return false;
    return true;
};
App.unloader.inc = function(){
    App.unloader.counter++;
};
App.unloader.dec = function(){
    App.unloader.counter--;
};
App.unloader.counter = 0;


// --- --- --- --- ---
function Form(url){
    this.Url = url || undefined;
    this.Content = undefined;
    this.Params = undefined;
    this.Hid = App.hid();
    //this.Hid = undefined;
    this.Pool = [];
    this.isGet = false;		// был ли get
    return this;
}

// --- --- --- --- ---
Form.path = function(url){
	return _cmfp(url);
};

// --- --- --- ---
// рекурсивная функция отработки this.Pool
Form.prototype.go = function(pool){
	if(!pool.length) return;

	var fun = pool.shift();
	if(typeof fun === 'function') fun.call(this,pool);
	else this.go(pool);
};

// --- --- --- ---
// определить html формы
Form.prototype.html = function(value){
	this.Content = value;
	return this;
};

// --- --- --- ---
// получить html формы и подгрузить js формы
Form.prototype.get = function(params,success,error){
	this.isGet = true;

	params = params || {};
	if(params.hid) this.Hid = params.hid;
	this.Params = params;

	var instance = this;

	// загрузим скрипт формы
	var fid = this.Url.split('/').join('_');
	var file = Form.path(this.Url + '/form.js');

	var getter = function(){
		$.ajax({
			url: Resource.path('cmatrix/form.php'),
			data : $.extend({},App.ajaxdata(params),{
				hid : instance.Hid,
				__form_url__ : instance.Url,
			}),
			type : 'POST',
			dataType : 'JSON',
			cache : false,
			async : true,               
			success : function(result){
				if(!~result.status){
					if(typeof error === 'function') error(result);
				    console.log(result.message);
				}
				else{
					instance.Content = result.data;
					if(typeof success === 'function') success(result);
					instance.go.call(instance,instance.Pool);
				}
			},
			error : function(jqXHR,textStatus,errorThrown){
				if(typeof error === 'function') error({
				    status : -1,
				    message : 'Возникла ошибка во время получения формы [ ' +instance.Url+ ' ]<br/>textStatus: ' + textStatus + '<br/>' +errorThrown,
				    jqxhr : jqXHR
				});
			}
		});
	};

	if(typeof window[fid] !== 'object'){
		$.getScript(file,getter);
	}
	else getter();

	return this;
};

// --- --- --- ---
// редирект на другую форму
Form.prototype.load = function(url,success,error){
    if(!url) return this;
    
    var instance  = this;
	this.Pool.push(
		function(pool){
			// здесь подгружаем объект формы
			var file = Form.path(url + '/form.js');
			var fid = url.split('/').join('_');
			if(typeof window[fid] !== 'object'){
				$.getScript(file,function(){
					instance.go.call(instance,instance.Pool);
				})
				.done(function(script,textStatus){
					if(typeof success === 'function') success({
					    status : 1,
					    message : textStatus,
					});
                })
                .fail(function(jqxhr, settings, exception){
                    console.log(jqxhr);
                    console.log(settings);
                    if(typeof error === 'function') error({
                        status : -1,
                        message : 'Возникла ошибка во время загрузки скрипта формы [ ' +url+ ' ]<br/>' +jqxhr.responseText,
                        jqxhr : jqxhr
                    });
                });
			}
			else instance.go.call(instance,instance.Pool);
		}
	);
    return this;
};

// --- --- --- ---
// редирект на другую форму
Form.prototype.relay = function(params,callback,tag){
	var fid = this.Url.split('/').join('_');
	var file = Form.path(this.Url + '/form.js');

	var getter = function(){
		if(typeof window[fid].relay === 'function'){
			window[fid].relay(params,callback,tag);
		}
	};

	if(typeof window[fid] !== 'object'){
		$.getScript(file,getter);
	}
	else getter();

	return this;
};

// --- --- --- ---
// положить html формы в tag
Form.prototype.put = function(tag){
	if(!tag) console.log('Не определён tag для адресации формы [ ' +this.Url+ ' ]');
	else{
        // если форма была инициализирована без url (пустая форма), то обозначить content положить стразу
        // иначе положить в pool функцию укладки контента
        if(!this.Url){
            html(this.Content);
            if(typeof callback === 'function') callback();
        }
        else this.Pool.push(
            function(pool){
                tag.html(this.Content);
                if(typeof callback === 'function') callback();
                this.go(pool);
            }
        );
	}
	return this;
};

// --- --- --- ---
Form.prototype.append = function(tag){
    if(!tag) console.log('Не определён tag для адресации формы [ ' +this.Url+ ' ]');
    else {
        // если форма была инициализирована без url (пустая форма), то обозначить content положить стразу
        // иначе положить в pool функцию укладки контента
        if(!this.Url){
            html(this.Content);
            if(typeof callback === 'function') callback();
        }
        else this.Pool.push(
            function(pool){
                tag.append(this.Content);
                if(typeof callback === 'function') callback();
                this.go(pool);
            }
        );
    }
	return this;
};

// --- --- --- ---
Form.prototype.prepend = function(tag){
    if(!tag) console.log('Не определён tag для адресации формы [ ' +this.Url+ ' ]');
    else {
        // если форма была инициализирована без url (пустая форма), то обозначить content положить стразу
        // иначе положить в pool функцию укладки контента
        if(!this.Url){
            html(this.Content);
            if(typeof callback === 'function') callback();
        }
        else this.Pool.push(
            function(pool){
                tag.prepend(this.Content);
                if(typeof callback === 'function') callback();
                this.go(pool);
            }
        );
    }
	return this;
};


// --- --- --- ---
// распарсить форму
Form.prototype.parser = function(callback){
	var instance  = this;
	
	this.Pool.push(
		function(pool){
			// здесь подгружаем объект формы
			var file = Form.path(this.Url + '/form.js');
			var fid = this.Url.split('/').join('_');
			var parser = function(){
				window[fid].parser.call(instance,pool,callback);
			};
			if(typeof window[fid] !== 'object'){
				$.getScript(file,function(){
					parser();
				});
			}
			else parser();
		}
	);

	// если не был get(), то нужно запустить отработку пула 
	if(!instance.isGet) instance.go.call(instance,instance.Pool);
	else if(instance.Content) instance.go.call(instance,instance.Pool);
	return this;
};

// --- --- --- ---
// получить hid формы
Form.prototype.getHid = function(){
	return this.Hid;
};




// --- --- --- --- ---
// --- --- --- --- ---
// --- --- --- --- ---
function Resource(url){
    this.Url = url || undefined;
    this.Content = undefined;
    this.Pool = [];
    this.Params = undefined;
};

// --- --- --- --- ---
Resource.path = function(url){
	return _cmrp(url);
};

















// --- --- --- --- ---
/**
 * Активация формы
 * @param string url url формы (имя_проекта/путь_к_форме)
 * @param function|null
 *  function - функция, которую нужно выполнить после получения контента формы, передав контент и парсер этого контента (content,pareser), это случай, когда контент нужно отдать и не отображать в собственном конструкторе
 *  null - в этом случае контент формы нужно отобразить и распарсить в собственном конструкторе.
 * @param object|array options опции формы
 */
//Form.require = function(url,callback,options){
//	var arr_src = url.split("/");
//	var arr_url = [];
//	var arr_fun = [];
//
//	var fun1 = function(_option,_arr){
//		var _a = _arr[_arr.length-1];
//		if(_a === undefined) _arr.push(_option);
//		else _arr.push(_a +"/"+ _option);
//		return _arr;
//	};
//
//	var fun2 = function(_url,_arr){
//		var _fun = _url.split("/").join(".");	// шаблон запускающей функции
//
//		var _str2 = "if(typeof fa === 'function') fa.call(null,options);";
//		_str2 += "else{";
//		_str2 += "	var f = window.Forms."+_fun+";";	// запускающая функция формы
//		_str2 += "	if(typeof f === 'function') f.call(null,"+callback+",options);";
//		_str2 += "}";
//
//		var _str = "function(options){";
//		_str += "	var fa = " +_arr[_arr.length-1]+ ";";
//		_str += "	if(typeof window.Forms."+_fun+" !== 'function'){"; // есть нет запускающей функции - загрузить скрипт
//		_str += "		$.getScript(Form.path('"+_url+"/form.js'),function(){";
//		_str +=				_str2;
//		_str += "		});";
//		_str += "	}else{";
//		_str +=				_str2;
//		_str += "	}";
//		_str += "};";
//
//		_arr.push(_str);
//		return _arr;
//	};
//
//	for(var i in arr_src) arr_url = fun1.call(null,arr_src[i],arr_url);
//	arr_url.reverse();
//	for(var j in arr_url) arr_fun = fun2.call(null,arr_url[j],arr_fun);
//
//	eval("var f = " + arr_fun[arr_fun.length-1]);
//	f.call(null,options);
//};

// --- --- --- --- ---
/** Получение контента формы передача её в callback функцию
 *  !!! ассинхронно, не json
 * 
 *  @param string url url формы
 *  @param null|object|array options опции получения контента
 *  @param function callback функция, которую нужно выполнить после получения контента, передав ей полученыый контент
 */
//Form.get = function(url,options,callback){
//	if(options !==null && typeof options === "object") options.__cm__url__ = url;
//	else options = { __cm__url__ : url };

	// $$getformpath - путь к php-скрипту получения контента формы
//	wi.ajax.async($$getformpath,options,callback,function(result){
//		wi.error(result.message);
//	});
//};

// --- --- --- --- ---
/** Выполнение php скрипта и передача результата в callback функцию 
 *  !!! ассинхронно, не json
 * 
 *  @param string url url формы
 *  @param null|object|array options опции получения контента
 *  @param function callback функция, которую нужно выполнить после получения контента, передав ей полученыый контент
 */
//Form.run = function(url,options,callback){
//	wi.ajax.async(Form.path(url),options,callback,function(result){
//		wi.error(result.message);
//	});
//};
