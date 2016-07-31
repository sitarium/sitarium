@include('admin/_header')

    <div class="container">
        <div class="row">
        	@if (Session::has('alertAdminWithDefaultPassword'))
        	
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-danger">
                    <div class="panel-heading">Alert</div>
                    <div class="panel-body text-danger">
                        You still have the default admin password! Hurry up and <strong><a href="/password/reset">change it now!</a></strong>
                    </div>
                </div>
            </div>
        	
        	@endif
        
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default" id="websites_panel">
                    <div class="panel-heading">
                    	Websites
                    </div>
                    <div class="content"></div>
                    <div class="panel-footer">
            			<a class="btn btn-sm btn-primary" href="{{ url('/admin/website') }}" role="button">Add a website</a>
                    </div>
                </div>
                
                <div class="panel panel-default" id="users_panel">
                    <div class="panel-heading">
                    	Users
                    </div>
                    <div class="content"></div>
                    <div class="panel-footer">
                    	<a class="btn btn-sm btn-primary" href="{{ url('/admin/user') }}" role="button">Add a user</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	{!! Asset::container('jquery')->scripts() !!}
	{!! Asset::container('bootstrap')->scripts() !!}
	
	<script type="text/javascript">
    	+function($){

        	function getWebsites(page) {
            	url = '/admin/websites';
            	if (page != null && page != "undefined") {
					url += '?page=' + page;
            	}
            	
                $.ajax({
                    url : url,
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

        	function getUsers(page) {
            	url = '/admin/users';
            	if (page != null && page != "undefined") {
					url += '?page=' + page;
            	}
            	
                $.ajax({
                    url : url,
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