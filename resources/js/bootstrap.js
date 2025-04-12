import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// 🔹 Habilitar logs en consola para depuración
Pusher.logToConsole = true;
window.Pusher = Pusher;
// 🔹 Configuración de Laravel Echo con Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// 🔹 Estado para controlar la reproducción del sonido
let isSoundPlaying = false;

// 🔹 Función para reproducir el sonido con control de reproducción
const playAlarmSound = () => {
    if (isSoundPlaying) {
        console.log('⚠️ El sonido ya está en reproducción.');
        return; // No hacer nada si el sonido ya se está reproduciendo
    }

    isSoundPlaying = true;
    const audio = new Audio('http://209.126.85.168/sound/alert.mp3'); // Ruta del archivo en "public/sounds/"

    audio
        .play()
        .then(() => {
            console.log('🔊 Sonido reproducido con éxito');
            isSoundPlaying = false; // Restablecer el estado después de la reproducción
        })
        .catch((error) => {
            console.error('🔊 Error al reproducir el sonido:', error);
            isSoundPlaying = false; // Restablecer el estado si hay un error
        });
};

// 🔹 Suscripción al canal y escucha del evento
window.Echo.channel('canal-notificar-dispositivo')
    .subscribed(() => {})
    .listen('.NotificarDispositivoEvento', (e) => {
        console.log('⚠️ ingresooo');
        // Verifica si se recibió el dispositivo
        if (!e.dispositivo) {
            console.log('⚠️ No se ha recibido el dispositivo.');
            return;
        }
        let dispositivo = e.dispositivo;
        let tenant_id = window.Laravel.tenant_id;
        // Verifica si el dispositivo pertenece al tenant actual
        if (tenant_id === dispositivo.tenant_id) {
            playAlarmSound();
        } else {
            console.log('⚠️ Dispositivo no pertenece a este tenant.');
        }
    })
    .error((err) => {
        console.log('❌ Error al suscribirse al canal:', err);
    });
