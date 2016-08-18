@forelse ($users as $user)
	@if ($user == $users->first())
		<table class="content table">
			<tr>
    			<th class="col-md-1">Id</th>
    			<th class="col-md-2">Name</th>
    			<th class="col-md-3">Email</th>
    			<th class="col-md-1">Admin</th>
		@if (isset($website) && $website != null)
				<th class="col-md-3">Authorized</th>
		@endif
    		</tr>
	@endif
			<tr>
				<td>{{ $user->id }}</td>
				<td><a href="{{ url('/admin/user', $user->id) }}">{{ $user->name }}</a></td>
				<td><a href="{{ url('/admin/user', $user->id) }}">{{ $user->email }}</a></td>
				<td>{{ $user->admin ? 'Yes' : 'No' }}</td>
	@if (isset($website) && $website != null)
				<td>
    				{!! Form::open(['url' => '/admin/authorize', 'class' => 'authorize_form form-horizontal', 'role' => 'form']) !!}
    				
    					{!! Form::hidden('userId', $user->id) !!}
    					
    					{!! Form::hidden('websiteId', $website) !!}
    					
                    	<div class="btn-group button-switch" data-toggle="buttons">
                            <label class="btn btn-default" data-active-class="btn-success" data-inactive-class="btn-default">
                            	{!!  Form::radio('authorized', 1, $user->authorized, ['autocomplete' => 'off']); !!} Yes
                            </label>
                            <label class="btn btn-success ajax-form_success_placeholder">
								<span class="glyphicon glyphicon-ok"></span>
								<span class="ajax-form_message"></span>
                            </label>
                            <label class="btn btn-danger disabled ajax-form_error_placeholder">
								<span class="glyphicon glyphicon-exclamation-sign"></span>
								<span class="ajax-form_message"></span>
                            </label>
                            <label class="btn btn-default" data-active-class="btn-danger" data-inactive-class="btn-default">
                            	{!!  Form::radio('authorized', 0, ! $user->authorized, ['autocomplete' => 'off']); !!} No
                            </label>
						</div>
                    {!! Form::close() !!}
                </td>
	@endif
			</tr>
	@if ($user == $users->last())
		</table>
		@if ($users->links() !== "")
            <div class="pagination_links panel-body text-center">
				{{ $users->links() }}
            </div>
		@endif
	@endif
@empty
    <div class="content panel-body">
        No user registered.
    </div>
@endforelse


@if (isset($website) && $website != null)
	<script type="text/javascript">
    	+function($){
        	'use strict';

    		$('.authorize_form').each(function() {
        		
				var $form = $(this);
    			var $buttonSwitch = $('.button-switch').buttonswitch();

    			$form.ajaxform({
    				locale: 'fr',
    				resetOnSuccess: false,
    				placeholders: {
        				error: '.ajax-form_error_placeholder',
        				success: '.ajax-form_success_placeholder',
    				},
    				transitions: {
						show: function($placeholder) {
							$placeholder.css('opacity', 0).animate(
								{ opacity: 1, width: 'toggle' },
								{ queue: false }
							);
						},
    					hide: function($placeholder) {
    						$placeholder.animate(
    							{ opacity: 0, width:'hide' },
    							{ queue: false }
    						);
    					}
    				},
    				callbacks: {
        				success: function(myAjaxForm) {
    						setTimeout(function() {
        						myAjaxForm.clearAllAlerts();
							}, 2000);
        					$buttonSwitch.refresh();
        				},
        				error: function() {
        					$form[0].reset();
        					$buttonSwitch.refresh();
        				}
    				},
    				init: function() {
    					$form.find('.ajax-form_success_placeholder, .ajax-form_error_placeholder').hide().removeClass('hidden');
    				}
    			});
        		
    			$form.find('input').change(function() {   
    				$form.submit();
        		});
    		});
    		
    	}(jQuery);
	</script>
@endif