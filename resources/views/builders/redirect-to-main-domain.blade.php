RewriteCond %{HTTP_HOST} !^{{ $mainDomain }}*
RewriteRule ^(.*)$ https://{{ $mainDomain }}/$1 [R=301,L]
