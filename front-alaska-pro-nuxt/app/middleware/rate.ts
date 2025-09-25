import { push } from "notivue";
import { defineNuxtRouteMiddleware, navigateTo } from "nuxt/app"

export default defineNuxtRouteMiddleware(() => {
    const RATE_LIMIT = 100; // Máximo de 100 peticiones
    const TIME_WINDOW = 60 * 1000 * 60; // 1 hora en milisegundos
    try {
        const now = Date.now();
        const requestLog = JSON.parse(localStorage.getItem('requestLog') || '[]');

        // Filtrar las peticiones dentro de la ventana de tiempo
        const recentRequests = requestLog.filter((timestamp: number) => now - timestamp < TIME_WINDOW);

        if (recentRequests.length >= RATE_LIMIT) {
            push.warning({ title: 'Bloqueado', message: 'Has alcanzado el límite de peticiones. Por favor, espera un momento.', duration: 3000 });
            localStorage.setItem('rateLimit', "true");

            // Reiniciar después del tiempo de ventana
            setTimeout(() => {
                localStorage.removeItem('rateLimit');
                localStorage.removeItem('requestLog');
            }, TIME_WINDOW);
        } else {
            localStorage.removeItem('rateLimit');
        }

        // Registrar la nueva petición
        recentRequests.push(now);
        localStorage.setItem('requestLog', JSON.stringify(recentRequests));
    } catch (error) {
        console.error(error);
        //return navigateTo('/');
    }
});
