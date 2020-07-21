/**
 * jEasyUI js addon by cmatrix.ru
 */
/**
 * Новые методы Tree
 */
$.extend($.fn.tree.methods, {
    touch : function(jq, params){
        if(!params) return false;
        
        var tree = $(jq[0]);
        var id = params;
        var node = tree.tree('find',id);
        if(node) tree.tree('select',node.target).tree('expandTo',node.target).tree('scrollTo',node.target);
    }
});

 
/**
 * Новые методы Tabs
 */
$.extend($.fn.tabs.methods, {
    /**
     * Получение количества открытых вкладок
     */
    count : function(jq, params){
        var tabs = $(jq[0]);
        return $('.tabs-header ul.tabs > li',tabs).length;
    },
    
    /**
     * Получение id вкладки по её index
     */
    getIdByIndex : function(jq, params){
        if(params === undefined) return false;
        
        var tabs = $(jq[0]);
        var index = params;
        
        var tab = tabs.tabs('getTab',index);
        var id = tab.hasAttr('id') ? tab.attr('id') : false;
        return id;
    },
    
    /**
     * Получение индекса вкладки по её id
     */
    getIndexById : function(jq, params){
        if(params === undefined) return false;
        
        var tabs = $(jq[0]);
        var id = params;
        var index = false;
        
        var _tabs = tabs.tabs('tabs');
		for(var i in _tabs){
			if(id == _tabs[i].attr('id')) { index = i; break; }
		}
		return index;
    },

    /**
     * Проверка наличия открытых табов по их id
     * @param array params массив проверяемых id
     * @retrun array массив проверяемых id, имеющих открытую табу
     */
    checkTabsById : function(jq, params){
        if(params === undefined) return false;
        
        var tabs = $(jq[0]);
        var arr_id = params;
        var arr_ret = [];
        
        // получить массив id табов
        var _arr = [];
        var _tabs = tabs.tabs('tabs');
		$(_tabs).each(function(key,value){
		    _arr.push(value.attr('id'));
		});
		
        // выбрать открытые табы
		$(arr_id).each(function(key,value){
		    if(_arr.indexOf(value) !== -1) arr_ret.push(value);
		});
		return arr_ret;

        
    },
    
    /**
     * Добавление или выделение табы, поиск по id
     */
    touch : function(jq, params){
        if(params === undefined) return false;
        
        var tabs = $(jq[0]);
        var index = tabs.tabs('getIndexById',params.id);
        
        // --- если нет табы, то создать        
        if(index === false){
            tabs.tabs('add',params);
        }
        // --- если есть таба, то выделить её
        else{                                           
			var tab = tabs.tabs('getSelected');
			var ind = tabs.tabs('getTabIndex',tab);
			if(index !== ind) tabs.tabs('select',parseInt(index));
		}
    }
    
});


/**
 * Новые методы Layuot
 */
 
//console.log($.fn.tabs.methods.options);
$.extend($.fn.layout.methods, {
    
    /**
     * получение массива регионов
     */
    /*regions : function(jq, params){
        var layout = $(jq[0]);
        var arr = [];
        layout.children('div.layout-panel').each(function(){
            if($(this).hasClass('layout-panel-west')) arr.push(layout.layout('panel','west'));
            if($(this).hasClass('layout-panel-east')) arr.push(layout.layout('panel','east'));
            if($(this).hasClass('layout-panel-north')) arr.push(layout.layout('panel','north'));
            if($(this).hasClass('layout-panel-south')) arr.push(layout.layout('panel','south'));
            if($(this).hasClass('layout-panel-center')) arr.push(layout.layout('panel','center'));
        });
        return arr;
    },*/
    
});


/**
 * Новые методы Datagrid
 */
$.extend($.fn.datagrid.methods, {
    
    /** 
     * getFieldsList - Получить плоский массив полей
     *  {
     *      имя_поля1 : тип_поля1,
     *      имя_поля2 : тип_поля2
     *  }
     */
    getFieldsList : function(jq, params){
        var datagrid = $(jq[0]);
        var fields = {};
        var columns = datagrid.datagrid('options').columns;
        console.log(columns);
        for(var i in columns){
            for(var j in columns[i]){
                //fields.push(columns[i][j].field);
                fields[columns[i][j].field] = {
                    type : columns[i][j].type || null,
                    title : columns[i][j].title,
                    hidden : columns[i][j].hidden || null
                };
            }
        }
        return fields;
    }
});

/**
 * Новые методы Form
 */
$.extend($.fn.form.methods,{
    getData : function(jq, params){
        
        // подсчёт количества эелементов в объекте (не работает .length)
        var fun_count = function(ob){
            var c = 0;
            for(var i in ob) c++;
            return c;
        };
        
        var and = {};
        $(jq[0]).find('tr.and').each(function(){
            var instance = this;
            var name = $(instance).attr('name');
            
            var or = [];
            $(this).find('tr.or').each(function(){
                var instance = this;
                
                var cond = $(instance).find('[name=cond]').val();
                var val = $(instance).find('[name=val]').val();
                if(cond && val){
                    or.push({
                        cond : cond,
                        val : val
                    });
                }
            });
            if(or.length) and[name] = or;
        });
        return fun_count(and) ? and : undefined;
    }
});


/**
 * Валидаторы
 */
$.extend($.fn.validatebox.defaults.rules, {
    strongLength : {
        validator: function(value, param){
            return value.length === param[0];
        },
        message: 'Длина значения не равно необходимой величине [ {0} ]'
    },
    minLength : {
        validator: function(value, param){
            return value.length >= param[0];
        },
        message: 'Длина значения меньше минимального значения [ {0} ]'
    },
    maxLength : {
        validator: function(value, param){
            return value.length <= param[0];
        },
        message: 'Длина значения превышает максимальное значение [ {0} ]'
    },
    hid : {
        validator: function(value, param){
            //return /^[(a-f|A-F)0-9]{32}$/.test(value);
            return /^[a-f0-9]{32}$/.test(value);
        },
        message: 'Неверный формат HID-строки'
    },
    date : {
        validator: function(value, param){
            //return !isNaN(Date.parse(value));
            return /^\d{4}-\d{2}-\d{2}$/.test(value);
        },
        message: 'Неверный формат даты'
    },
    time : {
        validator: function(value, param){
            return /^(([0,1][0-9])|(2[0-3])):[0-5][0-9]$/.test(value);
        },
        message: 'Неверный формат времени'
    },
    datetime : {
        validator: function(value, param){
            return /^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}(\.[0-9]+)?$/.test(value);
            //return /^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}$/.test(value);
        },
        message: 'Неверный формат даты, обязательно указывать дату и время'
    },
    serial : {
        validator: function(value, param){
            return /^\d{2}-\d{2}$/.test(value);
        },
        message: 'Неверный формат серии, должно быть XX-XX'
    },
    numReal : {
        validator: function(value, param){
            return /^-?\d*\.?\d+$/.test(value);
        },
        message: 'Неверный формат числа с плавающей точкой'
    },
    numInteger : {
        validator: function(value, param){
            return /^-?\d+$/.test(value);
        },
        message: 'Неверный формат целого числа'
    },
	maxFileSize: {
		validator: function(value, param){
			var file = $(this).parent().find('.textbox-value');
			var sizeKB = ((file[0].files[0].size)/1024).toFixed(0);
			return sizeKB<=param[0];
		},
		message: 'Размер файл должен быть меньше {0}Kb.'
	}
    
});


/**
 * Форматеры
 */
$.fn.datebox.defaults.formatter = function(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return y+'-'+(m<10?'0'+m:m)+'-'+(d<10?'0'+d:d);
};


/**
 * Парсеры
 */
$.fn.datebox.defaults.parser = function(s){
    if(s){
        var a = s.split('-');
        var d = new Date(parseInt(a[0]),parseInt(a[1])-1,parseInt(a[2]));
        return d;
    }else{
        return new Date();
    }
};


/**
 * missingMessage
 */
$.map([true],function(plugin){
    for(var i in $.fn){
        if($.fn[i].defaults && $.fn[i].defaults.missingMessage) $.fn[i].defaults.missingMessage = 'Это поле необходимо заполнить';
    }
});


/**
 * Кнопка стереть поле ввода
 */
  $.extend($.fn.textbox.methods,{
      
    addClearBtn: function(jq,iconCls){
        iconCls = iconCls || 'icon-clear';
        
        return jq.each(function(){
            var t = $(this);
            
            var opts = t.textbox('options');
            opts.icons = opts.icons || [];
            
            var f = false;
            for(var i in opts.icons) if(opts.icons[i].iconCls === iconCls) f = true;
            
            if(!f && t.hasClass('easyui-textbox')){
                opts.icons.unshift({
                    iconCls: iconCls,
                    handler: function(e){
                        $(e.data.target).textbox('clear').textbox('textbox').focus();
                        $(this).css('visibility','hidden');
                    }
                });
            }
            
            t.textbox();
            if (!t.textbox('getText')) t.textbox('getIcon',0).css('visibility','hidden');
            
            t.textbox('textbox').bind('keyup', function(){
                var icon = t.textbox('getIcon',0);
                if ($(this).val()) icon.css('visibility','visible');
                else icon.css('visibility','hidden');
            });
        });
    }
    
});
