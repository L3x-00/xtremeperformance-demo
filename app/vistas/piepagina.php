</div>
            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    #btn-chatbot {
        position: fixed; bottom: 20px; right: 20px;
        background: linear-gradient(135deg, #00C6FF, #0052D4);
        color: white; border: none; border-radius: 50px;
        padding: 15px 25px; font-size: 16px; font-weight: bold;
        cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 9999;
    }
    #chatbot-window {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 435px;  
    height: 653px; 
    
    /* Reglas de seguridad responsivas */
    max-width: 90vw; 
    max-height: 85vh;
    
    background-color: #0F111A;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
    .chat-oculto { display: none !important; }
    .chat-header {
        background: linear-gradient(135deg, #12121D, #1E2235); color: white;
        padding: 15px; display: flex; justify-content: space-between;
        align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    #chat-messages {
        flex: 1; padding: 15px; overflow-y: auto;
        display: flex; flex-direction: column; gap: 10px;
    }
    .mensaje {
        max-width: 85%; padding: 10px 15px; border-radius: 15px;
        font-size: 14px; line-height: 1.4; color: white; word-wrap: break-word;
    }
    .mensaje.usuario { align-self: flex-end; background-color: #0052D4; border-bottom-right-radius: 4px; }
    .mensaje.bot { align-self: flex-start; background-color: #1E2235; border-bottom-left-radius: 4px; }
    
    .chart-container {
        background-color: #151722; border-radius: 10px;
        padding: 10px; margin-top: 5px; width: 100%; box-sizing: border-box;
    }
    
    .chat-input-area {
        display: flex; padding: 10px; background-color: #151722;
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .chat-input-area input {
        flex: 1; background-color: #1E2235; border: none; padding: 10px 15px;
        border-radius: 20px; color: white; outline: none; font-size: 14px;
    }
    .chat-input-area button {
        background: transparent; border: none; color: #00C6FF;
        font-weight: bold; padding: 0 15px; cursor: pointer;
    }
</style>

<button id="btn-chatbot" onclick="toggleChat()">🤖 Mecánico Virtual</button>

<div id="chatbot-window" class="chat-oculto">
    <div class="chat-header">
        <div style="line-height: 1.2;">
            <strong style="font-size: 16px;">Mecánico Virtual</strong><br>
            <small style="color: #aaa; font-size: 12px;">IA de Xtreme Performance</small>
        </div>
        <button onclick="toggleChat()" style="background:none; border:none; color:white; cursor:pointer; font-size: 18px;">✖</button>
    </div>
    
    <div id="chat-messages">
        <div class="mensaje bot">¡Hola! 🚗 Soy el asistente virtual. Puedes preguntarme por tu vehículo o pedirme métricas del taller.</div>
    </div>
    
    <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Escribe un mensaje..." onkeypress="handleEnter(event)">
        <button onclick="enviarMensajeWeb()">Enviar</button>
    </div>
</div>

<script>
    // 🛡️ Nivel de acceso del usuario
    const rolUsuarioActivo = <?php echo isset($datos['usuario']['tipoUsuario']) ? $datos['usuario']['tipoUsuario'] : 3; ?>;

    function toggleChat() {
        document.getElementById('chatbot-window').classList.toggle('chat-oculto');
    }

    function handleEnter(event) {
        if (event.key === 'Enter') enviarMensajeWeb();
    }

    function agregarBurbuja(texto, remitente, isId = false) {
        const chatMessages = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = `mensaje ${remitente}`;
        div.innerText = texto;
        
        if (isId) div.id = "escribiendo-temp";
        
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Función que renderiza el gráfico dentro de una burbuja del chat
    function agregarBurbujaGrafico(chartData) {
        const chatMessages = document.getElementById('chat-messages');
        const canvasId = 'chart-' + Date.now();
        
        // Creamos el contenedor HTML para el canvas
        const div = document.createElement('div');
        div.className = 'mensaje bot chart-container';
        div.innerHTML = `
            <div style="text-align: center; font-weight: bold; margin-bottom: 10px; font-size: 12px; color: #fff;">
                ${chartData.titulo}
            </div>
            <canvas id="${canvasId}" style="max-height: 200px;"></canvas>
        `;
        
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        const ctx = document.getElementById(canvasId).getContext('2d');
        Chart.defaults.color = 'rgba(255, 255, 255, 0.7)'; // Letras blancas para el tema oscuro

        // 1. DIBUJAR DONA (ÓRDENES)
        if (chartData.tipo === 'pastel') {
            const labels = chartData.series.map(s => s.label);
            const dataValues = chartData.series.map(s => s.value);
            const bgColors = chartData.series.map(s => s.color === 'blue' ? '#00C6FF' : '#00E676');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: bgColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '65%'
                }
            });
        } 
        // 2. DIBUJAR BARRAS (INGRESOS)
        else if (chartData.tipo === 'barras') {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Ingresos S/',
                        data: chartData.data,
                        backgroundColor: '#00C6FF',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    async function enviarMensajeWeb() {
    const input = document.getElementById('chat-input');
    const mensaje = input.value.trim();
    if (!mensaje) return;

    agregarBurbuja(mensaje, 'usuario');
    input.value = '';
    agregarBurbuja('Mecánico analizando...', 'bot', true);

    try {
        const response = await fetch('https://www.xtremeperformancepe.com/public/api/endpoints/chatbot_pro.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                mensaje: mensaje,
                rol: rolUsuarioActivo,      // Debe ser 'ADMON', 'CLIENTE' o 'MECANICO'
                id_usuario: idUsuarioActivo // Debe ser el ID numérico (ej. 20)
            })
        });
            const data = await response.json();
            
            const tempMsg = document.getElementById('escribiendo-temp');
            if(tempMsg) tempMsg.remove();

            // 1. Mostrar texto
            agregarBurbuja(data.respuesta, 'bot');

            // 2. Si viene un gráfico, lo pintamos
            if (data.chart) {
                agregarBurbujaGrafico(data.chart);
            }

        } catch (error) {
            const tempMsg = document.getElementById('escribiendo-temp');
            if(tempMsg) tempMsg.remove();
            agregarBurbuja('Error de conexión con el servidor. Intenta nuevamente.', 'bot');
        }
    }
</script>
</body>
</html>