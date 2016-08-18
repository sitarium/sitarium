+function ($) {
	'use strict';
	
	//
	function ID_Generator() {};
	ID_Generator.prototype.rand =  Math.floor(Math.random() * 26) + Date.now();
	ID_Generator.prototype.getId = function() {
		return this.rand++;
	};
	var idGenerator = new ID_Generator();
	//
	function elementOrParentIsFixed(element) {
	    var $element = $(element);
	    var $checkElements = $element.add($element.parents());
	    var isFixed = false;
	    $checkElements.each(function(){
	        if ($(this).css("position") === "fixed") {
	            isFixed = true;
	            return false;
	        }
	    });
	    return isFixed;
	}
	//
	$.fn.containsRange = function (range, allowPartiallySelected) {
	    var foundContainingNode = false;
	    this.each(function() {
	        if (range.containsNode(this, allowPartiallySelected)) {
	            foundContainingNode = true;
	            return false;
	        }
	    });
	    return foundContainingNode;
	}
	//
	
	var FlyEditor = function (options)
	{
		var myFlyEditor = this;
		
		myFlyEditor.options = $.extend(true, {}, $.flyeditor.defaults, options);
		if (typeof(myFlyEditor.options.root) == 'string') {
			myFlyEditor.root = $(this.options.root);
		}
		if (myFlyEditor.options.repeatables == null){
			myFlyEditor.options.repeatables = new $.fn.init;
		}
		if (myFlyEditor.options.editables == null){
			myFlyEditor.options.editables = new $.fn.init;
		}
		
		// Text selection module init
		rangy.init();
		if (myFlyEditor.options.highlightApplier == null){
			myFlyEditor.options.highlightApplier = rangy.createClassApplier("sitarium_highlight");
		}
		
		$.when(
			myFlyEditor.animate_navbar()
		)
		.then(function() {
			// Initialization of editable/repeatable elements
			myFlyEditor.options.editables = myFlyEditor.options.editables.add(myFlyEditor.options.repeatables.children());
			myFlyEditor.make_editable(myFlyEditor.options.editables);
			
//			$('body').on('touchstart touchend touchcancel touchleave touchmove', function(event) {
//				console.log(event);
//			});

			// Text selection
			$("body").on('mouseup keyup touchend touchcancel', function(event) {
				if (rangy.getSelection().rangeCount > 0)
				{
					var selection = rangy.getSelection().getRangeAt(0);
					if (selection.toString() != '')
					{
						var $element = $(selection.commonAncestorContainer);
						var maxLevels = 10;
						var currentLevel = 0;
						while ($element.not("body") && currentLevel < maxLevels)
						{
							if ($element.hasClass('fly-editor_editable')) {
								if (typeof($element.data('bs.popover')) != 'undefined' && $element.data('bs.popover').tip() != 'undefined')
								{
									$element.data('bs.popover').tip().find('.fly-editor_highlight').removeAttr('disabled');
								}
								$frame = $("#fly-editor_frame_" + $element.data('fly-editor_id'));
								break;
							}
							$element = $element.parent();
							currentLevel++;
						}
					}
					else
					{
						$('.popover_container').find('.fly-editor_highlight').attr('disabled', 'disabled');
					}
				}
			});
		});
		
		myFlyEditor.lastAction = new Date();
		
		myFlyEditor.root.before('<div class="position_check" />');
		var $position_check = $('.position_check');
		var position_offset = $position_check.offset();
		var flyeditor_position_check_delay = 1000/30;
		setInterval(function() {
 			if (position_offset.left != $position_check.offset().left || position_offset.top != $position_check.offset().top)
 			{
 				position_offset = $position_check.offset();
 				myFlyEditor.resize_all_frames(false);
 			}
 			
		}, flyeditor_position_check_delay);

		myFlyEditor.root.after('<div class="popover_container fly-editor" />');
	};
	
	$.flyeditor = function (options) {
		return new FlyEditor(options);
	};
	
	$.flyeditor.defaults = {
		root: 'body',
		repeatables: null,
		editables: null,
		csrf: '',
		position_check: '.position_check',
		highlightApplier: null
	};
	
	FlyEditor.prototype.animate_navbar = function()
	{
		// Animation of navigation bar
		$("#fly-editor_navbar").animate({
			minHeight: '50px',
			height: '50px'
		}, {
			duration: 400,
			queue: false
		});
		$('*:not(.fly-editor *)').filter(function() {return $(this).css("position") === 'fixed' && $(this).position().top === 0;}).add("body").animate({
			paddingTop: '50px',
			backgroundPositionY: '+=50px'
		}, {
			duration: 400,
			queue: false
		});
	}
	
	FlyEditor.prototype.add_frame = function($ref)
	{
		var myFlyEditor = this;
		
		var id = $ref.data('fly-editor_id');
		
		myFlyEditor.root.append(
			'<div id="fly-editor_frame_' + id + '" class="fly-editor_frame fly-editor_original"></div>'
		);

		var $frame = $('#fly-editor_frame_' + id);
		$frame
			.css('marginTop', $ref.css('marginTop'))
			.css('marginRight', $ref.css('marginRight'))
			.css('marginBottom', $ref.css('marginBottom'))
			.css('marginLeft', $ref.css('marginLeft'))
			.css('paddingTop', $ref.css('paddingTop'))
			.css('paddingRight', $ref.css('paddingRight'))
			.css('paddingBottom', $ref.css('paddingBottom'))
			.css('paddingLeft', $ref.css('paddingLeft'))
			.css('border-radius', $ref.css('border-radius'));

		myFlyEditor.resize_frame($ref);
		
		var buttons = [];

		if ($ref.is('img'))
		{
			$frame.append(
					'	<div id="dropzone_' + id + '" class="dropzone" data-width="' + $ref.width() + '" data-height="' + $ref.height() + '" data-image="' + $ref.attr("src") + '" data-resize="true" data-url="/fly-editor/image_upload" style="width: 100%;">' +
					'		<input type="file" name="thumb" />' +
					'	</div>'
			);
			$('#dropzone_' + id).html5imageupload({
				data: {
					'_token': myFlyEditor.options.csrf
				},
				onUndo: function() {
					$ref.attr('src', $ref.data('initial_url'));
					this.reset();
					this._init();
					$frame.removeClass('fly-editor_updated').addClass('fly-editor_original');
				},
				onAfterInitImage: function() {
					$(this.element).find('.fly-editor_undo_cancel').remove();
					$(this.element).find('.fly-editor_undo').attr('disabled', 'disabled');
				},
				onAfterCancel: function() {
					var myHtml5ImageUpload = this;
					$(this.element)
					.append(
							'	<button type="button" class="fly-editor_undo fly-editor_undo_cancel btn btn-warning btn-xs float-button">' +
							'		<span class="glyphicon glyphicon-share-alt"></span>' +
							'	</button>'
					)
					.find('.fly-editor_undo_cancel').click(function()
					{
						myHtml5ImageUpload.undo();
					});
				},
				onAfterSelectImage: function() {
					$(this.element).find('.fly-editor_undo_cancel').remove();
				},
				onAfterProcessImage: function() {
					$ref.attr('src', $(this.element).data('final_url').split("?")[0]);
					$frame.removeClass('fly-editor_original').addClass('fly-editor_updated');
				}
			});
		}
		else
		{
			// Undo button
			buttons.push({
				selector: '.fly-editor_undo',
				html: 
					'	<button type="button" class="fly-editor_undo btn btn-warning btn-xs" disabled="disabled">' +
					'		<span class="glyphicon glyphicon-share-alt"></span>' +
					'	</button>',
				action: function(event) {
					event.preventDefault();
					myFlyEditor.triggerAction(function() {
						$ref
							.html($ref.data('initial_value'))
							.trigger('input');
					});
				}
			});
			
			// Highlight button
			buttons.push({
				selector: '.fly-editor_highlight',
				html: 
					'	<button type="button" class="fly-editor_highlight btn btn-primary btn-xs" disabled="disabled">' +
					'		<span class="glyphicon glyphicon-text-size"></span>' +
					'	</button>',
				action: function(event) {
					event.preventDefault();
					myFlyEditor.triggerAction(function() {
						if (myFlyEditor.options.highlightApplier != null && $ref.containsRange(rangy.getSelection().getRangeAt(0), true))
						{
							myFlyEditor.options.highlightApplier.toggleSelection();
							rangy.getSelection().removeAllRanges();
							$ref.trigger('input');
						}
					});
				}
			});

			// Repeatables
			if ($ref.parent().is('.fly-editor_repeatable'))
			{
				// Move up and down buttons
				buttons.push({
					selector: '.fly-editor_up',
					html: 
						'	<button type="button" class="fly-editor_up btn btn-info btn-xs">' +
						'		<span class="glyphicon glyphicon-chevron-up"></span>' +
						'	</button>',
					action: function(event) {
						event.preventDefault();
						myFlyEditor.triggerAction(function() {
							$ref.insertBefore($ref.prev());
							myFlyEditor.resize_all_frames();
						});
					}
				});	
				buttons.push({
					selector: '.fly-editor_down',
					html: 
						'	<button type="button" class="fly-editor_down btn btn-info btn-xs">' +
						'		<span class="glyphicon glyphicon-chevron-down"></span>' +
						'	</button>',
					action: function(event) {
						event.preventDefault();
						myFlyEditor.triggerAction(function() {
							$ref.insertAfter($ref.next());
							myFlyEditor.resize_all_frames();
						});
					}
				});

				// Clone button
				buttons.push({
					selector: '.fly-editor_clone',
					html: 
						'	<button type="button" class="fly-editor_clone btn btn-info btn-xs">' +
						'		<span class="glyphicon glyphicon-duplicate"></span>' +
						'	</button>',
					action: function(event) {
						event.preventDefault();
						myFlyEditor.triggerAction(function() {
							$clone = $ref.clone(false);
							$clone.insertAfter($ref);
							myFlyEditor.options.editables = myFlyEditor.options.editables.add($clone);
							myFlyEditor
								.make_editable($clone)
								.resize_all_frames(false);
						});
					}
				});

				// Delete button
				buttons.push({
					selector: '.fly-editor_delete',
					html: 
						'	<button type="button" class="fly-editor_delete btn btn-danger btn-xs">' +
						'		<span class="glyphicon glyphicon-trash"></span>' +
						'	</button>',
					action: function(event) {
						event.preventDefault();
						myFlyEditor.triggerAction(function() {
							$ref.attr('id', 'to_be_removed');
							$parent = $ref.parent();
							$("#fly-editor_frame_" + $ref.data('fly-editor_id')).remove();
							$ref.popover('destroy');
							$ref.remove();
							myFlyEditor.options.editables.remove('#to_be_removed');
							myFlyEditor.resize_all_frames();
						});
					}
				});
			}
			
			// Close button
			buttons.push({
				selector: '.fly-editor_close',
				html: 
					'	<button type="button" class="fly-editor_close btn btn-default btn-xs">' +
					'		<span class="glyphicon glyphicon-remove"></span>' +
					'	</button>',
				action: function(event) {
					event.preventDefault();
					myFlyEditor.triggerAction(function() {
						$ref.popover('hide');
					});
				}
			});
		}
		
		$ref.data('fly-editor_buttons', buttons);
		
		$ref
			.popover({
				trigger: 'manual',
				html: true,
				content: function() {
					var final_content = '<div class="fly-editor_buttons">';
					for (var i = 0; i < buttons.length; i++) {
						final_content += buttons[i].html;
					}
					return final_content + '</div>';
				},
				placement: 'top',
				container: $('.popover_container')
			})
			.on('focus', function() {
				myFlyEditor.options.editables.not($ref).popover('hide');
				if (typeof($ref.data('bs.popover').tip()) == 'undefined' || ! $ref.data('bs.popover').tip().hasClass('in')) {
					$ref.popover('show');
					myFlyEditor.reset_popover_buttons($ref);
				}
			});
		
		return this;
	};
	
	FlyEditor.prototype.triggerAction = function(action)
	{
		var myFlyEditor = this;
		
		if (typeof(action) == 'function')
		{
			var limit = (new Date(myFlyEditor.lastAction)).setMilliseconds(myFlyEditor.lastAction.getMilliseconds() + 100);
			var now = new Date();
			if (now > limit) 
			{
				myFlyEditor.lastAction = now;
				action();
			}
		}
	}
	
	FlyEditor.prototype.resize_all_frames = function(refresh_popover)
	{
		var myFlyEditor = this;

		var $ref = myFlyEditor.options.editables;

		if (refresh_popover == null) {
			refresh_popover = true;
		}
		
		$ref.each(function() {
			var $this = $(this);
			
			myFlyEditor.resize_frame($this, refresh_popover);
		});
	};
	
	FlyEditor.prototype.resize_frame = function($ref, refresh_popover)
	{
		var myFlyEditor = this;
		
		if ($ref != null)
		{
			if (refresh_popover == null) {
				refresh_popover = true;
			}
			
			$ref.each(function() {
				var $this = $(this);
				
				var isFixed = elementOrParentIsFixed($this);
		        if (isFixed) {
		        	$("#fly-editor_frame_" + $this.data('fly-editor_id')).css('position', 'fixed');
		        }
		        
				var offset = $this.offset();
				$("#fly-editor_frame_" + $this.data('fly-editor_id'))
					.offset({ top: offset.top-1, left: offset.left-1 })
					.width($this.width())
					.height($this.height());
				
				if (typeof($this.data('bs.popover')) != 'undefined' && $this.data('bs.popover').tip() != 'undefined' && $this.data('bs.popover').tip().hasClass('in'))
				{
					if (refresh_popover == true) {
						$this.popover('show');
					}
					myFlyEditor.reset_popover_buttons($this);
				}
			});
		}
		return this;
	};
	
	FlyEditor.prototype.reset_popover_buttons = function($ref)
	{
		var myFlyEditor = this;
		
		$ref.each(function() {
			var $this = $(this);
			
			if ($this.data('bs.popover') != 'undefined' && $this.data('bs.popover').tip() != 'undefined')
			{
				var buttons = $this.data('fly-editor_buttons');
				if (typeof(buttons) != 'undefined')
				{
					for (var i = 0; i < buttons.length; i++) 
					{
						if (typeof(buttons[i].action) === 'function') 
						{
							$ref.data('bs.popover').tip()
								.find(buttons[i].selector)
								.on('touchstart click', buttons[i].action);
						}
					}
				}
				
				$this.data('bs.popover').tip().find('.fly-editor_up, fly-editor_down').removeAttr('disabled');
				if ($this.is(':first-child'))
				{
					$this.data('bs.popover').tip().find('.fly-editor_up').attr('disabled', 'disabled');
				}
				if ($this.is(':last-child'))
				{
					$this.data('bs.popover').tip().find('.fly-editor_down').attr('disabled', 'disabled');
				}

				if ($this.data('initial_value') != $this.html())
				{
					$this.data('bs.popover').tip().find('.fly-editor_undo').removeAttr('disabled');
				}
				else
				{
					$this.data('bs.popover').tip().find('.fly-editor_undo').attr('disabled', 'disabled');
				}
			}
		});
		
		return this;
	};
	
	FlyEditor.prototype.make_editable = function($ref)
	{
		$ref = typeof $ref !== 'undefined' ? $ref : this.options.editables;
		
		var myFlyEditor = this;
		
		$ref
			.each(function() {
				var $this = $(this);
				var id = idGenerator.getId();

				$this.data('fly-editor_id', id);
					
				if ($this.is('img'))
				{
					$this.data('initial_url', $this.attr('src'));
				}
				else
				{
					$this
						.data('initial_value', $this.html())
						.attr("contenteditable", "true")
						.unbind('input').on('input', function(event)
						{
							var $this = $(this);
							var offset = $this.offset();
							$frame = $("#fly-editor_frame_" + id);
							
							// Resize the frame
							myFlyEditor.resize_all_frames(false);
							
							// Check the changes
							if ($this.data('initial_value') != $this.html())
							{
								$frame.removeClass('fly-editor_original').addClass('fly-editor_updated');
							}
							else
							{
								$frame.removeClass('fly-editor_updated').addClass('fly-editor_original');
							}
						});
					$this.pastePlainText();
//						.on('paste', function(event) {
//							var $this = $(this);
//							var saved_content = $this.html();
//							
//							if (event && event.clipboardData && event.clipboardData.getData)
//							{
//								console.log(event);
//							}
//							else
//							{
//								$this.empty();
//								// waitforpastedata
//								if ($this.contents().length > 0)
//								{
//									//processpaste
//									
//								}
//								else
//								{
//								
//								}
//								return true;
//							}
//							event.preventDefault();
//							
////							pasteTarget.innerHTML = event.clipboardData.getData("text/plain");
//						});
				}
				myFlyEditor.add_frame($this);
			});
		
		return this;
	};
	
	FlyEditor.prototype.submit = function(options)
	{
		var myFlyEditor = this;
		
		var $content = $('body');
		var $clone = $content.clone();
		$clone.removeAttr('style');
		$clone.find(myFlyEditor.options.root).remove();
		$clone.find('.position_check').remove();
		$clone.find('.popover_container').remove();
		$clone.find('[contenteditable]').removeAttr('contenteditable');
		
		var submission = {
				"submission" : $clone[0].outerHTML,
				"pathname" : window.location.pathname,
				"_token" : myFlyEditor.options.csrf
		};
		$.ajax({
			type: "post",
			url: '/fly-editor/submit',
			data: submission,
			dataType: "json",
			timeout: 5000,
		})
		.done(function(result, status, jqXHR)
		{
			if (typeof result.code !== 'undefined' && result.code === 0)
			{
				$content.find('.fly-editor_frame').remove();
				myFlyEditor.make_editable();
				
				if($.isFunction(options.callbacks.success)) {
					options.callbacks.success(result);
				}
			}
			else
			{
				if($.isFunction(options.callbacks.error)) {
	    			options.callbacks.error({
	    				code: -1,
	    				message: result.message !== 'undefined' ? result.message : 'Unexpected error'
	    			});
	    		}
			}
		})
		.fail(function(jqXHR, status, errorThrown)
		{
			if($.isFunction(options.callbacks.error)) {
    			options.callbacks.error({
    				code: status,
    				message: errorThrown
    			});
    		}
		});
		
		$clone = null;
		
		return this;
	};
		
}(jQuery);