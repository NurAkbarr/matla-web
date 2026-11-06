import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { initHeroSlider, initTypewriter } from './hero-slider';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initHeroSlider();
    initTypewriter();
});
