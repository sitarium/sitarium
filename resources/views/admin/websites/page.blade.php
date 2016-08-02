@forelse ($websites as $website)
	@if ($website == $websites->first())
		<table class="content table">
			<tr>
    			<th>Id</th>
    			<th>Name</th>
    			<th>Host</th>
    			<th>Email</th>
    			<th>Active</th>
		@if (isset($user) && $user != null)
				<th>Authorized</th>
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
                            <label class="btn btn-default" data-active-class="btn-danger" data-inactive-class="btn-default">
                            	{!!  Form::radio('authorized', 0, ! $website->authorized, ['autocomplete' => 'off']); !!} No
                            </label>
						</div>
						<span class="form_result"></span>
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
        		
    			$form.find('input').change(function() { 
    
        			var $input = $(this);
        			var data = serialize($form);
        			
                	$.ajax({
    					type: $form.attr('method') || 'post',
    					url: $form.attr('action'),
    					data: data,
    					dataType: "json"
    				}).done(function(result) {
    					if (typeof result.code !== 'undefined')
    					{
    						$form.find('.form_result').html(result.message);
    						setTimeout(function() { $form.find('.form_result').html('') }, 2000);
    					}
    					else
    					{
    						$form.find('.form_result').html('Error processing.');
        					$form[0].reset();
        					$buttonSwitch.refresh();
    					}
                    }).fail(function () {
    					$form.find('.form_result').html('Error submitting.');
    					$form[0].reset();
    					$buttonSwitch.refresh();
                    });
                        
        		});
    		});

    		var serialize = function(form)
    		{
    		    var o = {};
    		    var a = form.serializeArray();
    		    $.each(a, function()
    		    {
    		        if (o[this.name] !== undefined)
    		        {
    		            if (!o[this.name].push)
    		            {
    		                o[this.name] = [o[this.name]];
    		            }
    		            o[this.name].push(this.value || '');
    		        } else {
    		            o[this.name] = this.value || '';
    		        }
    		    });
    		    return o;
    		};
    		
    	}(jQuery);
	</script>
@endif
	