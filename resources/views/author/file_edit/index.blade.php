@push('css')
	<style>
		.breadcrumb ul{ padding: 0; margin:0;}
		.breadcrumb li{ display: inline; font-size: 20px}
		.breadcrumb{ padding: 5px 10px; margin-bottom: .5rem;}
		.breadcrumb {
			display: -ms-flexbox;
			display: flex;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			padding: 0.75rem 1rem;
			margin-bottom: 1rem;
			list-style: none;
			background-color: #e9ecef;
			border-radius: 0.25rem;
		}

		.CodeMirror{
			border: 1px solid #ccc;
			/*background-color: #f5f5f5;*/
			height:500px;
		}
		.error-marker {
			color: black;
			width: 10px !important;
			background-color: #ff0000;
		}

		.error-marker .error-message {
			display: none;
			position: absolute;
			background-color: #ddd;
			border: 1px solid #999;
			padding: 6px;
			width: 140px;
			left: 15px;
			top: -1em;
		}

		.error-marker:hover .error-message {
			display: block;
		}
	</style>
@endpush
@push('css_plugin')
	<link rel="stylesheet"
		  href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
	</link>
	<link rel="stylesheet"
		  href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/monokai.min.css">
@endpush
@push('plugin')
	<script type="text/javascript"
			src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js">
	</script>
	<script type="text/javascript"
			src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.min.js">
	</script>
@endpush
@extends("sourcebit::author.master")
@section("content")
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<form method="post" action="{{url($bUrl.'store')}}" enctype="multipart/form-data" >
			@csrf
			<div class="card">
				<div class="card-header">
					<h2 class="card-title"> {!! $page_icon !!} &nbsp; {{ $title }} </h2>
					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
							<i class="fas fa-minus"></i>
						</button>
{{--						<button type="button" class="btn btn-tool" >--}}
{{--							<a href="{{url($bUrl)}}" class="btn btn-info btn-sm"><i class="mdi mdi-plus"></i> <i class="fa fa-arrow-left"></i> Back</a>--}}
{{--						</button>--}}
					</div>
				</div>
				<div class="card-body">
					<div class="col-12 breadcrump-mediamanager">
						<div class="breadcrumb">
							<ul class="text-success">
								<li><i class="fas fa-home"></i> / {{$fileName}}</li>
							</ul>
						</div>
					</div>
					<div class="col-md-12">
						{!! validation_errors($errors) !!}
						<div id="robots_aria" class="form-group row ">
							<input type="hidden" value="{{$fileName}}" name="fileName">
							<label for="" class="col-sm-12 col-form-label">{{$name}} </label>
							<div class="col-sm-12">
								<textarea name="content" id="editor" cols="50" rows="10" class="form-control" style="height:500px" spellcheck="false">{!! $fileRead !!}</textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="offset-md-2 col-sm-9">
						@php
							$spinner=  '<i class="fas fa-spinner fa-pulse"></i> Please wait... ';
						@endphp
						<button type="submit" name="submit"  class="btn btn-success">Edit File</button>
						&nbsp;&nbsp;
					</div>
				</div>
			</div>
			<!-- /.card-body -->

			</div>

		</form>
		<!-- /.card -->
	</section>
	<!-- /.content -->
@endsection
@push('js')
	<script>
		var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
			lineNumbers: true,
			tabSize: 2,
			value: this.value,
			mode: 'javascript',
			theme: 'monokai',
			gutters: ['error']
		});
		editor.on("gutterClick", function(cm, n) {
			var info = cm.lineInfo(n);
			cm.setGutterMarker(n, "breakpoints", info.gutterMarkers ? null : makeMarker());
		});

		function makeMarker(msg) {
			const marker = document.createElement('div');
			marker.classList.add('error-marker');
			marker.innerHTML = '&nbsp;';

			const error = document.createElement('div');
			error.innerHTML = msg;
			error.classList.add('error-message');
			marker.appendChild(error);

			return marker;
		}
	</script>
@endpush


