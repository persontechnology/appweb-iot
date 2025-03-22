export function timeAgo(date) {
    if (!date) return "Fecha no disponible";

    const now = new Date();
    const past = new Date(date);
    const diffInSeconds = Math.floor((now - past) / 1000);

    const intervals = [
        { label: "año", seconds: 31536000 },
        { label: "mes", seconds: 2592000 },
        { label: "semana", seconds: 604800 },
        { label: "día", seconds: 86400 },
        { label: "hora", seconds: 3600 },
        { label: "minuto", seconds: 60 },
        { label: "segundo", seconds: 1 },
    ];

    for (const interval of intervals) {
        const count = Math.floor(diffInSeconds / interval.seconds);
        if (count >= 1) {
            return `hace ${count} ${interval.label}${count > 1 ? "s" : ""}`;
        }
    }

    return "justo ahora";
}

