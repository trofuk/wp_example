(function(w, $){
	var renderItem = function(item){
		var itemContacts = '';
		for(var i = 0; i < item.contacts.length; i++){
			if(item.contacts[i].type == 'phone'){
				itemContacts += '<a href="tel:'+item.contacts[i].value.replace(/\D/g, '')+'" class="departments-item-contact">' + item.contacts[i].value + '</a>'
			}else{
				itemContacts += '<div class="departments-item-contact">' + item.contacts[i].value + '</div>'
			}
		}

		var itemHours = '<table class="departments-item-hours">';
		for(var i = 0; i < item.working_hours.length; i++){
			itemHours += '<tr><td>' + item.working_hours[i].day + '</td><td>' + item.working_hours[i].time + '</td></tr>';
		}
		itemHours += '</table>';

		var itemServices = '';
		for(var i = 0; i < item.services.length; i++){
			itemServices += '<div class="departments-item-service-title">'+item.services[i].title+'</div>';
			for(var j = 0; j < item.services[i].items.length; j++){
				itemServices += '<div class="departments-item-service">'+item.services[i].items[j].title+'</div>';
			}
		}

		var template = [
			'<div class="departments-item">',
				'<div class="departments-item-image" style="background-image: url(\''+ item.featured_image +'\')"></div>',
				'<div class="departments-item-head">',
					'<div class="departments-item-title">' + item.title + '</div>',
					'<a class="departments-item-link" href="' + item.url + '"></a>',
					'<div class="departments-item-row">',
						'<div class="departments-item-col">' + itemContacts + '</div>',
						'<div class="departments-item-col">' + itemHours + '</div>',
					'</div>',
				'</div>',
				'<div class="departments-item-services">',
					itemServices,
				'</div>',
			'</div>'
		];

		$('.departments-list').scrollbar();
		$('.departments-list .departments-item-wrap').html(template.join(''));
	}

	var displayData = function(){
		var DMContext = this;
		$.post(this.config('endpoint'), {
			action: 'departments_list'
		})
		.done(function(response){
			DMContext.items = response;
			DMContext.markers = [];
			DMContext.activeIndex = 0;
			DMContext.markerIndex = 0;
			for(var i = 0; i < DMContext.items.length; i++){
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(DMContext.items[i].coordinates),
					map: DMContext.map,
					icon: DMContext.activeIndex == i?DMContext.config('marker_active'):DMContext.config('marker_default'),
					index: i
				});
				(function(marker){
					marker.addListener('click', function(e) {
						for(var j = 0; j < DMContext.markers.length; j++){
							DMContext.markers[j].setIcon(DMContext.config('marker_default'));
						}
						if(DMContext.items.length > marker.index){
							DMContext.activeIndex = marker.index;
							marker.setIcon(DMContext.config('marker_active'));
							renderItem(DMContext.items[marker.index]);
						}
						marker.setZIndex(google.maps.Marker.MAX_ZINDEX + DMContext.markerIndex++);
					});
				})(marker);
				marker.setZIndex(google.maps.Marker.MAX_ZINDEX + DMContext.markerIndex++);
				DMContext.markers.push(marker);
			}
			if(DMContext.items.length){
				renderItem(DMContext.items[0]);
			}
		});
	}

	var bootstrap = function(){
		if(typeof w.jQuery === 'undefined'){
			console.error('Departments map requires jQuery');
			return;
		}
		$('body').append('<script type="text/javascript" src="' + this.config('plugins_url') +'/jquery.scrollbar.min.js"></script>');
		$('body').append('<link rel="stylesheet" type="text/css" href="' + this.config('plugins_url') +'/jquery.scrollbar.css">');

		var template = [
			'<div id="dv_' + this.id + '" class="departments-viewport' + ($('#wpadminbar').length?' departments-with-adminbar':'') + '">',
				'<div class="departments-viewport-inner">',
					'<span class="departments-close">&times;</span>',
					'<div id="map_'+this.id+'" class="departments-map"></div>',
					'<div class="departments-list"><div class="departments-item-wrap"></div></div>',
				'</div>',
			'</div>'
		];

		$('body').append(template.join(''));

		w['dm_map_' + this.id] = (function(){
			this.map = new google.maps.Map(document.getElementById('map_'+this.id), {
				center: new google.maps.LatLng(this.config('map.center.lat'), this.config('map.center.lng')),
				mapTypeControl: false,
				streetViewControl: false,
				zoom: this.config('map.zoom'),
				zoomControl: true,
			});

			displayData.call(this);
		}).bind(this);
		
		if(typeof google == 'undefined'){
			$('body').append('<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false&key='+ this.config('map.apikey') +'&callback=dm_map_' + this.id +'"></script>');
		}else{
			setTimeout(w['dm_map_' + this.id], 200);
		}

		var DMContext = this;
		setTimeout(function(){
			$('#dv_' + DMContext.id + ' .departments-close').click(function(e){
				DMContext.hide();
			});
		}, 200);

		this.isBootstrapped = true;
	}

	var DepartmentsMap = function(containerSelector, options){
		this.config = function(key){
			var haystack = w.DMConfig;
			var segments = key.split('.');
			for(var i = 0; i < segments.length; i++){
				var segment = segments[i];
				if(typeof haystack[segment] !== 'undefined'){
					haystack = haystack[segment];
				}else{
					return null;
				}
			}
			return haystack;
		}
		this.id = Math.random().toString().substring(2);
		this.isBootstrapped = false;
		this.isVisible = false;
	}
	DepartmentsMap.prototype.show = function(){
		if(this.isVisible){
			return;
		}
		if(!this.isBootstrapped){
			bootstrap.call(this);
		}

		this.oldBodyOverflow = $('body').css('overflow');
		$('body').css('overflow', 'hidden');
		setTimeout(function(){
			$('.departments-viewport').css('transform', 'translateY(0)');
		}, 100);

		this.isVisible = true;
	}

	DepartmentsMap.prototype.hide = function(){
		if(!this.isVisible){
			return;
		}
		if(!this.isBootstrapped){
			return;
		}

		$('.departments-viewport').css('transform', 'translateY(-100vh)');
		$('body').css('overflow', this.oldBodyOverflow);
		
		this.isVisible = false;
	}

	DepartmentsMap.prototype.toggle = function(){
		if(this.isVisible){
			this.hide();
		}else{
			this.show();
		}
	}

	// Export
	w.DPMap = DepartmentsMap;

})(window, jQuery);