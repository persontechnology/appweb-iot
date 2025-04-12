const channelName = 'canal-notificar-dispositivo';
let subscribed = false;
const eventHandlers = [];

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
// 🔹 Función para suscribirse solo una vez
const subscribeToDeviceChannel = () => {
    if (subscribed) return;
    subscribed = true;

    window.Echo.channel(channelName)
        .subscribed(() =>
            console.log(`✔️ Suscripción al canal "${channelName}"`),
        )
        .listen('.NotificarDispositivoEvento', (e) => {
            console.log('📢 Evento recibido:', e);
            eventHandlers.forEach((handler) => handler(e));
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
        .error((err) =>
            console.error('❌ Error al suscribirse al canal:', err),
        );
};

// 🔹 Función para registrar un nuevo manejador de eventos en React
const registerDeviceEventHandler = (handler) => {
    eventHandlers.push(handler);
};

// 🔹 Iniciar la suscripción
subscribeToDeviceChannel();

export { registerDeviceEventHandler };
