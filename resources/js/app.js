import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
export default function SimpleComponent() {
    return {
        // You can define any data properties here if needed.
    };
}

Alpine.start();
