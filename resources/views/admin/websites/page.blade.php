@forelse ($websites as $website)
	@if ($website == $websites->first())
		<table class="content table">
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