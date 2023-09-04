RewriteCond %{HTTP_HOST} !^{{ $mainDomain }}*
RewriteRule ^(.*)$ {{ $protocol  }}://{{ $mainDomain }}/$1 [R=301,L]
