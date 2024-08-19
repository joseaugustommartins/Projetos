<?php
date_default_timezone_set('America/Sao_Paulo'); // Defina seu fuso horário

// Inicializa a data do evento com uma data padrão
$eventDate = isset($_POST['eventDate']) ? new DateTime($_POST['eventDate']) : new DateTime('2024-08-17 00:00:00');

// Data e hora atual
$currentDate = new DateTime();

// Calcula a diferença entre as duas datas
$interval = $currentDate->diff($eventDate);

// Variáveis para armazenar a contagem inicial
$days = $interval->days;
$hours = $interval->h;
$minutes = $interval->i;
$seconds = $interval->s;

// Formata o tempo inicial para passar para o JavaScript
$initialCountdown = [
    'days' => $days,
    'hours' => $hours,
    'minutes' => $minutes,
    'seconds' => $seconds
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contagem Regressiva</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link para o arquivo CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet"> <!-- Fonte Montserrat -->
    <style>
        /* Estilos para a caixa de formulário */

        .date-form {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        /* Mostra a caixa quando o usuário passa o mouse sobre a caixa de formulário */
        .date-form:hover {
            opacity: 1;
            visibility: visible;
        }

        /* Estilo para o botão de encerrar contagem */
        #endCountdownButton {
            font-size: 16px;
            padding: 5px 10px;
            border: none;
            background-color: #ff4d4d;
            /* Cor vermelha para o botão de encerrar */
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        #endCountdownButton:hover {
            background-color: #cc0000;
            /* Cor vermelha escura para hover */
        }
    </style>
</head>

<body>
    <div class="countdown">
        <div id="countdown-timer">
            <!-- Bloco para Dias e Horas -->
            <div class="upper-row">
                <div id="days" class="time-unit"></div> Dias
                <div id="hours" class="time-unit"></div> Horas
            </div>
            <!-- Faixa divisória para Dias e Horas -->
            <div class="divider"></div>
            <!-- Bloco para Minutos e Segundos -->
            <div class="lower-row">
                <div id="minutes" class="time-unit"></div> Min.
                <div id="seconds" class="time-unit"></div> Seg.
            </div>
        </div>
    </div>

    <!-- Adicionando a faixa branca à esquerda -->
    <div class="left-side-bg"></div>

    <!-- Adicionando a faixa vermelha à direita -->
    <div class="right-side-bg"></div>

    <!-- Formulário para inserir a data do evento -->
    <div class="date-form">
        <form method="post" action="">
            <label for="eventDate">Data e Hora do Evento:</label><br>
            <input type="datetime-local" id="eventDate" name="eventDate" value="<?php echo $eventDate->format('Y-m-d\TH:i'); ?>"><br><br>
            <button type="submit">Atualizar Contagem</button>
        </form>
        <button id="endCountdownButton">Encerrar Contagem</button>
    </div>

    <script>
        // Obtém o tempo inicial do PHP
        const initialCountdown = <?php echo json_encode($initialCountdown); ?>;

        // Calcula o tempo final em milissegundos desde o Epoch
        const eventDate = new Date('<?php echo $eventDate->format("Y-m-d H:i:s"); ?>').getTime();

        let countdownActive = true; // Controle se a contagem está ativa

        function updateCountdown() {
            if (!countdownActive) return; // Se a contagem não estiver ativa, não atualiza

            const now = new Date().getTime();
            const distance = eventDate - now;

            if (distance < 0) {
                document.getElementById("countdown-timer").innerHTML = "<div class='countdown-message'>TEMPO ESGOTADO!</div>";
                countdownActive = false; // Para a contagem regressiva
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").textContent = days;
            document.getElementById("hours").textContent = hours;
            document.getElementById("minutes").textContent = minutes;
            document.getElementById("seconds").textContent = seconds;

            setTimeout(updateCountdown, 1000);
        }

        // Adiciona um listener para o botão de encerrar contagem
        document.getElementById('endCountdownButton').addEventListener('click', function() {
            document.getElementById("countdown-timer").innerHTML = "<div class='countdown-message'>META CONCLUÍDA!!!</div>";
            countdownActive = false; // Para a contagem regressiva
        });

        // Inicia a contagem regressiva
        updateCountdown();
    </script>
</body>

</html>
