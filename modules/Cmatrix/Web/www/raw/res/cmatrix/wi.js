var Wi = {};

// --- --- --- --- --- --- ---
Wi.icons = {};
Wi.icons.button = function(icon,size){
	size = size || '16';
	icon = icon || 'def';
	return Wi.icons.buttons[size][icon] ? Wi.icons.buttons[size][icon] : Wi.icons.buttons[size].def;
};
Wi.icons.buttons = {
	"16" : {
		def    : 'icon-fugue-16x16-gear',
		blank  : 'icon-blank',
		add    : 'icon-humano2-16x16-list-add',
		edit   : 'icon-humano2-16x16-gtk-edit',
		copy   : 'icon-humano2-16x16-gnome-panel-window-list',
		delete : 'icon-humano2-16x16-list-remove',
		ok     : 'icon-fugue-16x16-tick',
		cancel : 'icon-fugue-16x16-cross',
		close  : 'icon-fugue-16x16-cross',
		'check-on'  : 'icon-cmatrix-16x16-check-on',
		'check-off' : 'icon-cmatrix-16x16-check-off',

		edd    : 'icon-humano2-16x16-gtk-edit',
		rem    : 'icon-humano2-16x16-list-remove',
		cpy    : 'icon-humano2-16x16-gnome-panel-window-list',
		move   : 'icon-humano2-16x16-folder-move',
		bug    : 'icon-humano2-16x16-tools-report-bug',
		clear  : 'icon-humano2-16x16-edit-clear',
		search : 'icon-humano2-16x16-system-search',
		report : 'icon-humano2-16x16-crack-attack',
		loader : 'icon-cmatrix-16x16-loader',
		
		lamp        : 'icon-fugue-16x16-light-bulb',
		'lamp-on'   : 'icon-fugue-16x16-light-bulb',
		'lamp-off'  : 'icon-fugue-16x16-light-bulb-off',
		

		//'config-menu' : 'icon-fugue-16x16-application-tile',
		'config-menu' : 'icon-fugue-16x16-edit-alignment-justify',
	},
	"48" : {
		message : 'icon-humano2-48x48-document-properties',
		alert   : 'icon-humano2-48x48-dialog-warning',
		error   : 'icon-humano2-48x48-process-stop',
		confirm : 'icon-humano2-48x48-system-help',
		//select : 'icon-humano2-48x48-system-help'
	}
};
Wi.icons.status = function(value){
    if(!Wi.icons.statuses[value]){
        console.log(value);
        return value;
    }
    var icon = Wi.icons.statuses[value].icon;
    var name = Wi.icons.statuses[value].name;
    return '<div style="width:100%" class="' +icon+ '" title="' +name+ '"></div>';
};

Wi.icons.statuses = {
    'A' : {
        'name' : 'Черновик',
        'icon' : 'icon-humano2-16x16-document',
    },
    'B' : {
        'name' : 'Оформлен',
        'icon' : 'icon-cmatrix-16x16-check-orange',
    },
    'C' : {
        'name' : 'Утверждён',
        'icon' : 'icon-cmatrix-16x16-check-green',
    },
    'X' : {
        'name' : 'Удалён',
        'icon' : 'icon-fugue-16x16-cross-script',
    },
    'S' : {
        'name' : 'Проведён',
        'icon' : 'icon-fugue-16x16-star-small',
        
    }
};

Wi.icons.bool = function(value){
    if(value === true) value = 'true';
    else if(value === false) value = 'false';
    else if(value === null) value = 'null';
    
    if(!Wi.icons.bools[value]) return value;
    var icon = Wi.icons.bools[value].icon;
    var name = Wi.icons.bools[value].name;
    return '<div style="width:100%;" class="' +icon+ '" title="' +name+ '"></div></div>';
};

Wi.icons.bools = {
    'true' : {
        'name' : 'Да',
        //'icon' : 'icon-cmatrix-16x16-check-green',
        'icon' : 'icon-cmatrix-16x16-check-on'
    },
    'false' : {
        'name' : 'Нет',
        //'icon' : 'icon-cmatrix-16x16-check-red',
        'icon' : 'icon-cmatrix-16x16-check-off'
    },
    'null' : {
        'name' : 'Пусто',
        //'icon' : 'icon-cmatrix-16x16-check-blue'
        'icon' : 'icon-empty'
    }
};



// --- --- --- --- ---
// --- --- --- --- ---
// --- --- --- --- ---
/**
 * Всплывающее окно
 */
Wi.message = function(options){
    if(typeof options == 'string') options = { content : options };
    
    options = $.extend({},Wi.message.defaults,options);
    options.id = options.id || App.hid();
    options = Wi.message.options.call(null,options);
    
    // --- --- --- ---
    options.msg = options.content; delete options.content;
    var onReady = options.onReady; delete options.onReady;
    var afterShow = options.afterShow; delete options.afterShow;
    var background = options.background; delete options.background;
    
    options.style = options.style || {};
    options.style = $.extend({},options.style,options.position);

    // --- --- --- ---
    if(typeof onReady === 'function') onReady.call(null,options.id);
    $.messager.show(options).css('background-color',background );
    if(typeof afterShow === 'function') afterShow.call(null,options.id);
};

// --- --- --- --- ---
Wi.message.defaults = {
    //title : 'Сообщение',
    title : null,
    
    content : 'Действие успешно выполнено',
    icon : Wi.icons.button('message',48),
    padding : 120,
    width : 'auto',
    //width : 400,
    height : 'auto',
    resizable : false,
    maximizable : false,
    
    //background : '#FFFCED',
    background : '#FFFAE4',
    
    timeout   : 1000,
    
    //showSpeed : 350,
    showSpeed : 200,

    posX      : 'center',
    //posY      : 'bottom',
    posY      : 'center',
    //marginX : 10,
    //marginY : 10,
    
    //showType  : null,
    //showType  : 'slide',
    showType  : 'fade',
    //showType  : 'show',
};

// --- --- --- --- ---
Wi.message.options = function(options){
    var content = function(options){
        if(!options.icon) return options.content;
        else{
            var content = '<div><table style="width:100%"><tr>';
            content += '<td><div class="' + options.icon + '" style="width:48px;height:48px"></td><td style="width:20px"/>';
            //content += '<td style="font-size:100%">' + options.content + '</td>';
            content += '<td><pre>' + options.content + '</pre></td>';
            content += '</tr></table></div>';
            return content;
        }
    };
    var position = function(options){
        var posX = {
            left : { left : options.marginX, right : '' },
            right : { left : '', right : options.marginX },
            center : { right : '' }
        };
        var posY = {
            top : { top : $("body").scrollTop() + options.marginY, bottom : '' },
            bottom : { top : '', bottom : $("body").scrollTop() + options.marginY },
            center : { bottom : '' }
        };
        if(!posX[options.posX] || !posY[options.posY]) console.log('Неизвестное положение окна [ ' +options.posX+' / ' +options.posY+ ' ]');
        return $.extend({},posX[options.posX],posY[options.posY]);
    };    
    
    options.content = content.call(null,options);
    options.position = position.call(null,options);
    delete options.icon;
    delete options.posX;
    delete options.posY;
    delete options.marginX;
    delete options.marginY;

    return options;
};




// --- --- --- --- ---
// --- --- --- --- ---
// --- --- --- --- ---
/**
 * Окно
 * 
 * @param object options
 *  - padding
 *  - onReady - перед отрисовкой
 *  - afterShow - после отрисовки
 *  - onClose - перед закрытием
 *  - & all easyui.windows options
 * 
 */
Wi.window = function(options){    
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},Wi.window.defaults,options);
    options.id = options.id || App.hid();
    options = Wi.window.options.call(null,options);

    // --- --- --- ---
    content = options.content; delete options.content;
    var padding = options.padding; delete options.padding;
    var onReady = options.onReady; delete options.onReady;
    var afterShow = options.afterShow; delete options.afterShow;
    var background = options.background; delete options.background;
    var timeout = options.timeout; delete options.timeout;

    // --- --- --- ---
    jQuery(content).appendTo('body')
        .css('padding',padding)
        .css('background-color',background);
        //.dialog(options);
        
    // --- --- --- ---
    if(typeof onReady === 'function') onReady.call(null,options.id);
    App.node(options.id).dialog(options);
    if(typeof afterShow === 'function') afterShow.call(null,options.id);

    App.node(options.id).dialog('panel').find('.close-button').parents('a').focus();

    // --- resize --- --- --- begin
    var win = App.node(options.id);
    
    var h1 = win.parent('.panel.window').height();
    var h2 = $(window).height() * 0.98;
    if(h1>h2) win.window('resize',{height : h2}).window('vcenter');

    var w1 = win.parent('.panel.window').width();
    var w2 = $(window).width() * 0.95;
    if(w1>w2) win.window('resize',{width : w2}).window('hcenter');
    // --- resize --- --- --- end
    
    // --- auto close --- --- --- begin
    //if(timeout !== 0) setTimeout(function(){ wi.getNode(options.id).dialog('close'); },timeout);
    if(timeout !== 0){
        var tag = App.node(options.id).dialog('panel').find('.close-button');
        tag.text('(' +timeout+ ')');
        var i = setInterval(function(){
            if(--timeout === 0) App.node(options.id).dialog('close');
            tag.text('(' +timeout+ ')');
        },1000);
    }
    // --- auto delete --- --- --- end
};

// --- --- --- --- ---
Wi.window.defaults = {
    title : 'Стандартное окно',
    content : '',
    modal : false,
    width : 'auto',
    height : 'auto',
    resizable : true,
    collapsible : false,
    minimizable : false,
    maximizable : true,
    shadow : false,
    //openAnimation : 'slide',
    //openDuration : 1000,
    //onClose : function(){$(this).dialog('destroy');},
    buttons : ['close'],

    padding : 5,
    onReady : undefined,
    background : '#FFF',
    timeout : 0
};

// --- --- --- --- ---
Wi.window.options = function(options){
    var content = function(options){
        return '<div id="' +options.id+ '">'+ options.content +'</div>';
    };
    var width = function(value){
        value = value || 400;
        if(typeof value === 'string' && ~value.indexOf('%')) return $(window).width() * parseInt.call(null,value) / 100;
        else if(value === 'auto') return value;
        else return parseInt.call(null,value);
    };
    var height = function(value){
        value = value || 250;
        if(typeof value === 'string' && ~value.indexOf('%')) return $(window).height() * parseInt.call(null,value) / 100;
        else if(value === 'auto') return value;
        else return parseInt.call(null,value);
    };
    var padding = function(value){
        var cicle = function(value){
            for(var i in value) value[i] += 'px';
            return value;
        };
        if(typeof value === 'number') return value +'px';
        else if(typeof value === 'object') return cicle.call(null,value).join(' ');
        else return value;
    };
    var onClose = function(options){
        var onClose = options.onClose; delete options.onClose;
        return function(){
            if(typeof onClose === 'function') onClose.call(null,options.id);
            $(this).dialog('destroy');
        };
    };
    
    var buttons = function(id,value){
        var buttons_defs = {
            plain : true,
            icon : 'def',
            handler : function(){}
        };
        var buttons_pars = {
            close : {
                text : 'Закрыть <span class="close-button"></span>',
                icon : Wi.icons.button('close'),
                handler : function(){ $('#'+id).dialog('close'); },
            },
        };
        var string = function(id,value){
            if(value === 'empty') return { plain : true, disabled : true };
            
            var ob = $.extend({},buttons_defs,buttons_pars[value] || {
                text : value,
                icon : Wi.icons.button(value),
            });
            ob.id = id;
            ob.icon = ~ob.icon.indexOf('icon-') ? ob.icon : Wi.icons.button(ob.icon);
            ob.iconCls = ob.icon; delete ob.icon;
            return ob;
        };
        var object = function(id,value){
            if(typeof value.handler === 'string' && buttons_pars[value.handler]){
                value.handler = buttons_pars[value.handler].handler || null;
            }
            var ob = $.extend({},buttons_defs,value);
            ob.id = id;
            ob.icon = ~ob.icon.indexOf('icon-') ? ob.icon : Wi.icons.button(ob.icon);
            ob.iconCls = ob.icon; delete ob.icon;
            return ob;
        };
        
        if(!value) return;  // если нет кнопок
        var buttons = [];
        for(var i in value){
            //var id = value[i].id || App.hid();
            // кнопкам передать id окна, для манипуляции с окном
            if(typeof value[i] === 'string') buttons[i] = string.call(null,id,value[i]);
            else if(typeof value[i] === 'object') buttons[i] = object.call(null,id,value[i]);
            else console.log('Неизвестный button тип кнопки' + typeof(value[i]));
        }
        return buttons;
    };
    
    options.content = content.call(null,options);
    options.width = width.call(null,options.width);
    options.height = height.call(null,options.height);
    options.padding = padding.call(null,options.padding);
    options.buttons = buttons.call(null,options.id,options.buttons);
    options.onClose = onClose.call(null,options);
    
    return options;
};





// --- --- --- --- ---
// --- --- --- --- ---
// --- --- --- --- ---
/**
 * Сообщение
 */
Wi.alert = function(options){
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},Wi.alert.defaults,options);
    options = Wi.alert.options.call(null,options);

    // --- --- --- ---
    Wi.window.call(null,options);
};

// --- --- --- --- ---
Wi.alert.defaults = {
    title : 'Сообщение',
    content : 'Действие успешно выполнено',
    icon : 'alert',
    padding : 10,
    modal : true,
    resizable : false,
    maximizable : false,
    //background : '#FFFCED',
    timeout   : 59,
};

// --- --- --- --- ---
Wi.alert.options = function(options){
    var content = function(options){
        var icon = Wi.icons.button(options.icon,48);
        var content = '<div><table><tr>';
        if(options.icon) content += '<td><div class="' + icon + '" style="width:48px;height:48px;padding-right:20px"></td><td style="width:20px"/>';
        //content += '<td style="font-size:100%"><pre>' + options.content + '</pre></td>';
        content += '<td><pre>' + options.content + '</pre></td>';
        content += '</tr></table></div>';
        return content;
    };
    options.content = content.call(null,options);
    delete options.icon;

    return options;
};



// --- --- --- --- ---
// --- --- --- --- ---
// --- --- --- --- ---
/**
 * Ошибка
 */
Wi.error = function(options){
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},Wi.error.defaults,options);
    options = Wi.error.options.call(null,options);
    
    // --- --- --- ---
    Wi.alert.call(null,options);
};

// --- --- --- --- ---
Wi.error.defaults = {
    title : 'Внимание',
    content : 'Действие завершилось с ошибкой',
    icon : 'error',
    background : '#FFE2E2',
    timeout   : 0,
};

// --- --- --- --- ---
Wi.error.options = function(options){
    return options;
};



// --- --- --- --- ---
// --- --- --- --- ---
// --- --- --- --- ---
/**
 * @param options
 *  - array onOk 
 */
Wi.confirm = function(options){
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},Wi.confirm.defaults,options);
    options.id = options.id || App.hid();   // для закрытия по кнопке Да
    options = Wi.confirm.options.call(null,options);

    // --- --- --- ---
    Wi.alert.call(null,options);
};

// --- --- --- --- ---
Wi.confirm.defaults = {
    title : 'Внимание',
    content : 'Вы уверены ?',
    icon : 'confirm',
    modal : true,
    timeout : 0,
};

// --- --- --- --- ---
Wi.confirm.options = function(options){
    var buttons = function(options){
        options.buttons = options.buttons || [
            {
                text : ' Да ',
                icon : 'ok',
                width : 70,
                handler : function(){
                    if(typeof options.onOk === 'function'){
                        var ret = options.onOk.call(null,options.id);
                        if(ret !== false) App.node(options.id).dialog('close');
                    }
                    else App.node(options.id).dialog('close');
                }
            },{
                text : ' Нет ',
                icon : 'cancel',
                width : 70,
                handler : 'close'
            }
        ];
        return options.buttons;
    };

    options.buttons = buttons.call(null,options);
    
    return options;
};




// --- --- --- --- ---
// --- --- --- --- ---
// --- --- --- --- ---
Wi.form = function(options){
    if(typeof options === 'string') options = { content : options };
    options = $.extend({},Wi.form.defaults,options);
    options.id = options.id || App.hid();   // для закрытия по кнопке Да
    options = Wi.form.options.call(null,options);

    // --- для формы --- begin
    var url = options.url || undefined; delete options.url;
    // var onSubmit = options.onSubmit || function(param){
    //     if(!App.node('form',options.id).form('validate')){
    //         Wi.alert("Заполните все обязательные поля");
    //         return false;
    //     }
    // }; delete options.onSubmit;
    var onSubmit = options.onSubmit; delete options.onSubmit;
    var success = options.success || function(){
        App.node(options.id).dialog('close');        
    }; delete options.success;
    // --- для формы --- end

    // --- --- --- ---
    Wi.window.call(null,options);
    
    // --- --- --- ---
    // на все textbox доюавить обработчик по enter
    App.node(options.id).find('.cm-textbox').each(function(){
        $(this).textbox().textbox("textbox").on("keydown",function(e){
            var instance = this;
            if(e.keyCode === 13){
                if(!App.node('form',options.id).form('validate')) Wi.alert({
                    content : 'Заполните все обязательные поля',
                    onClose : function(){ $(instance).focus(); }
                });
                else App.node('form',options.id).form("submit");
            }
        });
    });
    
    App.node(options.id).find('.cm-combobox').combobox();
    Wi.form.focus.call(null,options);
    
    // для формы --- --- --- --- begin
    var form_opt = {};
    if(url) form_opt['url'] = url;
    if(onSubmit) form_opt['onSubmit'] = onSubmit;
    if(success) form_opt['success'] = success;
    // для формы --- --- --- --- end

    App.node('form',options.id).form(form_opt);
};

// --- --- --- --- ---
Wi.form.defaults = {
    resizable : true,
    maximizable : false,
    title : 'Введите данные',
    content : {},
    modal : true,
    timeout : 0,
    width : 400
};

// --- --- --- --- ---
Wi.form.options = function(options){
    var buttons = function(options){

        // --- --- --- --- ---
        var buttons = options.buttons || [
            {
                text : options.okText || ' Сохранить ',
                icon : 'ok',
                handler : function(){
                    if(!App.node('form',options.id).form('validate')) Wi.alert("Заполните все обязательные поля");
                    else{
                        if(typeof options.onOk === 'function') options.onOk.call(null,options.id);
                        else{
                            var form_opt = App.node('form',options.id).form('options');
                            
                            // если есть url, то сделать submit для формы, success будет вызван методами формы, и вернёт data от post скрипта
                            // иначе принудительно собрать значения полей и вызвать success
                            if(form_opt.url) App.node('form',options.id).form('submit');
                            else{
                                if(typeof form_opt.success === 'function'){
                                    form_opt.success.call(null,{
                                            status : 1,
                                            message : 'OK',
                                            data : Wi.form.getter.call(null,options)
                                        }
                                    );
                                }
                                App.node(options.id).dialog('close');
                            }
                        } 
                    }
                }
            },{
                //text : ' Закрыть  <span class="close-button"></span>',
                text : options.closeText || ' Закрыть ',
                icon : 'cancel',
                handler : 'close'
            }
        ];
        return buttons;
    };

    // --- --- --- --- ---
    var content = function(options){
        
        // --- --- --- --- ---
        var fun_string = function(options){
            return options.content;
        };
        
        // --- --- --- --- ---
        var fun_object = function(options){
            
            // --- --- --- --- ---
            var fun_size = function(value){
                if(typeof value === 'string') return value;
                else return value+'px';
            };
            
            // --- --- --- --- ---
            var fun_textbox = function(id,i,cur,password){
                password = password || false;
                var width = cur.width ? fun_size(cur.width) : '100%';
                var height = cur.height ? fun_size(cur.height) : '30px';
                
                var arr_style = [];
                arr_style.push('width:' + width);
                arr_style.push('height:' + height);
                
                var nn = '<input id="' +id+ '" class="cm-textbox" name="' +i+ '" style="' +arr_style.join(';')+ '"';
                if(password) nn += 'type="password"';
                if(cur.value) nn += 'value="' +cur.value+ '"';
                if(cur.raw){
                    var arr = [];
                    for(var i=0 in cur.raw){ arr.push(i+':'+cur.raw[i]); }
                    nn += ' data-options="' +arr.join(',')+ '"';
                }
                nn += '>';
                return nn;
            };
            
            // --- --- --- --- ---
            var fun_combobox = function(id,i,cur){
                var width = cur.width ? fun_size(cur.width) : '100%';
                var height = cur.height ? fun_size(cur.height) : '30px';
                
                var arr_style = [];
                arr_style.push('width:' + width);
                arr_style.push('height:' + height);
                
                var nn = '<input id="' +id+ '" class="cm-combobox" name="' +i+ '" style="' +arr_style.join(';')+ '"';
                if(cur.value) nn += 'value="' +cur.value+ '"';
                if(cur.raw){
                    var arr = [];
                    for(var i=0 in cur.raw){ arr.push(i+':'+cur.raw[i]); }
                    nn += ' data-options="' +arr.join(',')+ '"';
                }
                nn += '>';
                return nn;
            };
            
            // --- --- --- --- ---
            var fun_input = function(i,cur){
                return '<input type="hidden" name="' +i+ '" value="' +cur+ '">';
            };
            
            // --- --- --- --- ---
            // --- --- --- --- ---
            // --- --- --- --- ---
            var arr0 = [];
            var arr1 = [];
            arr0.push('<form id="form-' +options.id+ '" method="POST">');
            
            for(var i in options.content){
                var cur = options.content[i];
                if(typeof cur === 'object'){
                    var id = cur.id || App.hid();
                
                    options.arr[i].id = id;
                    var type = cur.type || 'textbox';
                    
                    var nn = cur.label ? '<div style="font-weight:bold;margin-bottom:4px;">'+ cur.label +'</div>' : '';
                    switch(type){
                        case 'textbox'     : nn += fun_textbox(id,i,cur); break;
                        case 'passwordbox' : nn += fun_textbox(id,i,cur,true); break;
                        case 'combobox'    : nn += fun_combobox(id,i,cur); break;
                    }
                    arr1.push(nn);
                }
                else{
                    var nn = fun_input(i,cur);
                    arr1.push(nn);
                }
            }
            
            arr0.push(arr1.join('<div style="height:15px"></div>'))
            arr0.push('</form>');
            return arr0.join('');
        };
        
        if(typeof options.content === 'object') return fun_object.call(null,options);
        else return fun_string.call(null,options);
    };

    // --- --- --- --- ---
    // --- --- --- --- ---
    if(typeof options.content === 'object') options.arr = options.content;
    options.buttons = buttons.call(null,options);
    options.content = content.call(null,options);

    return options;
};

// --- --- --- --- ---
Wi.form.getter = function(options){
    if(!options.arr) return null;
    
    var fun_textbox = function(cur){
        return App.node(cur.id).textbox('getValue');
    };
    var fun_combobox = function(cur){
        return App.node(cur.id).combobox('getValue');
    };

    var arr = {};
    for(var i in options.arr){
        var cur = options.arr[i];
        var type = cur.type || 'textbox';
        
        switch(type){
            case 'textbox' :
                arr[i] = fun_textbox(cur);
                break;
            case 'passwordbox' :
                arr[i] = '******';
                break;
            case 'combobox' :
                arr[i] = fun_combobox(cur);
                break;
        }
    }
    return arr;
};

// --- --- --- --- ---
// сфокусироваться на первом поле ввода
Wi.form.focus = function(options){
    if(!options.arr) return;
    
    var fun = function(obj){
        for(var i in obj){
            if(typeof obj[i] === 'object') return obj[i];
        }
    };
    
    var data = fun(options.arr);
    if(data.type === 'textbox'){
        App.node(data.id).textbox('textbox').focus();
    }
    else if(data.type === 'combobox'){
        App.node(data.id).combobox('textbox').focus();
    }
};



var wi2 = {};

// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
/**
 * Генератор уникального случайного числа
 */ 
wi2.hid = function(){
	return Math.floor((Math.random()*1000000000000)+1);
};

/**
 * Генератор id тега с суффиксом
 */ 
wi2.id = function(id,suffix){
    return "#"+ id + (suffix === undefined ? "" : "-"+suffix);
};

/**
 * Получение узла с конкретным id и конкретным суффиксом
 */ 
wi2.node = function(id,suffix){
    return $(wi2.id(id,suffix));
};


// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
wi2.page = {
    
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
        
        if(isNew) jQuery("<form action=\"" +url+ "\" target=\"_blank\">").appendTo("body").submit().remove();
        else location.replace(url);
    }    
    
};





// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
wi2.ajax = {};

wi2.ajax.data = function(data,level){
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
                case 'object' : _data[i] = wi2.ajax.data(data[i],level); break;
                default : if(data[i].jquery === undefined) _data[i] = data[i];  
            }
        }
    }
    return _data;
};


/**
 * Вспоывающее окно
 */
wi2.message = function(options){
    if(typeof options == 'string') options = { content : options };
    
    options = $.extend({},wi2.message.defaults,options);
    options.id = options.id || wi2.hid();
    options = wi2.message.options.call(null,options);
    
    // --- --- --- ---
    options.msg = options.content; delete options.content;
    var onReady = options.onReady; delete options.onReady;
    var afterShow = options.afterShow; delete options.afterShow;
    var background = options.background; delete options.background;
    
    options.style = options.style || {};
    options.style = $.extend({},options.style,options.position);

    // --- --- --- ---
    if(typeof onReady === 'function') onReady.call(null,options.id);
    $.messager.show(options).css('background-color',background );
    if(typeof afterShow === 'function') afterShow.call(null,options.id);
};

// --- --- --- --- ---
wi2.message.defaults = {
    title : 'Сообщение',
    content : 'Действие успешно выполнено',
    //icon : wi2.icons.button('message',48),
    padding : 20,
    //width : 'auto',
    width : 400,
    height : 'auto',
    resizable : false,
    maximizable : false,
    //background : '#FFFCED',
    timeout   : 1750,
    showSpeed : 350,
    
    posX      : 'center',
    posY      : 'bottom',
    marginX : 10,
    marginY : 10,
    
    //showSpeed : 100,
    //showType  : null,
    //showType  : 'slide',
    showType  : 'fade',
    //showType  : 'show',
};

// --- --- --- --- ---
wi2.message.options = function(options){
    var content = function(options){
        if(!options.icon) return options.content;
        else{
        var content = '<div><table style="width:100%"><tr>';
        content += '<td><div class="' + options.icon + '" style="width:48px;height:48px"></td><td style="width:20px"/>';
        //content += '<td style="font-size:100%">' + options.content + '</td>';
        content += '<td><pre>' + options.content + '</pre></td>';
        content += '</tr></table></div>';
        return content;
        }
    };
    var position = function(options){
        var posX = {
            left : { left : options.marginX, right : '' },
            right : { left : '', right : options.marginX },
            center : { right : '' }
        };
        var posY = {
            top : { top : $("body").scrollTop() + options.marginY, bottom : '' },
            bottom : { top : '', bottom : $("body").scrollTop() + options.marginY },
            center : { bottom : '' }
        };
        if(!posX[options.posX] || !posY[options.posY]) console.log('Неизвестное положение окна [ ' +options.posX+' / ' +options.posY+ ' ]');
        return $.extend({},posX[options.posX],posY[options.posY]);
        
    };    
    
    options.content = content.call(null,options);
    options.position = position.call(null,options);
    delete options.icon;
    delete options.posX;
    delete options.posY;
    delete options.marginX;
    delete options.marginY;

    return options;
};


/**
 * Окно
 * 
 * @param object options
 *  - padding
 *  - onReady - перед отрисовкой
 *  - afterShow - после отрисовки
 *  - onClose - перед закрытием
 *  - & all easyui.windows options
 * 
 */
wi2.window = function(options){    
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},wi2.window.defaults,options);
    options.id = options.id || wi2.hid();
    
    // --- --- --- ---
    options = wi2.window.options.call(null,options);

    // --- --- --- ---
    content = options.content; delete options.content;
    var padding = options.padding; delete options.padding;
    var onReady = options.onReady; delete options.onReady;
    var afterShow = options.afterShow; delete options.afterShow;
    var background = options.background; delete options.background;
    var timeout = options.timeout; delete options.timeout;

    // --- --- --- ---
    jQuery(content).appendTo('body')
        .css('padding',padding)
        .css('background-color',background);
        //.dialog(options);
        
    // --- --- --- ---
    if(typeof onReady === 'function') onReady.call(null,options.id);
    wi.getNode(options.id).dialog(options);
    if(typeof afterShow === 'function') afterShow.call(null,options.id);

    wi.getNode(options.id).dialog('panel').find('.close-button').parents('a').focus();

    // --- resize --- --- ---
    var win = wi.getNode(options.id);
    var h1 = win.parent('.panel.window').height();
    var h2 = $(window).height() * 0.98;
    if(h1>h2) win.window('resize',{height : h2}).window('vcenter');

    var w1 = win.parent('.panel.window').width();
    var w2 = $(window).width() * 0.95;
    if(w1>w2) win.window('resize',{width : w2}).window('hcenter');
    
    // --- auto delete --- --- ---
    //if(timeout !== 0) setTimeout(function(){ wi.getNode(options.id).dialog('close'); },timeout);
    if(timeout !== 0){
        var tag = wi.getNode(options.id).dialog('panel').find('.close-button');
        tag.text('(' +timeout+ ')');
        var i = setInterval(function(){
            if(--timeout === 0) wi.getNode(options.id).dialog('close');
            tag.text('(' +timeout+ ')');
        },1000);
    }
};

// --- --- --- --- ---
wi2.window.defaults = {
    title : 'Стандартное окно',
    content : '',
    modal : false,
    width : 'auto',
    height : 'auto',
    resizable : true,
    collapsible : false,
    minimizable : false,
    maximizable : true,
    shadow : false,
    //openAnimation : 'slide',
    //openDuration : 1000,
    //onClose : function(){$(this).dialog('destroy');},
    buttons : ['close'],

    padding : 5,
    onReady : undefined,
    background : '#FFF',
    timeout : 0
};

// --- --- --- --- ---
wi2.window.options = function(options){
    var content = function(options){
        return '<div id="' +options.id+ '">'+ options.content +'</div>';
    };
    var width = function(value){
        value = value || 400;
        if(typeof value === 'string' && ~value.indexOf('%')) return $(window).width() * parseInt.call(null,value) / 100;
        else if(value === 'auto') return value;
        else return parseInt.call(null,value);
    };
    var height = function(value){
        value = value || 250;
        if(typeof value === 'string' && ~value.indexOf('%')) return $(window).height() * parseInt.call(null,value) / 100;
        else if(value === 'auto') return value;
        else return parseInt.call(null,value);
    };
    var padding = function(value){
        var cicle = function(value){
            for(var i in value) value[i] += 'px';
            return value;
        };
        if(typeof value === 'number') return value +'px';
        else if(typeof value === 'object') return cicle.call(null,value).join(' ');
        else return value;
    };
    var onClose = function(options){
        var onClose = options.onClose; delete options.onClose;
        return function(){
            if(typeof onClose === 'function') onClose.call(null,options.id);
            $(this).dialog('destroy');
        };
    };
    var buttons = function(id,value){
        var buttons_defs = {
            plain : true,
            icon : 'def',
            handler : function(){}
        };
        var buttons_text = {
            close : 'Закрыть'
        };
        var string = function(id,value){
            return {
                iconCls : wi2.icons.button(value),
                text : buttons_text[value] ? buttons_text[value] : value,
                plain :  true,
                handler : 'close'
            };
        };
        var object = function(id,value){
            var ob = $.extend({},buttons_defs,value);
            ob.icon = ~ob.icon.indexOf('icon-') ? ob.icon : wi2.icons.button(ob.icon);
            ob.iconCls = ob.icon; delete ob.icon;
            return ob;
        };
        
        if(!value) return;  // если нет кнопок
        var buttons = [];
        for(var i in value){
            value[i].id = wi2.hid();
            if(typeof value[i] === 'string') buttons[i] = string.call(null,id,value[i]);
            else if(typeof value[i] === 'object') buttons[i] = object.call(null,id,value[i]);
            else console.log('Неизвестный button тип кнопки' + typeof(value[i]));
            
            if(typeof buttons[i].handler === 'string' && buttons[i].handler === 'close'){
                buttons[i].text += ' <span class="close-button"></span>';
                buttons[i].handler = function(){ $('#'+id).dialog('close'); };
            }
        }
        return buttons;
    };
    
    options.content = content.call(null,options);
    options.width = width.call(null,options.width);
    options.height = height.call(null,options.height);
    options.padding = padding.call(null,options.padding);
    options.buttons = buttons.call(null,options.id,options.buttons);
    options.onClose = onClose.call(null,options);
    
    return options;
};









/**
 * Сообщение
 */
wi2.alert = function(options){
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},wi2.alert.defaults,options);
    options = wi2.alert.options.call(null,options);

    // --- --- --- ---
    wi2.window.call(null,options);
};

// --- --- --- --- ---
wi2.alert.defaults = {
    title : 'Сообщение',
    content : 'Действие успешно выполнено',
    icon : 'alert',
    padding : 10,
    modal : true,
    resizable : false,
    maximizable : false,
    //background : '#FFFCED',
    timeout   : 59,
};

// --- --- --- --- ---
wi2.alert.options = function(options){
    var content = function(options){
        var icon = wi2.icons.button(options.icon,48);
        var content = '<div><table><tr>';
        if(options.icon) content += '<td><div class="' + icon + '" style="width:48px;height:48px;padding-right:20px"></td><td style="width:20px"/>';
        //content += '<td style="font-size:100%"><pre>' + options.content + '</pre></td>';
        content += '<td><pre>' + options.content + '</pre></td>';
        content += '</tr></table></div>';
        return content;
    };
    options.content = content.call(null,options);
    delete options.icon;

    return options;
};



/**
 * Ошибка
 */
wi2.error = function(options){
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},wi2.error.defaults,options);
    options = wi2.error.options.call(null,options);
    
    // --- --- --- ---
    wi2.alert.call(null,options);
};

// --- --- --- --- ---
wi2.error.defaults = {
    title : 'Внимание',
    content : 'Возникла ошибка',
    icon : 'error',
    //background : '#FFD1D1',
    timeout   : 0,
};

// --- --- --- --- ---
wi2.error.options = function(options){
    return options;
};




/**
 * @param options
 *  - array onOk 
 */
wi2.confirm = function(options){
    if(typeof options == 'string') options = { content : options };
    options = $.extend({},wi2.confirm.defaults,options);
    options = wi2.confirm.options.call(null,options);

    // --- --- --- ---
    wi2.alert.call(null,options);
};

// --- --- --- --- ---
wi2.confirm.defaults = {
    title : 'Внимание',
    content : 'Вы уверены ?',
    icon : 'confirm',
    modal : true,
    timeout : 0,
};

// --- --- --- --- ---
wi2.confirm.options = function(options){
    var buttons = function(options){
        options.buttons = options.buttons || [
            {
                text : ' Да ',
                icon : 'ok',
                width : 70,
                handler : function(){
                    if(typeof options.onOk === 'function') options.onOk.call(null,options.id);
                }
            },{
                text : ' Нет ',
                icon : 'cancel',
                width : 70,
                handler : 'close'
            }
        ];
        return options.buttons;
    };

    options.buttons = buttons.call(null,options);
    
    return options;
};

// --- --- --- --- --- 
// --- --- --- --- --- 
// --- --- --- --- --- 
// --- --- --- --- --- 
// --- --- --- --- --- 
// --- --- --- --- --- 
// --- --- --- --- --- 
// --- --- --- --- --- 
function Form2(url){
    this.Url = url || undefined;
    this.Content = undefined;
    this.Pool = [];
    this.Params = undefined;
}

// --- --- --- ---
// рекурсивная функция отработки this.Pool
Form2.prototype.go = function(pool){
    if(!pool.length) return;
    
    var fun = pool.shift();
    if(typeof fun === 'function') fun.call(this,pool);
    else this.go(pool);
};

// --- --- --- ---
// просто dump this в console
Form2.prototype.dump = function(){
    console.log('---dump---');
    console.log(this);
    console.log('---dump---');
};

// --- --- --- ---
// определить html формы
Form2.prototype.html = function(value){
    this.Content = value;
    return this;
};

// --- --- --- ---
// получить html формы и подгрузить js формы
Form2.prototype.get = function(params,callback){
    params = params || {};
    params.hid = params.hid || wi2.hid();
    
    this.Params = params;

    var instance = this;

    // загрузим скрипт формы
    var fid = this.Url.split('/').join('_');
    var file = Form.path(this.Url + '/form.js');
    
    var getter = function(){
        $.ajax({
            url: Resource.path('cmatrix/form/get2.php'),
            data : $.extend({},wi2.ajax.data(params),{
                __cm__url__ : instance.Url,
            }),
            type : 'POST',
            dataType : 'JSON',
            cache : false,
            async : true,
            success : function(result){
                if(!~result.status) wi2.error(result.message);
                else{
                    instance.Content = result.data;
                    if(typeof callback === 'function') callback(instance.Content,function(){
                        instance.go.call(instance,instance.Pool);
                    });
                    else instance.go.call(instance,instance.Pool);
                }
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
Form2.prototype.relay = function(params,callback,tag){
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
Form2.prototype.put = function(tag){
    if(!tag) wi2.error('Не понятно куда положить html формы [ ' +this.Url+ ' ]');
    else{
        // если форма была инициализирована без url (пустая форма), то обозначить content положить стразу
        // иначе положить в pool функцию укладки контента
        if(!this.Url) html(this.Content);
        else this.Pool.push(
            function(pool){
                tag.html(this.Content);
                this.go(pool);
            }
        );
    }
    return this;
};

// --- --- --- ---
// распарсить форму
Form2.prototype.parser = function(){
    var instance  = this;
    this.Pool.push(
        function(pool){
            // здесь подгружаем объект формы
            var fid = this.Url.split('/').join('_');
            window[fid].parser.call(instance,pool);
        }
    );
    return this;
};

// --- --- --- ---
// получить hid формы
Form2.prototype.getHid = function(){
    return this.Params.hid;
};