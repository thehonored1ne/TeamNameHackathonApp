import './bootstrap';

import Alpine from 'alpinejs';

import autoAnimate from '@formkit/auto-animate';

window.Alpine = Alpine;

Alpine.start();

window.autoAnimate = autoAnimate;

autoAnimate(element, {
  duration: 600,
  easing: 'ease-in-out'
});


