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
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	Websites
                    </div>
                    	@forelse ($websites as $website)
                    		@if ($website == $websites->first())
                        		<table class="table">
                        			<tr>
                            			<th>Id</th>
                            			<th>Name</th>
                            			<th>Host</th>
                            			<th>Email</th>
                            			<th>Active</th>
                            		</tr>
                    		@endif
                    				<tr>
                    					<td>{{ $website->id }}</td>
                    					<td><a href="{{ url('/admin/website', $website->id) }}">{{ $website->name }}</a></td>
                    					<td><a href="{{ url('/admin/website', $website->id) }}">{{ $website->host }}</a></td>
                    					<td>{{ $website->email }}</td>
                    					<td>{{ $website->active ? 'Yes' : 'No' }}</td>
                    				</tr>
                    		@if ($website == $websites->last())
                        		</table>
                    		@endif
                        @empty
                            <div class="panel-body">
                                No website registered.
                            </div>
                        @endforelse
                    <div class="panel-footer">
            			<a class="btn btn-sm btn-primary" href="{{ url('/admin/website') }}" role="button">Add a website</a>
                    </div>
                </div>
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	Users
                    </div>
                    	@forelse ($users as $user)
                    		@if ($user == $users->first())
                        		<table class="table">
                        			<tr>
                            			<th>Id</th>
                            			<th>Name</th>
                            			<th>Email</th>
                            			<th>Admin</th>
                            		</tr>
                    		@endif
                    				<tr>
                    					<td>{{ $user->id }}</td>
                    					<td><a href="{{ url('/admin/user', $user->id) }}">{{ $user->name }}</a></td>
                    					<td><a href="{{ url('/admin/user', $user->id) }}">{{ $user->email }}</a></td>
                    					<td>{{ $user->admin ? 'Yes' : 'No' }}</td>
                    				</tr>
                    		@if ($user == $users->last())
                        		</table>
                    		@endif
                        @empty
                            <div class="panel-body">
                                No website registered.
                            </div>
                        @endforelse
                    <div class="panel-footer">
                    	<a class="btn btn-sm btn-primary href="{{ url('/admin/user') }}" role="button">Add a user</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	{!! Asset::container('jquery')->scripts() !!}
	{!! Asset::container('bootstrap')->scripts() !!}

@include('admin/_footer')