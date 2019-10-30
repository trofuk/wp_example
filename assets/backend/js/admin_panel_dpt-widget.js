(function ( $ ) { 
    $.fn.dptWidget = function( options ) {
        // Plugin Settings default
        var settings = $.extend({
            source: $('#result'),
            hasChilds: false,
            item:{
                buttonNew: 'Create Element',
                editForm: function(){},
            },
            childItem:{
                buttonNew: 'Create Sub Element',
                editForm: function(){},
            }
        }, options );
 
        var getConfig = function(config)
        {
            var config = config.split('.');
            var handshake = settings;
            for(var i in config)
            {
                if(typeof handshake[config[i]] !== 'undefined')
                {
                    handshake = handshake[config[i]]; 
                }
                else
                {
                    console.error(
                        'Config "'+ config.join('.') +'" not found.' +
                        ' Please specify it in widget configuration'
                    );
                    return null;
                }
            }
            return handshake;
        }
        var appendEl = function(block, el, params)
        {
            var params = params ? params : {};
            var el = $(el, params);
            block.append(el);
            return el;
        }
        var formConstructor = function(item){
            return  {

                item: item,
                form: $(document.createElement('div')).addClass('form'),
                controls : $(document.createElement('div')).addClass('form-group controls'),

                createInput: function(label, name)
                {
                    var formGroup = $(document.createElement('div')).addClass('form-group');
                    var label = $(document.createElement('label')).text(label);
                    
                    var input = $(document.createElement('input'))
                    .attr({type: "text", name: name});

                    formGroup.append(label,input);
                    this.form.append(formGroup);
                    return input;
                },
                createSelect: function(label, name, options)
                {
                    var formGroup = $(document.createElement('div')).addClass('form-group');
                    var label = $(document.createElement('label')).text(label);
                    
                    var select = $(document.createElement('select'))
                    .attr({name: name});

                    if(options.length)
                    {
                        for(var i in options)
                        {
                            if(
                                typeof options[i] === 'object' &&
                                typeof options[i].label === 'string' && 
                                typeof options[i].value ==='string'
                            )
                            {
                                var option = $(document.createElement('option'))
                                .attr({label: options[i].label})
                                .text(options[i].value);

                                select.append(option)
                            }
                            if(typeof options[i] === 'string' || typeof options[i] === 'number')
                            {
                                var option = $(document.createElement('option'))
                                .text(options[i]);

                                select.append(option);
                            }
                        }
                    }
                    formGroup.append(label,select);
                    this.form.append(formGroup);
                    return select;
                },
                createButton:function(text,callback)
                {
                    var button = $(document.createElement('span'))
                    .addClass('button button-primary')
                    .text(text)
                    .click(callback);

                    this.controls.append(button);
                    return button;
                },
                render: function(){
                    this.form.append(this.controls);
                    this.item.editBlock.append(this.form);
                },
            }
        }
        var formArrayToObj = function(array)
        {
            var obj = {};
            for(var i in array)
            {
                obj[array[i].name] = array[i].value;
            }
            return obj;
        }
        var itemBindData = function(item,data)
        {
            for(var i in data)
            {
                item.find('[name="'+i+'"]').each(function(){
                    $(this).val(data[i]);
                    $(this).trigger('bindData');
                });
            }
        }
        var createItem = function(block, widget)
        {
            var item = renderItem(widget).addClass('widget-item empty');
            var editAction = getConfig('item.editForm');
            editAction(item, formConstructor(item));
            if(getConfig('hasChilds'))
            {
                item.controlBlock = appendEl(
                    item,
                    '<div></div>',
                    {"class":"widget-control"}
                );
                appendEl(
                    item.controlBlock,
                    '<span></span>', {
                    "class":"button button-primary",
                    "text": getConfig("childItem.buttonNew"),
                    "click": function(){
                        createChildItem(item.childItemsBlock, item.parentWidget);
                    }
                });
                item.childItemsBlock = appendEl(
                    item,
                    '<div></div>',
                    {"class":"sub-items-block"}
                );
                item.childItemsBlock.sortable({
                    items: '.widget-sub-item',
                    handle: '> .widget-header',
                    tolerance: "pointer",
                    cursor:'move',
                    revert:'true',
                    dropOnEmpty: true,
                    forcePlaceholderSize: true,
                    connectWith: '.widget-item .sub-items-block',
                    update:function(e, ui){
                        item.parentWidget.serialize(item.parentWidget.saveSerialized);
                    },
                    start: function(e, ui){
                        ui.placeholder.height(ui.item.height());
                    },
                    placeholder:'widget-item placeholder',
                });
            }

            block.append(item);
            return item;
        }
        var createChildItem = function(block, widget)
        {
            var child = renderItem(widget).addClass('widget-sub-item empty');
            var editAction = getConfig('childItem.editForm');
            editAction(child, formConstructor(child));
            block.append(child);
            return child;
        }
        var renderItem = function(widget)
        {
            var item = $(document.createElement('div'));
            item.parentWidget = widget;
            item.header = appendEl(
                item,
                '<div></div>',
                {"class": "widget-header"}
            );
            item.headerTitle = appendEl(
                item.header,
                '<span></span>',
                {"class": "header-title"}
            );
            item.headerControls = appendEl(
                item.header,
                '<div></div>',
                {"class": "header-controls"}
            );
            item.headerType = appendEl(
                item.headerControls,
                '<span></span>',
                {"class": "control-type"}
            );
            item.headerToggle = appendEl(
                item.headerControls,
                '<span></span>' ,
                {"class": "control-edit dashicons dashicons-arrow-down-alt2"}
            );
            item.update = function()
            {
                this.removeClass('empty');
                this.parentWidget.serialize(this.parentWidget.saveSerialized);
            }
            item.setTitle = function(value)
            {
                this.headerTitle.text(value);
            }
            item.toggle = function()
            {
                this.editBlock.toggleClass('opened');
            };
            item.setType = function(value)
            {
                this.headerType.text(value);
            }
            item.delete = function()
            {
                this.remove();
                this.parentWidget.serialize(this.parentWidget.saveSerialized);
            }
            item.headerToggle.click(
                function(){
                    item.toggle.call(item);
                }
            );
            item.editBlock = appendEl(
                item,
                '<div></div>',
                {"class":"widget-edit-block"}
            );
            return item;
        };
        
        // Plugin init
        this.each(function() 
        {   
            var widget = $(this).addClass('dpt-widget');
            widget.controlBlock = appendEl(widget, '<div></div>', {"class":"widget-control"} );
            
            appendEl(
                widget.controlBlock,
                '<span></span>',
                {
                    "class":"button button-primary",
                    "text": getConfig("item.buttonNew"),
                    "click": function(){
                        createItem(widget.itemsBlock, widget);
                    }
                }
            );

            widget.itemsBlock = appendEl(
                widget,
                '<div></div>',
                {"class":"widget-items-block"}
            );

            (function(){
                var source = getConfig('source').val();
                if(source.length > 1)
                {
                    var data=JSON.parse(source);
                }
                if(typeof data !== 'undefined' && data.length)
                {                    
                    for(var i in data)
                    {
                        var itemEl = createItem(widget.itemsBlock, widget).removeClass('empty');
                        itemData = data[i];
                        itemBindData(itemEl, itemData);

                        if(getConfig('hasChilds') && data[i].items.length)
                        {
                            var childs = data[i].items; 
                            for(var q in childs)
                            {   
                                var childEl = createChildItem(itemEl.childItemsBlock, itemEl.parentWidget).removeClass('empty');
                                var childData = childs[q];
                                itemBindData(childEl, childData);
                            }
                        }
                    }
                }
            })();

            $(widget).find('.widget-items-block').sortable({
                items: '.widget-item',
                handle: '> .widget-header',
                tolerance: "pointer",
                cursor:'move',
                revert:'true',
                forcePlaceholderSize: true,
                dropOnEmpty: true,
                update:function(e, ui){
                    widget.serialize(widget.saveSerialized);
                },
                start: function(e, ui){
                    ui.item.children('.sub-items-block, .widget-control').css({display: "none"});
                    ui.placeholder.height(
                        ui.item.children('.widget-header').outerHeight()
                    );
                },
                stop: function(e, ui){
                    ui.item.children('.sub-items-block, .widget-control').css({display: "block"});
                },
                placeholder:'widget-item placeholder',
            });

            widget.serialize = function(callback)
            {
                var result = [];
                widget.find('.widget-item:not(.empty)').each(function(){
                    var itemData = $(this).children('.widget-edit-block').find(':input').serializeArray();
                    var item = formArrayToObj(itemData);
                    if(getConfig('hasChilds'))
                    {
                        item.items = [];
                        $(this).find('.widget-sub-item:not(.empty)').each(function(){
                            var childData = $(this).children('.widget-edit-block').find(':input').serializeArray();
                            var childItem = formArrayToObj(childData);
                            item.items.push(childItem);
                        });

                    }
                    result.push(item);
                });
                callback(result);
            }
            widget.saveSerialized = function(result)
            {
                getConfig('source').val(JSON.stringify(result));
            }
        });
    };
}( jQuery ));