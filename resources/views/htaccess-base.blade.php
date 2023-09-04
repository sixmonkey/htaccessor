@if (count($mod_rewrite_directives))
<IfModule mod_rewrite.c>
RewriteEngine On
@foreach ($mod_rewrite_directives as $directive)
{{ $directive }}
@endforeach
</IfModule>

@endif
@foreach ($directives as $directive)
{{ $directive }}
@endforeach
