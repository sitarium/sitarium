@include('admin/_header')

    <div class="container">
        <div class="row">
        
            <div class="col-md-10 col-md-offset-1">
            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	Website infos
                    </div>
                    
                    <div class="panel-body">
                
        				{!! Form::model($website, ['url' => '/admin/website', 'id' => 'website_form', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        				
                    		<input type="hidden" name="id" value="{{ $website->id }}">
            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Name</label>
        
                                <div class="col-md-6">
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
        
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Host</label>
        
                                <div class="col-md-6">
        							{!! Form::text('host', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
            
                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail Address</label>
        
                                <div class="col-md-6">
                                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Active</label>
        
                                <div class="col-md-6">
                                	<div class="btn-group button-switch" data-toggle="buttons">
                                        <label class="btn btn-default" data-active-class="btn-success" data-inactive-class="btn-default">
                                        	{!!  Form::radio('active', 1, ['autocomplete' => 'off']); !!} Active
                                        </label>
                                        <label class="btn btn-default" data-active-class="btn-danger" data-inactive-class="btn-default">
                                        	{!!  Form::radio('active', 0, ['autocomplete' => 'off']); !!} Inactive
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
                
                <div class="panel panel-default" id="users_panel">
                    <div class="panel-heading">
                    	Users
                    </div>
                    <div class="content"></div>
                </div>
            
                <div class="panel panel-danger hidden" id="delete_panel">
                    <div class="panel-heading">
                		<a data-toggle="collapse" href="#collapse_danger" class=" text-danger">
                        	Danger zone	
                    	</a>
                    </div>
                    <div id="collapse_danger" class="panel-collapse collapse">
                    	<div class="panel-body">
                    	
        					{!! Form::open(['url' => '/admin/website', 'method' => 'delete', 'id' => 'delete_form', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        				
        						{!! Form::hidden('id', $website->id) !!}
                
                                <div class="form-group">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-btn fa-delete"></i>Delete website
                                        </button>
                                    </div>
                                </div>
        
                        	{!! Form::close() !!}
                        
                    	</div>
                    </div>
                </div>
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
        		$('#users_panel, #delete_panel').hide();
			}
    		$('#users_panel, #delete_panel').removeClass('hidden');

    		$('.button-switch').buttonswitch();

    		$('#website_form').ajaxform({
        		locale: 'fr',
        		resetOnSuccess: false,
        		callback: function(callback_vars) {
        			$('#website_form, #delete_form').find('input[name="id"]').val(callback_vars.id);
        			getUsers();
        			$('#delete_panel').slideDown();
        			$('#users_panel').slideDown();
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
    		
        	function getUsers(page) {
            	var url = '/admin/users/' + $('#website_form').find('input[name="id"]').val();
            	if (page != null && page != "undefined") {
					url += '?page=' + page;
            	}
            	
                $.ajax({
                    url: url,
                    dataType: 'json',
                }).done(function (data) {
                    $('#users_panel').find('.pagination_links').remove();
                    $('#users_panel').find('.content').replaceWith(data);
                    
                	$('#users_panel').find('.pagination a').on('click', function (event) {
                        event.preventDefault();
                        getUsers($(this).attr('href').split('page=')[1]);
                    });
                }).fail(function () {
                	$('#users_panel').find('.content').replaceWith('<div class="content panel-body alert-danger" role="alert">Users could not be loaded.</div>');
                });
            };

            getUsers();

    	}(jQuery);
	</script>
	
@include('admin/_footer')