</div>
            </div>
        </div>
        <div class="col-sm-1"></div>
    </div>
</div>

<style>
    #btn-chatbot {
        position: fixed; bottom: 20px; right: 20px;
        background: linear-gradient(135deg, #00C6FF, #0052D4);
        color: white; border: none; border-radius: 50px;
        padding: 15px 25px; font-size: 16px; font-weight: bold;
        cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 9999;
    }
    #chatbot-window {
        position: fixed; bottom: 80px; right: 20px;
        width: 320px; height: 450px; background-color: #0F111A;
        border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        display: flex; flex-direction: column; overflow: hidden;
        z-index: 9999; transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.1);
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
        max-width: 80%; padding: 10px 15px; border-radius: 15px;
        font-size: 14px; line-height: 1.4; color: white;
    }
    .mensaje.usuario { align-self: flex-end; background-color: #0052D4; border-bottom-right-radius: 4px; }
    .mensaje.bot { align-self: flex-start; background-color: #1E2235; border-bottom-left-radius: 4px; }
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
    // 🛡️ INYECCIÓN DE SEGURIDAD DESDE PHP
    // Usamos el arreglo $datos que tu Controlador le pasa a la Vista.
    // Si no detecta la sesión, asume por defecto nivel 3 (Cliente).
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
                    tipoUsuario: rolUsuarioActivo 
                })
            });
            
            const data = await response.json();
            
            const tempMsg = document.getElementById('escribiendo-temp');
            if(tempMsg) tempMsg.remove();

            agregarBurbuja(data.respuesta, 'bot');

            if (data.chart) {
                agregarBurbuja(`📊 [Gráfico generado: ${data.chart.titulo}]. Revisa tu App Móvil para verlo renderizado o pide el detalle en texto.`, 'bot');
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