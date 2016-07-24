<div class="fly-editor main_container">

	{!! Asset::container('jquery')->show() !!}
	{!! Asset::container('ajax-form')->show() !!}
	
	{!! Asset::container('fly-editor-bootstrap')->show() !!}
	{!! Asset::container('fly-editor-bootstrap-workaround')->show() !!}
	{!! Asset::container('bgpos')->show() !!}
	
	{!! Asset::container('fly-editor')->show() !!}
	
	@if (! Auth::check())
	
	<!-- Login -->
	<div class="modal fade fly-editor_dialog-container" id="sitarium_login_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span>
					</button>
					<h4 class="modal-title">A qui ai-je l'honneur ?</h4>
				</div>
				
				<div class="modal-body">
					<form class="form-horizontal" role="form" id="fly-editor_login_form" action="/sitarium/login" method="post">
						{{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="sitarium_login_submit_button" type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i>Login
                                    </button>
    
                                    <a class="btn btn-link" href="{{ 'http://' . env('SITARIUM_ADMIN_WEBSITE') . '/password/reset' }}">Forgot Your Password?</a>
                                </div>
                            </div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<script	type="text/javascript">
		+function($){
			'use strict';
			
			$('#fly-editor_login_form').ajaxform({
				locale: 'fr',
				callback: function() {
					setTimeout('window.location.reload(true)', 2000);
				}
			});
			
			$('.sitarium_connection_link')
				.click(function(event) {
					event.preventDefault();
					$("#sitarium_login_modal").modal();
				})
				.show();

		}(jQuery);
	</script>
	
	@else

	{!! Asset::container('html5imageupload')->show() !!}
	{!! Asset::container('rangy')->show() !!}
	{!! Asset::container('PastePlainText')->show() !!}
	
	
	<nav id="fly-editor_navbar" class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-fly-editor-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					Menu
					<span class="glyphicon glyphicon-menu-hamburger"></span>
				</button>
				<a href="#" class="navbar-brand">Sitarium</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-fly-editor-navbar-collapse-1">
				<div class="navbar-element">
					<!-- Open button -->
					<button id="sitarium_open_file_button" type="button" class="btn navbar-btn btn-primary power-button">
						<span class="glyphicon glyphicon-folder-open"></span>
						Ouvrir
					</button>
				</div>
				<div class="navbar-element navbar-right">
					<!-- Logout button -->
					<button id="sitarium_logout_button" type="button" class="btn navbar-btn btn-primary power-button">
						<span class="glyphicon glyphicon-off"></span>
						Se déconnecter
					</button>
				</div>
			</div>
		</div>
	</nav>
	
	<!-- Open file form -->
	<div class="modal fade fly-editor_dialog-container" id="sitarium_open_file_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span>
					</button>
					<h4 class="modal-title">Passons à la suite</h4>
				</div>
				
				<div class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th>Fichier</th>
								<th>Dernière modification</th>
							</tr>
						</thead>
						<tbody>
							
						@foreach ($editable_files as $file)
							<tr>
								<td><a href="{{ $file['name'] }}">{{ $file['name'] }}</a></td>
								<td>{{ $file['date'] }}</td>
							</tr>
						@endforeach
						
						</tbody>
					</table>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Logout form -->
	<div class="modal fade fly-editor_dialog-container " id="sitarium_logout_modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span>
					</button>
					<h4 class="modal-title">Déjà fini ?</h4>
				</div>
				
				<div class="modal-body">
					<h5 class="alert alert-danger">
						Oh non, vous voulez déjà partir ?
					</h5>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<a href="/sitarium/logout" class="btn btn-danger" type="submit">Oui, c'est bon pour moi.</a>
				</div>
			</div>
		</div>
	</div>
	
	<script	type="text/javascript">
		+function($){
			'use strict';

			var $flyeditor = $.flyeditor({
				root: '.fly-editor',
				repeatables: $('.fly-editor_repeatable'),
				editables: $('.fly-editor_editable'),
				csrf: '{{ csrf_token() }}',
			});

			
			$('.navbar-brand').click(function() {
				return false;
			});
			
			$('#sitarium_open_file_button').click(function() {
				$('#sitarium_open_file_modal').modal();
			});
			
			$('#sitarium_logout_button').click(function() {
				$('#sitarium_logout_modal').modal();
			});

			$('.sitarium_connection_link')
				.html('Déconnexion')
				.click(function(event) {
					event.preventDefault();
					$("#sitarium_logout_modal").modal();
				});
			
		}(jQuery);
	</script>
	
	@endif

	<script>
		$.noConflict();
	</script>
</div>
