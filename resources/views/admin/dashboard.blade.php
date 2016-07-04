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
                    <div class="panel-heading">Websites</div>
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
                    					<td>{{ $website->name }}</td>
                    					<td>{{ $website->host }}</td>
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
                    </ul>
                </div>
                
                <div class="panel panel-default">
                    <div class="panel-heading">Users</div>
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
                    					<td>{{ $user->name }}</td>
                    					<td>{{ $user->email }}</td>
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
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
	{!! Asset::container('jquery')->scripts() !!}
	{!! Asset::container('bootstrap')->scripts() !!}

@include('admin/_footer')