import './bootstrap';

import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Bootstrap'i global window nesnesine ata
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Erişilebilirlik için modal davranışını düzenle
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        // Bootstrap yüklendi, işlemlere devam edebiliriz
        console.log('Bootstrap başarıyla yüklendi');
    } else {
        console.error('Bootstrap global olarak erişilebilir değil!');
    }
});
