(function ( $ ) { 
    $('#contacts').dptWidget(
    {   
        source: $('#contacts_result'),
        hasChilds: false,
        item:{
            buttonNew: _contacts.translate.create,
            editForm: function(item, formConstructor){
                var select = formConstructor.createSelect(
                    _contacts.translate.labelForType,
                    'type',
                    [                   
                        {
                            label: _contacts.translate.typeVariants.phone,
                            value: "phone"
                        },
                        {
                            label: _contacts.translate.typeVariants.email,
                            value: "email"
                        }
                    ]
                );
                var input = formConstructor.createInput(
                    _contacts.translate.labelForValue,
                    'value'
                    );
                
                var bindData= function(){
                    item.setTitle(input.val());
                    item.setType(select.find('option:selected').attr('label'));
                }
                $.each([input, select ], function() {
                    this.one("bindData", function() {
                        bindData();
                    });
                });
                formConstructor.createButton( _contacts.translate.save, function()
                {
                    bindData();
                    item.update();
                    item.toggle();
                });

                formConstructor.createButton( _contacts.translate.delete, function()
                {   
                    item.delete();
                });
                
                formConstructor.render();
            },
        },
    });

    $('#working_hours').dptWidget(
    {
        source: $('#working_hours_result'),
        hasChilds: false,
        item:{
            buttonNew: _working_hours.translate.create,
            editForm: function(item, formConstructor){
                var day = formConstructor.createInput(
                    _working_hours.translate.labelForDay,
                    'day'
                );

                var time = formConstructor.createInput(
                    _working_hours.translate.labelForTime,
                    'time'
                );

                var bindData= function(){
                    item.setTitle(time.val());
                    item.setType(day.val());
                }

                $.each([day, time], function() {
                    this.one("bindData", function() {
                        bindData();
                    });
                });

                formConstructor.createButton(_working_hours.translate.save, function()
                {
                    bindData();
                    item.update();
                    item.toggle();
                });

                formConstructor.createButton(_working_hours.translate.delete, function()
                {   
                    item.delete();
                });
                
                formConstructor.render();
            },
        },
    });
    $('#services').dptWidget(
    {
        source: $('#services_result'),
        hasChilds: true,
        item:{
            buttonNew: _services.translate.category.create,
            editForm: function(item, formConstructor){
                var name = formConstructor.createInput(
                    _services.translate.category.labelForTitle,
                    'title'
                );
                
                var bindData= function(){
                    item.setTitle(name.val());
                    item.setType(
                        _services.translate.category.labelForType
                    );
                }
                var warning = function()
                {
                    console.log(item.childItemsBlock !== 'undefined');
                    if(item.childItemsBlock !== 'undefined' && item.childItemsBlock.find('.widget-sub-item').length)
                    {
                        return confirm(_services.translate.category.warning);     
                    }
                    return true;
                    
                }
                $.each([name], function() {
                    this.one("bindData", function() {
                        bindData();
                    });
                });

                formConstructor.createButton(_services.translate.category.save, function()
                {
                    bindData();
                    item.update();
                    item.toggle();
                });

                formConstructor.createButton(_services.translate.category.delete, function()
                {   
                    if(warning())
                    {
                        item.delete();    
                    }
                });
                
                formConstructor.render();
            },
        },
        childItem:{
            buttonNew: _services.translate.service.create,
            editForm: function(item, formConstructor){
                var name = formConstructor.createInput(
                    _services.translate.service.labelForTitle,
                    'title'
                );
                
                var bindData= function(){
                    item.setTitle(name.val());
                    item.setType(
                        _services.translate.service.labelForType
                    );
                }

                $.each([name], function() {
                    this.one("bindData", function() {
                        bindData();
                    });
                });

                formConstructor.createButton(_services.translate.service.save, function()
                {
                    bindData();
                    item.update();
                    item.toggle();
                });

                formConstructor.createButton(_services.translate.service.delete, function()
                {   
                    item.delete();
                });
                
                formConstructor.render();
            },
        }
    });
}( jQuery ));