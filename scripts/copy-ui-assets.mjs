import { cp, mkdir } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const root = fileURLToPath(new URL('../', import.meta.url));
const copies = [
    ['bootstrap/dist/css/bootstrap.min.css', 'bootstrap/bootstrap.min.css'],
    ['bootstrap/dist/js/bootstrap.bundle.min.js', 'bootstrap/bootstrap.bundle.min.js'],
    ['@fortawesome/fontawesome-free/css/all.min.css', 'fontawesome/css/all.min.css'],
    ['@fortawesome/fontawesome-free/webfonts', 'fontawesome/webfonts'],
    ['bootstrap-icons/font', 'bootstrap-icons/font'],
];
for (const [source, target] of copies) {
    const destination = path.join(root, 'public/vendor', target);
    await mkdir(path.dirname(destination), { recursive: true });
    await cp(path.join(root, 'node_modules', source), destination, { recursive: true });
}
