@forelse ($users as $user)
	@if ($user == $users->first())
		<table class="content table">
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