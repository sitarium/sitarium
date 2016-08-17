@forelse ($websites as $website)
	@if ($website == $websites->first())
		<table class="content table">
			<tr>
    			<th class="col-md-1">Id</th>
    			<th class="col-md-2">Name</th>
    			<th class="col-md-2">Host</th>
    			<th class="col-md-3">Email</th>
    			<th class="col-md-1">Active</th>
		@if (isset($user) && $user != null)
				<th class="col-md-3">Authorized</th>
		@endif
    		</tr>
	@endif
			<tr>
				<td>{{ $website->id }}</td>
				<td><a href="{{ url('/admin/website', $website->id) }}">{{ $website->name }}</a></td>
				<td><a href="{{ url('/admin/website', $website->id) }}">{{ $website->host }}</a></td>
				<td>{{ $website->email }}</td>
				<td>{{ $website->active ? 'Yes' : 'No' }}</td>
	@if (isset($user) && $user != null)
				<td>
    				{!! Form::open(['url' => '/admin/authorize', 'class' => 'authorize_form form-horizontal', 'role' => 'form']) !!}
    				
    					{!! Form::hidden('websiteId', $website->id) !!}
    					
    					{!! Form::hidden('userId', $user) !!}
    					
                    	<div class="btn-group button-switch" data-toggle="buttons">
                            <label class="btn btn-default" data-active-class="btn-success" data-inactive-class="btn-default">
                            	{!!  Form::radio('authorized', 1, $website->authorized, ['autocomplete' => 'off']); !!} Yes
                            </label>
                            <label class="btn btn-success ajax-form_success_placeholder">
								<span class="glyphicon glyphicon-ok"></span>
								<span class="ajax-form_message"></span>
                            </label>
                            <label class="btn btn-danger disabled ajax-form_error_placeholder">
								<span class="glyphicon glyphicon-ok"></span>
								<span class="ajax-form_message"></span>
                            </label>
                            <label class="btn btn-default" data-active-class="btn-danger" data-inactive-class="btn-default">
                            	{!!  Form::radio('authorized', 0, ! $website->authorized, ['autocomplete' => 'off']); !!} No
                            </label>
						</div>
                    {!! Form::close() !!}
                </td>
	@endif
			</tr>
	@if ($website == $websites->last())
		</table>
		@if ($websites->links() !== "")
            <div class="pagination_links panel-body text-center">
				{{ $websites->links() }}
            </div>
		@endif
	@endif
@empty
	<div class="content panel-body">
	    No website registered.
	</div>
@endforelse


@if (isset($user) && $user != null)
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
	