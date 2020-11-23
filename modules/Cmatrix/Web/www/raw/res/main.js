$(document).ready(function(){
    ace.require("ace/ext/language_tools");
    ace.require("ace/ext/emmet");
    
    // здесь приведём в порядок layout
    /*
    var c = $('body');
    var p = c.layout('panel','north');
    var oldHeight = p.panel('panel').outerHeight();
    p.panel('resize', {height:'auto'});    
    var newHeight = p.panel('panel').outerHeight();
    c.layout('resize',{
        height: (c.height() + newHeight - oldHeight)
    });
    */
    
    $('#filesystem-tree').tree({
        onExpand : function(node){
            //console.log(node.target);
            $(node.target).children(".tree-icon:first").removeClass('fa-folder-o').addClass('fa-folder-open-o');
            //console.log(node.target);
        },
        onCollapse : function(node){
            //console.log(node.target);
            $(node.target).children(".tree-icon:first").removeClass('fa-folder-open-o').addClass('fa-folder-o');
            //console.log(node.target);
        },
    });
    
    // --- click на любом пункте контекстного меню
    $('#menu-folder0,#menu-folder1,#menu-file').menu({
        onClick : filesystem.menu.onClick,
        //duration :  12000
    });
    
    // --- для устранения скрола в панели north
    $('.top').removeAttr('style');
    
    new App();
});

// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
var App = function(){
    this.init();    
};

App.prototype.init = function(){
    window.onbeforeunload = function(e){
        if(!App.unloader.counter) return;
        
        e.returnValue = 'Данные могут быть не сохранены. Вы уверены?';
        return e.returnValue;
    };
};

// --- --- --- --- --- --- ---
App.unloader = {};
App.unloader.counter = 0;
App.unloader.inc = function(){
    App.unloader.counter++;
};
App.unloader.dec = function(){
    App.unloader.counter--;
};

// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
var Raw = function(){};
Raw.prototype.filesystem = function(){};

Raw.prototype.filesystem.rename = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');

    var tabs = $('#filesystem-tabs');
    var index = tabs.tabs('getIndexById',tree_node.id);
    
    if(index){
        new Wi().alert({ message : 'Файл  "' +tree_node.text+ '" открыт.<br/>Сначала закройте.' });
        return;
    }
    
    new Wi().prompt({
        value : tree_node.text,
        title: 'Переименование',
        message : 'Введите новое имя ' + (tree_node.type === 'folder' ? 'папки' : 'файла'),
        onOk : function(new_name){
            new Raw().filesystem.post(item.name,{
                oldid : tree_node.id,
                newname : new_name
            },function(data){
                tree.tree('update',{
                    target: tree_node.target,
                    id : data,
                    text: new_name,
                });
            });
        }
    });
};

Raw.prototype.filesystem.folder = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    
    new Wi().prompt({
        title: 'Создание папки',
        message : 'Введите имя новой папки',
        onOk : function(new_name){
            new Raw().filesystem.post(item.name,{
                oldid : tree_node.id,
                newname : new_name
            },function(data){
                tree.tree('reload',tree_node.target);
            });
        }
    });
};

Raw.prototype.filesystem.file = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    
    new Wi().prompt({
        title: 'Создание файла',
        message : 'Введите имя нового файла',
        onOk : function(new_name){
            new Raw().filesystem.post(item.name,{
                oldid : tree_node.id,
                newname : new_name
            },function(data){
                tree.tree('reload',tree_node.target);
            });
        }
    });
};

Raw.prototype.filesystem.delete = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    var parent_node = tree.tree('getParent',tree_node.target);
    
    var tabs = $('#filesystem-tabs');
    var tab_index = tabs.tabs('getIndexById',tree_node.id);
    var tab = tabs.tabs('getTab',parseInt(tab_index));
    
    // --- --- --- --- ---
    var fun_del = function(callback){
        new Raw().filesystem.post(item.name,{
            oldid : tree_node.id,
        },function(data){
            if(typeof callback === 'function') callback();
            tree.tree('reload',parent_node.target);
        });
    };
    
    // --- --- --- --- ---
    var fun_folder = function(){
        
        // --- --- --- --- --- --- --- --- ---
        var fun_tabsClose = function(arr_id){
            $(arr_id).each(function(key,value){
                var index = tabs.tabs('getIndexById',value);
                tabs.tabs('close',parseInt(index));
            });
        };
        
        // --- --- --- --- --- --- --- --- ---
        var fun_folderFileChanged = function(arr_id,arr_changed){
            new Wi().confirm({
                title : 'Внимание',
                message : 'Есть несохранённые изменения.<br/>Все равно удалить?',
                onOk : function(){
                    $(arr_changed).each(function(key,value){
                        var index = tabs.tabs('getIndexById',value);
                        var tab = tabs.tabs('getTab',parseInt(index));
                        var editor = tab.data('editor');
                        $(editor.Editor).removeData('changed');
                    });
                    fun_del(function(){
                        fun_tabsClose(arr_id);
                    });                    
                }
            });
        };
        
        // --- --- --- --- --- --- --- --- ---
        var fun_folderFilesOpend = function(arr_id){
            new Wi().confirm({
                title: 'Внимание',
                message : 'Есть открытые файлы.<br/>Все равно удалить?',
                deltaX : 25,
                deltaY : 25,
                onOk : function(){
                    var arr_changed = [];
                    $(arr_id).each(function(key,value){
                        var index = tabs.tabs('getIndexById',value);
                        var tab = tabs.tabs('getTab',parseInt(index));
                        var editor = tab.data('editor');
                        if(editor){
                            if($(editor.Editor).data('changed')) arr_changed.push(value);
                        }
                    });
                    
                    if(arr_changed.length) fun_folderFileChanged(arr_id,arr_changed);
                    else fun_del(function(){
                        fun_tabsClose(arr_id);
                    });
                }
            });
        };
        
        // --- --- --- --- --- --- --- --- ---
        var fun_folderNotEmpty = function(arr_id){
            new Wi().confirm({
                title: 'Внимание',
                message : 'Папка не пустая.<br/>Все равно удалить?',
                onOk : function(){
                    var arr = tabs.tabs('checkTabsById',arr_id);
                    if(arr.length) fun_folderFilesOpend(arr);
                    else fun_del(); 
                }
            });
        };
        
        // --- --- --- --- --- --- --- --- ---
        // --- --- --- --- --- --- --- --- ---
        // --- --- --- --- --- --- --- --- ---
        new Raw().filesystem.get('getFiles',{
            oldid : tree_node.id,
        },function(data){
            // если есть подпапки и файлы в них
            if(data.length > 1) fun_folderNotEmpty(data);
            else{
                new Wi().confirm({
                    title: 'Удаление',
                    message : 'Вы уверены, что нужно удалить папку "' +tree_node.text+ '" ?',
                    onOk : fun_del,
                });
            }
        });
    };
    
    // --- --- --- --- ---
    var fun_file = function(){
        
        // --- --- --- --- --- --- --- --- ---
        var fun_fun = function(){
            tabs.tabs('close',parseInt(tab_index));
        };
        
        // --- --- --- --- --- --- --- --- ---
        var fun_fileChanged = function(){
            new Wi().confirm({
                title : 'Внимание',
                message : 'Есть несохранённые изменения файла "' +tree_node.text+ '".<br/>Все равно удалить?',
                deltaX : 25,
                deltaY : 25,
                width : 'auto',
                onOk : function(){
                    var editor = tab.data('editor');
                    $(editor.Editor).removeData('changed');
                    fun_del(fun_fun);
                }
            });
        }        
        
        // --- --- --- --- --- --- --- --- ---
        var fun_fileOpend = function(){
            new Wi().confirm({
                title: 'Удаление',
                message : 'Файл "' +tree_node.text+ ' открыт".<br/>Все равно удалить?',
                onOk : function(){
                    var editor = tab.data('editor');
                    if(editor){
                        var changed = $(editor.Editor).data('changed');
                        if(changed) fun_fileChanged();
                        else fun_del(fun_fun);
                    }
                    else fun_del(fun_fun);
                }
            });
        };
        
        if(tab_index) fun_fileOpend();
        else{
            new Wi().confirm({
                title: 'Удаление',
                message : 'Вы уверены, что нужно удалить файл "' +tree_node.text+ '" ?',
                onOk : fun_del,
            });
        }
    };

    // --- --- --- --- ---
    // --- --- --- --- ---
    // --- --- --- --- ---
    if(tree_node.type === 'file') fun_file();
    else fun_folder();

    /*
    console.log(tree_node.id);
    console.log(tabs.tabs('getIndexById',tree_node.id));
    
    new Wi().confirm({
        title: 'Удаление',
        message : 'Вы уверены, что нужно удалить "' +tree_node.text+ '"',
        onOk : function(){
            new Raw().filesystem.post(item.name,{
                oldid : tree_node.id,
                oldid : tree_node.id,
            },function(data){
                tree.tree('reload',parent_node.target);
            });
        }
    });
    */
};

Raw.prototype.filesystem.reload = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    tree.tree('reload',tree_node.target);
};

Raw.prototype.filesystem.upload = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    
    //console.log(tree_node);
    
    jQuery($('.raw-upload').outerHtml()).removeClass('hidden').attr('id','raw-upload').appendTo($('body'))
        .find('.raw-filebox').attr('id','raw-filebox').filebox({
            multiple : true,
            required : true,
            icons:[{
                iconCls : 'icon-clear',
                handler : function(e){
                    $(e.data.target).filebox('clear');
                }
            }],
            onChange : function(newValue,oldValue){
                if(newValue) $('#raw-upload .raw-linkbutton').linkbutton('enable');
                else $('#raw-upload .raw-linkbutton').linkbutton('disable');
            }
        })
        .end()
        .find('.raw-linkbutton').linkbutton({
            onClick  :function(){
                var form = $('#raw-upload .raw-form');
                form.form({
                    queryParams : {
                        folder : tree_node.id
                    },
                    onProgress : function(percent){
                        //console.log(percent);
                        progressbar.progressbar({ value: percent });
                    },
                    success : function(data){
                        //data = JSON.stringify(data);
                        data = JSON.parse(data);
                        
                        
                        if(data.status !== -1){
                            tree.tree('reload',tree_node.target);
                            $('#raw-upload .raw-linkbutton').linkbutton('disable');
                            $('#raw-upload .raw-filebox').filebox('clear');
                            $('#raw-upload').dialog('close');
                            //$.messager.alert('Внимание', data.message);
                            new Wi().message({
                                message : data.message
                            });
                        }
                        else $.messager.alert('Внимание', data.message);
                    },
                    onLoadError: function(){
                        alert('error');
                    }
                });
                
                //if(!$('#raw-filebox').filebox('isValid')) return;
                if(!form.form('validate')) return;
                
                //var file = $('#raw-filebox').parent().find('.textbox-value');
                //console.log(file[0].files.length);
                //console.log(file[0].files[0]);
                
                var progressbar = $('#raw-upload .raw-progressbar');
                progressbar.progressbar();
                
                form.form('submit');
            }
        })
        //.end().find('._raw-progressbar').progressbar()
        .end().dialog();
    
    //$('#upload').dialog('open');
};

Raw.prototype.filesystem.copy = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    
    //$(this).menu('disableItem',$('#menu-folder0 .raw-copy')[0]);
    //$(this).menu('disableItem',$('#menu-folder0 .raw-move')[0]);
    $(this).menu('enableItem',$('#menu-folder0 .raw-paste')[0]);
    $(this).menu('enableItem',$('#menu-folder0 .raw-empty')[0]);

    $(this).menu('disableItem',$('#menu-folder1 .raw-copy')[0]);
    $(this).menu('disableItem',$('#menu-folder1 .raw-move')[0]);
    $(this).menu('enableItem',$('#menu-folder1 .raw-paste')[0]);
    $(this).menu('enableItem',$('#menu-folder1 .raw-empty')[0]);
    
    $(this).menu('disableItem',$('#menu-file .raw-copy')[0]);
    $(this).menu('disableItem',$('#menu-file .raw-cut')[0]);
    //$(this).menu('enableItem',$('#menu-file .raw-paste')[0]);
    $(this).menu('enableItem',$('#menu-file .raw-empty')[0]);
    
    //console.log(item);
    $('body').data('buff',{
        mode : item.name,
        oldid : tree_node.id
    });
    
    //$(this).menu('hideItem',item.target);
    //console.log(this,item);
};

Raw.prototype.filesystem.move = function(item){
    var tabs = $('#filesystem-tabs');
    
    var fun_fun = function(){
        $('body').data('buff',{
            mode : item.name,
            oldid : tree_node.id
        });
    };

    // --- --- --- --- --- --- ---    
    var fun_tabsClose = function(arr_id){
        $(arr_id).each(function(key,value){
            var index = tabs.tabs('getIndexById',value);
            tabs.tabs('close',parseInt(index));
        });
    };

    // --- --- --- --- --- --- ---    
    var fun_folderFilesOpend = function(arr_id){
        new Wi().confirm({
            title: 'Внимание',
            message : 'Есть открытые файлы.<br/>Все равно вырезать?',
            onOk : function(){
                fun_tabsClose(arr_id);
                fun_fun(); 
            }
        });
    };
    
    // --- --- --- --- --- --- ---    
    var fun_folderNotEmpty = function(arr_id){
        var arr = tabs.tabs('checkTabsById',arr_id);
        if(arr.length) fun_folderFilesOpend(arr);
        else fun_fun(); 
    }
    
    // --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- ---    
    // --- --- --- --- --- --- ---    
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    
    //$(this).menu('disableItem',$('#menu-folder0 .raw-copy')[0]);
    //$(this).menu('disableItem',$('#menu-folder0 .raw-move')[0]);
    $(this).menu('enableItem',$('#menu-folder0 .raw-paste')[0]);
    $(this).menu('enableItem',$('#menu-folder0 .raw-empty')[0]);

    $(this).menu('disableItem',$('#menu-folder1 .raw-copy')[0]);
    $(this).menu('disableItem',$('#menu-folder1 .raw-move')[0]);
    $(this).menu('enableItem',$('#menu-folder1 .raw-paste')[0]);
    $(this).menu('enableItem',$('#menu-folder1 .raw-empty')[0]);
    
    $(this).menu('disableItem',$('#menu-file .raw-copy')[0]);
    $(this).menu('disableItem',$('#menu-file .raw-move')[0]);
    //$(this).menu('enableItem',$('#menu-file .raw-paste')[0]);
    $(this).menu('enableItem',$('#menu-file .raw-empty')[0]);

    new Raw().filesystem.get('getFiles',{
        oldid : tree_node.id,
    },function(data){
        // если есть подпапки и файлы в них
        if(data.length > 1) fun_folderNotEmpty(data);
        else fun_fun();
    });
};

Raw.prototype.filesystem.paste = function(item){
    var tree = $('#filesystem-tree');
    var tree_node = tree.data('tree_node');
    
    //$(this).menu('enableItem',$('#menu-folder0 .raw-copy')[0]);
    //$(this).menu('enableItem',$('#menu-folder0 .raw-move')[0]);
    $(this).menu('disableItem',$('#menu-folder0 .raw-paste')[0]);
    $(this).menu('disableItem',$('#menu-folder0 .raw-empty')[0]);

    $(this).menu('enableItem',$('#menu-folder1 .raw-copy')[0]);
    $(this).menu('enableItem',$('#menu-folder1 .raw-move')[0]);
    $(this).menu('disableItem',$('#menu-folder1 .raw-paste')[0]);
    $(this).menu('disableItem',$('#menu-folder1 .raw-empty')[0]);

    $(this).menu('enableItem',$('#menu-file .raw-copy')[0]);
    $(this).menu('enableItem',$('#menu-file .raw-move')[0]);
    //$(this).menu('disableItem',$('#menu-file .raw-paste')[0]);
    $(this).menu('disableItem',$('#menu-file .raw-empty')[0]);
    
    var buff = $('body').data('buff');
    $('body').removeData('buff');
    
    //console.log(buff);
    
    new Raw().filesystem.post(buff.mode,{
        oldid : buff.oldid,
        newid : tree_node.id
    },function(data){
        tree.tree('reload',tree_node.target);
        if(buff.mode === 'move'){
            var node = tree.tree('find',buff.oldid);
            tree.tree('remove',node.target);
        }
    });
};

Raw.prototype.filesystem.empty = function(item){
    //$(this).menu('enableItem',$('#menu-folder0 .raw-copy')[0]);
    //$(this).menu('enableItem',$('#menu-folder0 .raw-move')[0]);
    $(this).menu('disableItem',$('#menu-folder0 .raw-paste')[0]);
    $(this).menu('disableItem',$('#menu-folder0 .raw-empty')[0]);

    $(this).menu('enableItem',$('#menu-folder1 .raw-copy')[0]);
    $(this).menu('enableItem',$('#menu-folder1 .raw-move')[0]);
    $(this).menu('disableItem',$('#menu-folder1 .raw-paste')[0]);
    $(this).menu('disableItem',$('#menu-folder1 .raw-empty')[0]);
    
    $(this).menu('enableItem',$('#menu-file .raw-copy')[0]);
    $(this).menu('enableItem',$('#menu-file .raw-move')[0]);
    //$(this).menu('disableItem',$('#menu-file .raw-paste')[0]);
    $(this).menu('disableItem',$('#menu-file .raw-empty')[0]);
    
    $('body').removeData('buff');
};

Raw.prototype.filesystem.get = function(mode,params,callback){
    $.ajax({
        url : 'ajax/filesystem/post.php',
        method : 'POST',
        dataType : 'JSON',
        data : {
            mode : mode,
            params : params,
        },
    })
    .done(function(data, textStatus, jqXH){
        if(typeof callback === 'function') callback(data.data);        
    })
    .fail(function(jqXHR, textStatus, errorThrown){
    });
};

Raw.prototype.filesystem.post = function(mode,params,callback){
    $.ajax({
        url : 'ajax/filesystem/post.php',
        method : 'POST',
        dataType : 'JSON',
        data : {
            mode : mode,
            params : params,
        },
    })
    .done(function(data, textStatus, jqXH){
        if(data.status == -1){
            new Wi().error({
                title : 'Внимание',
                message : data.message,
            });
        }
        else{
            new Wi().message({
                message : data.message
            });
            if(typeof callback === 'function') callback(data.data);
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown){
        new Wi().error({
            title : 'Внимание',
            message : textStatus,
        });
    });
};

// --- --- --- --- --- --- ---
Ace = function(params){
    params = params || {};
    this.FileId = params.file_id || null;
    this.ContainerId = params.container_id || null;
    this.Getter = params.getter || null;
    this.Setter = params.setter || null;
    this.onChange = params.onChange || function(){};
    this.onSave = params.onSave || function(){};
    
    //if(!this.FileId) console.log('Not defined FileId');
    //if(!this.ContainerId) console.log('Not defined ContainerId');
    //if(!this.Getter) console.log('Not defined Getter');
    //if(!this.Setter) console.log('Not defined Setter');
    
    //this.ContainerId = n_id;
    this.Editor;
    this.Type = 'plain_text';
    
    this.get();
};

Ace.prototype.get = function(){
    var instance = this;
    if(!instance.Getter) return;

    $.ajax({
        url : instance.Getter,
        method : 'POST',
        dataType : 'JSON',
        data : {
            id : instance.FileId
        },
    })
    .done(function(data){
        $('#'+instance.ContainerId).append(data.content);
        instance.init(data.type);
    })
    .fail(function(){
        
    });
};

Ace.prototype.init = function(type){
    var instance = this;
    this.Type = type || this.Type;
    
    instance.Editor = ace.edit(instance.ContainerId);
    instance.Editor.getSession().setMode('ace/mode/'+this.Type);
    
    instance.Editor.setTheme("ace/theme/textmate");		// день
    //instance.Editor.setTheme("ace/theme/chaos");		// ночь
    //instance.Editor.setTheme("ace/theme/eclipse");
    //instance.Editor.setTheme("ace/theme/xcode");
    
    instance.Editor.setOptions({
        enableBasicAutocompletion : true,       // автодополнение
        enableSnippets : true,                  // сниппеты
        scrollPastEnd : true,                   // прокрутка далее конца файла
        enableEmmet : true                      // zen coding
    });
    
    instance.Editor.on("change", function(e){
        if($(instance.Editor).data('changed')) return;
        instance.onChange.call(this);
        //filesystem.tabs.setMarker.call(this);
        $(instance.Editor).data('changed',true);
    });
            
    instance.Editor.commands.addCommand({
        name : "UnDo",
        bindKey : { win : "Ctrl-Z", mac : "Command-Z" },
        exec : function(editor){
            editor.undo();
        }
    });
            
    instance.Editor.commands.addCommand({
        name : "ReDo",
        bindKey : { win : "Ctrl-Y", mac : "Command-C" },
        exec : function(editor){
            editor.redo();
        }
    });
        
    instance.Editor.commands.addCommand({
        name : "Save Ctrl+S",
        bindKey : { win : "Ctrl-S", mac : "Command-S" },
        exec : function(editor){
            instance.save();
            instance.onSave.call(this);
            $(instance.Editor).removeData('changed');
        }
    });
    
    instance.Editor.focus();
};

Ace.prototype.save = function(){
    var instance = this;
    if(!instance.Setter) return;

    var content = instance.Editor.getValue();
    var file = instance.FileId;
            
    $.ajax({
        url : instance.Setter,
        method : 'POST',
        dataType : 'JSON',
        data : {
            file : file,
            content : content
        },
    })
    .done(function(data, textStatus, jqXH){
        //console.log(data);
        if(data.status == -1){
            new Wi().error({
                title : 'Внимание',
                message : data.message,
            });
        }
        else{
            new Wi().message({
                message : data.message
            });
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown){
        new Wi().error({
            title : 'Внимание',
            message : textStatus,
        });
    });
};

Ace.prototype.focus = function(){
    var instance = this;
    if(instance.Editor) instance.Editor.focus();
};

Ace.prototype.resize = function(){
    var instance = this;
    if(instance.Editor) instance.Editor.resize(true);
};

// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
// --- --- --- --- --- --- ---
var filesystem = {
    
    tree : {
        onClick : function(node){
            if(node.type === 'file'){
                $('#filesystem-tabs').tabs('touch',{
                    id : node.id,
                    title : '<span class="lmarker"></span>'+node.text+'<span class="rmarker"></span>',
                    closable : true,
                });
            }
        },
        
        onContextMenu : function(e,node){
            $('#filesystem-tree').data('tree_node',node);   // запоминить node
            
            e.preventDefault();
            var pos = { left: e.pageX + 10, top: e.pageY + 10 };
            
            if(node.type === 'root'){
                $('#menu-folder0').menu('show',pos);
            }
            else if(node.type === 'folder'){
                $('#menu-folder1').menu('show',pos);
            }
            else if(node.type === 'file'){
                $('#menu-file').menu('show',pos);
            }
        },
    },
    
    tabs : {
        onAdd : function(title,index){
            var instance = this;
            
            var id = $(instance).tabs('getIdByIndex',index);
            var node = $('#filesystem-tree').tree('find',id);
            var tab = $(instance).tabs('getTab',index);
            
            
            if(node.type === 'file'){
                var ext = node.text.split(".").pop();
                var ext = node.text===ext ? '' : ext;
                
                switch(ext){
                    case 'png' :
                    case 'jpg' :
                    case 'jpeg' : 
                    case 'gif' :
                        tab.append('<img src="' + $('meta[name=whome]').attr('content') +'/'+ node.id +'"/ >');
                        //console.log($('meta[name=whome]').attr('content'));
                        break;
                        
                    default :
                    console.log(id);
                    console.log(id.replace(/\.|\/|^|\s|=/g,'_'));
                        var n_id = 'ace-' + id.replace(/\.|\/|\^|\s|=/g,'_');
                        tab.append('<div id="' +n_id+ '"></div>');
                        
                        $('#'+n_id).panel({
                            fit : true,
                            title : id,
                            onResize : function(width, height){
                                var editor = tab.data('editor');
                                if(editor) tab.data('editor').resize(true);
                            }
                        });
                        
                        App.unloader.inc();
                        var editor = new Ace({
                            file_id : id,
                            container_id : n_id,
                            getter : 'ajax/filesystem/getter.php',
                            setter : 'ajax/filesystem/setter.php',
                            onChange : filesystem.tabs.setMarker,
                            onSave  :filesystem.tabs.clearMarker,
                        });
                        
                        tab.data('editor',editor);      // запомнить edit
                }
            }
            
            // запомнить event по клику на кнопку закрытия, для того, чтоб отловить доп клавиши: ctrl alt shift
            $('.tabs .tabs-header').eq(0).find('ul li').eq(index).find('.tabs-close').on('click',function(e){
                tab.data('e',e);
            });
        },
        
        onBeforeClose : function(title,index){
            var instance = this;
            
            var tab = $(this).tabs('getTab',index);
            var editor = tab.data('editor');
            if(!editor) return true;
            
            var fun_close = function(){
                var opts = $(instance).tabs('options');
				var bc = opts.onBeforeClose;
				opts.onBeforeClose = function(){};
                $(instance).tabs('close',index);
                opts.onBeforeClose = bc;
            };
            
            var e = tab.data('e') || {}; // если событие вызываетсф не крестом, то e - отсутствует
            var changed = $(editor.Editor).data('changed')
            
            if(changed && !e.ctrlKey){
                new Wi().confirm({
                    message : 'Есть несохранённые изменения.<br/>Всё равно закрыть?',
                    onOk : fun_close
                });
            }
            else fun_close();
            
            return false;
        },
        
        onClose : function(title,index){
            App.unloader.dec();
        },
        
        onSelect : function(title,index){
            var id = $(this).tabs('getIdByIndex',index);
            var node = $('#filesystem-tree').tree('touch',id);
            
            //console.log(id);
            var tab = $(this).tabs('getTab',index);
            var editor = tab.data('editor');
            if(editor) editor.focus();
        },
        
        setMarker : function(){
            var tabs = $('#filesystem-tabs');
            var tab = tabs.tabs('getSelected');
            var index = tabs.tabs('getTabIndex',tab);
            
			tabs.find('.tabs-header ul li').eq(index)
				.find('.lmarker').addClass('active').text('►').end()
				.find('.rmarker').addClass('active').text('◄');
        },
        
        clearMarker : function(){
            var tabs = $('#filesystem-tabs');
            var tab = tabs.tabs('getSelected');
            var index = tabs.tabs('getTabIndex',tab);
            
			tabs.find('.tabs-header ul li').eq(index)
				.find('.lmarker').removeClass('active').text('').end()
				.find('.rmarker').removeClass('active').text('');
        },
        
    },
    
    menu : {
        onClick : function(item){
            if(item.name === 'reload') new Raw().filesystem.reload(item);
            else if(item.name === 'rename') new Raw().filesystem.rename(item);
            else if(item.name === 'file') new Raw().filesystem.file(item);
            else if(item.name === 'folder') new Raw().filesystem.folder(item);
            else if(item.name === 'delete') new Raw().filesystem.delete(item);
            else if(item.name === 'upload') new Raw().filesystem.upload(item);
            
            else if(item.name === 'copy') new Raw().filesystem.copy.call(this,item);
            else if(item.name === 'move') new Raw().filesystem.move.call(this,item);
            else if(item.name === 'paste') new Raw().filesystem.paste.call(this,item);
            else if(item.name === 'empty') new Raw().filesystem.empty.call(this,item);
        },
        
    },
    
    dialog : {
        onBeforeOpen : function(){
            //return false;
        },
        onClose : function(){
            $(this).dialog('destroy');
        }, 
    },
    
};

