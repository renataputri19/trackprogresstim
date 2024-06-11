const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copy('node_modules/@fullcalendar/core/main.css', 'public/css/fullcalendar.css')
   .copy('node_modules/@fullcalendar/core/main.js', 'public/js/fullcalendar.js')
   .copy('node_modules/@fullcalendar/daygrid/main.css', 'public/css/fullcalendar.css')
   .copy('node_modules/@fullcalendar/daygrid/main.js', 'public/js/fullcalendar.js')
   .copy('node_modules/@fullcalendar/interaction/main.js', 'public/js/fullcalendar.js');
