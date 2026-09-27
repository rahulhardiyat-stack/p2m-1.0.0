import './bootstrap';

import Alpine from 'alpinejs';

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { Indonesian } from "flatpickr/dist/l10n/id.js";

flatpickr.localize(Indonesian); // Set bahasa default ke Indonesia
window.flatpickr = flatpickr;

window.Alpine = Alpine;

Alpine.start();
