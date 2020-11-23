/**
 * Необходим jeasyu
 */
var Wi = function(){};

// --- --- --- --- --- --- ---
Wi.prototype.message = function(data){
    data = data || {};
    data.title = data.title || 'Внимание';
    data.message = data.message || 'Сообщение';

    $.messager.show({
        title : '',
        //msg: data.message,
        //msg: '<i class="fa fa-2x fa-check" style="margin-right: 10px;"></i><span style="position:relative;top:-5px;">'+data.message+'</span>',
        msg: '<table><tr><td><i class="fa fa-2x fa-check"></i></td><td style="padding-left:10px">'+data.message+'</td></tr></table>',
        showType : 'fade',
        height: 'auto',
        showSpeed: 200,
        timeout: 600,
        style:{
            right:'',
            bottom: ''
        }
    });//.css('background-color','#fbf7ff');

};

// --- --- --- --- --- --- ---
Wi.prototype.alert = function(data){
    data = data || {};
    data.title = data.title || 'Внимание';
    data.message = data.message || 'Сообщение';
    data.width = data.width || 'auto';
    data.minWidth = data.minWidth || 300;
    data.icon = data.icon || 'fa-info';
    data.labelOk = data.labelOk || 'Да';
    data.labelCancel = data.labelCancel || 'Нет';
    
    $.messager.alert({
        title : data.title,
        msg: '<table><tr><td><i class="fa fa-2x '+data.icon+'"></i></td><td style="padding-left:15px">'+data.message+'</td></tr></table>',
        //icon : 'info',
        minWidth : data.minWidth,
        width : data.width,
    })  //.css('background-color','#f3f3f3')
        .find('.messager-icon').remove().end()
        .parents('.panel').find('.dialog-button a').eq(0).addClass('l-btn-plain').find('.l-btn-text').text(data.labelOk)
        .parents('.panel').find('.dialog-button a').eq(1).addClass('l-btn-plain').find('.l-btn-text').text(data.labelCancel);
        
};

// --- --- --- --- --- --- ---
Wi.prototype.error = function(data){
    data = data || {};
    data.title = data.title || 'Внимание';
    data.message = data.message || 'Ошибка';
    data.width = data.width || 'auto';
    data.minWidth = data.minWidth || 300;
    
    $.messager.alert({
        title : data.title,
        msg: '<table><tr><td><i class="fa fa-2x '+data.icon+'"></i></td><td style="padding-left:15px">'+data.message+'</td></tr></table>',
        //icon : 'error'
        
        minWidth : data.minWidth,
        width : data.width,
    });
};

// --- --- --- --- --- --- ---
Wi.prototype.confirm = function(data){
    data = data || {};
    data.title = data.title || 'Внимание';
    data.message = data.message || 'Вы уверены?';
    data.onOk = data.onOk || function(){};
    data.icon = data.icon || 'fa-question';
    data.labelOk = data.labelOk || 'Да';
    data.labelCancel = data.labelCancel || 'Нет';
    data.deltaX = data.deltaX || undefined;
    data.deltaY = data.deltaY || undefined;
    data.width = data.width || 'auto';
    data.minWidth = data.minWidth || 300;
    
    var messanger = $.messager.confirm({
        title: data.title,
        //msg: data.message,
        msg: '<table><tr><td><i class="fa fa-2x '+data.icon+'"></i></td><td style="padding-left:15px;text-align:center;">'+data.message+'</td></tr></table>',
        //icon : 'error',
        minWidth : data.minWidth,
        width : data.width,
        fn: function(r){
            if(r) data.onOk();
        },
    })  //.css('background-color','#f3f3f3')
        .find('.messager-icon').remove().end()
        //.parents('.panel').find('.dialog-button a').eq(0).find('.l-btn-text').text(data.labelOk)
        //.parents('.panel').find('.dialog-button a').eq(1).find('.l-btn-text').text(data.labelCancel);
        .parents('.panel').find('.dialog-button a').eq(0).addClass('l-btn-plain').find('.l-btn-text').text(data.labelOk)
        .parents('.panel').find('.dialog-button a').eq(1).addClass('l-btn-plain').find('.l-btn-text').text(data.labelCancel);
        
        
    if(data.deltaY) messanger.parents('.panel').css({
        top : parseInt(messanger.parents('.panel').css('top')) + data.deltaY
    });
    
    if(data.deltaX) messanger.parents('.panel').css({
        left : parseInt(messanger.parents('.panel').css('left')) + data.deltaX
    });
};

// --- --- --- --- --- --- ---
Wi.prototype.prompt = function(data){
    data = data || {};
    data.value = data.value || '';
    data.title = data.title || 'Внимание';
    data.message = data.message || 'Введите';
    data.onOk = data.onOk || function(){};
    data.icon = data.icon || 'fa-pencil';
    data.width = data.width || 'auto';
    data.minWidth = data.minWidth || 300;
    
    $.messager.prompt({
        title: data.title,
        //msg: data.message,
        msg: '<table><tr><td><i class="fa fa-2x '+data.icon+'"></i></td><td style="padding-left:15px">'+data.message+'</td></tr></table>',
        minWidth : 300,
        width : data.width,
        fn: function(value){
            if(value) data.onOk(value);
        }
    })  //.css('background-color','#f3f3f3')
        .find('input.messager-input').val(data.value).end()
        .find('.messager-icon').remove().end()
        .parents('.panel').find('.dialog-button a').eq(0).addClass('l-btn-plain').find('.l-btn-text').text(data.labelOk)
        .parents('.panel').find('.dialog-button a').eq(1).addClass('l-btn-plain').find('.l-btn-text').text(data.labelCancel);
        
        //.find('.messager-icon').removeClass('messager-question').append('<i class="fa fa-2x fa-pencil"></i>');
};