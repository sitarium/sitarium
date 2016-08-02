@include('admin/_header')

    <div class="container">
        <div class="row">
        
            <div class="col-md-10 col-md-offset-1">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	User infos
                    </div>
                    
                    <div class="panel-body">
                
        				{!! Form::model($user, ['url' => '/admin/user', 'id' => 'user_form', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        				
                    		<input type="hidden" name="id" value="{{ $user->id }}">
            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Name</label>
        
                                <div class="col-md-6">
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
            
                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail Address</label>
        
                                <div class="col-md-6">
                                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Admin</label>
        
                                <div class="col-md-6">
                                	<div class="btn-group button-switch" data-toggle="buttons">
                                        <label class="btn btn-default" data-active-class="btn-success" data-inactive-class="btn-default">
                                        	{!!  Form::radio('admin', 1, ['autocomplete' => 'off']); !!} Yes
                                        </label>
                                        <label class="btn btn-default" data-active-class="btn-danger" data-inactive-class="btn-default">
                                        	{!!  Form::radio('admin', 0, ['autocomplete' => 'off']); !!} No
                                        </label>
            						</div>
                                </div>
                            </div>
            
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i>Submit
                                    </button>
                                </div>
                            </div>
        
                        {!! Form::close() !!}
                        
               		</div>
                </div>
                
                <div class="panel panel-default" id="websites_panel">
                    <div class="panel-heading">
                    	Websites
                    </div>
                    <div class="content"></div>
                    <div class="panel-footer">
            			<a class="btn btn-sm btn-primary" href="{{ url('/admin/website') }}" role="button">Add a website</a>
                    </div>
                </div>
                
        		@if (intval($user->id) !== Auth::user()->id)
            
                    <div class="panel panel-danger hidden" id="delete_panel">
                        <div class="panel-heading">
                    		<a data-toggle="collapse" href="#collapse_danger" class=" text-danger">
                            	Danger zone	
                        	</a>
                        </div>
                        <div id="collapse_danger" class="panel-collapse collapse">
                        	<div class="panel-body">
                        	
            					{!! Form::open(['url' => '/admin/user', 'method' => 'delete', 'id' => 'delete_form', 'class' => 'form-horizontal', 'role' => 'form']) !!}
            				
            						{!! Form::hidden('id', $user->id) !!}
                    
                                    <div class="form-group">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-btn fa-delete"></i>Delete user
                                            </button>
                                        </div>
                                    </div>
            
                            	{!! Form::close() !!}
                            
                        	</div>
                        </div>
                    </div>
                    
				@endif
				
            </div>
                
            </div>
        </div>
    </div>
    
	{!! Asset::container('jquery')->scripts() !!}
	{!! Asset::container('bootstrap')->scripts() !!}
	{!! Asset::container('button-switch')->scripts() !!}
	{!! Asset::container('ajax-form')->scripts() !!}
	
	<script type="text/javascript">
    	+function($){

			if ($('#user_form').find('input[name="id"]').val() === "") {
        		$('#websites_panel, #delete_panel').hide();
			}
    		$('#websites_panel, #delete_panel').removeClass('hidden');

    		$('.button-switch').buttonswitch();

    		$('#user_form').ajaxform({
        		locale: 'fr',
        		resetOnSuccess: false,
        		callback: function(callback_vars) {
        			$('#user_form, #delete_form').find('input[name="id"]').val(callback_vars.id);
        			getWebsites();
        			$('#delete_panel').slideDown();
        			$('#websites_panel').slideDown();
        		}
    		});

    		$('#delete_form').ajaxform({
        		locale: 'fr',
        		callback: function(callback_vars) {
					var redirect_url = '/admin';
					if (callback_vars !== 'undefined' && callback_vars.redirect_url !== 'undefined')
					{
						redirect_url = callback_vars.redirect_url;
					}
					setTimeout('window.location.replace("' + callback_vars.redirect_url + '")', 2000);
        		}
    		});
    		
        	function getWebsites(page) {
            	var url = '/admin/websites/' + $('#user_form').find('input[name="id"]').val();
            	if (page != null && page != "undefined") {
					url += '?page=' + page;
            	}
            	
                $.ajax({
                    url: url,
                    dataType: 'json',
                }).done(function (data) {
                    $('#websites_panel').find('.pagination_links').remove();
                    $('#websites_panel').find('.content').replaceWith(data);
                    
                	$('#websites_panel').find('.pagination a').on('click', function (event) {
                        event.preventDefault();
                        getWebsites($(this).attr('href').split('page=')[1]);
                    });
                }).fail(function () {
                	$('#websites_panel').find('.content').replaceWith('<div class="content panel-body alert-danger" role="alert">Websites could not be loaded.</div>');
                });
            };

            getWebsites();

    	}(jQuery);
	</script>
	
@include('admin/_footer')