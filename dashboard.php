<?php
session_start();
$_SESSION['key'] = 531378373;
require_once 'controlador/controlador.php';
$controlador = new controlador();
$tem = $controlador->getTemperatura();
$userInfo = $controlador->getUserData($_SESSION['key']);
$historicos = $controlador->getHisticos($_SESSION['key']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
    <style>
        /* Estilo para el ícono de despliegue usando pseudo-elemento */
        select {
            position: relative;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="%23495057"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.29    -3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>'); /* Icono de flecha */
            background-repeat: no-repeat;
            background-position: right 10px center; /* Posición del ícono dentro del select */
            background-size: 12px; /* Tamaño del ícono */
            border-radius: 30px;
        }
        
        /* Estilo para el hover */
        select:hover {
            border-color: #80bdff; /* Cambio de color en hover */
        }
        
        /* Estilo para el focus */
        select:focus {
            outline: none; /* Eliminar el outline por defecto */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Sombra para indicar foco */
        }
        
        .icon-quality {
            display: inline-block;
            width: 122px;    /* Set both width and height to 100px to make the icons circular */
            height: 100px;   /* Ensures uniform size for all icons */
            background-size: cover; /* Ensures the background image covers the entire element */
            background-position: center; /* Centers the background image */
            margin-top: 0px; /* Adds some space to the right of each icon */
            margin-right: 10px; /* Adds some space to the right of each icon */
            background-repeat: no-repeat; /* Prevents the image from repeating */
        }

        input[type="number"],
        input[type="time"],
        input[type="checkbox"],
        input[type="radio"] {
            margin-top: 5px;
            margin-bottom: 20px;
            padding: 10px;
            width: calc(100% - 22px); /* Adjust input width considering padding */
            border: 1px solid #ccc;
            border-radius: 4px;
            display: block;
            box-sizing: border-box;
            border-radius: 30px;
        }
        
        input[type="radio"] {
            width: auto;
            padding: 0;
            margin-right: 10px;
            display: inline-block;
            vertical-align: middle;
        }
        
        label {
            text-align: center; /* Corregido de 'text-aling' a 'text-align' */
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        
        button {
            padding: 10px 20px;
            background-color: #44D2F2;
            color: #000000;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block; /* Center button */
            margin: 20px auto;
            width: 200px; /* Fixed width */
            border-radius: 30px;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        form div {
            margin-bottom: 20px;
        }
        
        .weather > div {
            display: flex;
            align-items: center; /* Alinea verticalmente los elementos en el centro */
            text-align: left; 
            flex-basis: 30%;
            padding: 10px;
            border-radius: 5px;
        }
        .weather i {
            font-size: 24px;
            color: white;
            margin-right: 10px; /* Espacio entre el ícono y el texto */
        }
        .weather strong {
            font-size: 20px;
            color: white;
        }
        p {
            margin: 0;
            color: white;
            font-size: 14px;
        }
        
        .weather{
            margin-left: -13px;
        }
        .ti {
            color: #333;
            text-align: center;
            font-size: 18px;
        }
        
        li {
            margin-bottom: 24px !important; 
        }
        
        .liInf2 {
            display: none;
            color: black;
            font-size: 16px;
        }
        
        .metal img {
            width: 100px;  /* Ajustar según sea necesario */
            height: auto;
            display: block;
            margin: auto;
        }
        
        .metal h3, .metal p {
            text-align: center;
            margin-top: 5px;
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }
        
        .hidden {
            display: none;
        }
        
        .category {
            width: 96%;
            overflow-x: auto; /* Permitir desplazamiento horizontal */
            white-space: nowrap;
        }
        
        .slider {
            list-style: none;
            padding: 0;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        
        .category .slider {
            overflow-x: auto;  /* Permite desplazamiento horizontal cuando sea necesario */
            scrollbar-width: none;  /* Para Firefox */
            -ms-overflow-style: none;  /* Para Internet Explorer 10+ */
        }
        
        .category .slider::-webkit-scrollbar {
            height: 0; /* Para navegadores basados en WebKit como Chrome y Safari */
        }

        .slider li {
            display: inline-block;
            padding: 2px;
            margin-top: 8px;
        }
        
        .slider a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 80px;
        }
        
        .slider a.active {
            background-color: #007bff;
            color: #fff;
        }

    </style>
  </head>
  <body>
    <div class="dashboard">
      <header>
        <div class="f fe">
          <div class="" id="waterQualityIcon"></div>
          <div class="heading">
            <h5 class="date" id="date">5 Abr 2024</h5>
            <h2 class="title" id="waterQualityTitle">Calidad del Agua</h2>
            <input type="hidden" id="calidad" />
          </div>
        </div>

        <div class="weather f">
          <div>
            <i class="fas fa-thermometer-half"></i> <!-- Ícono de termómetro -->
          
            <div>
              <strong id="temperature">--</strong>
              <p>Temperatura</p>
            </div>
          </div>
            
          <div>
            <i class="fas fa-tint"></i> <!-- Ícono de gota de agua para consumo de agua -->
            <div>
              <strong id="consumo">--</strong>
              <p>Consumo</p>
            </div>
          </div>
            
          <div>
            <i class="fas fa-water"></i> <!-- Ícono de agua para PPM dentro del agua -->
            <div>
              <strong id="ppm">--</strong>
              <p>Impurezas</p>
            </div>
          </div>
        </div>
      </header>

      <section>
        <!-- Category -->
        <div class="category">
            <ul class="slider">
                <li><a href="#!" class="active" data-category="control">Control</a></li>
                <li><a href="#!" data-category="datos">Configuración</a></li>
                <li><a href="#!" data-category="info">Información</a></li>
                <li><a href="#!" data-category="graficas">Gráficas</a></li>
                <li><a href="#!" data-category="cuenta">Cuenta</a></li>
            </ul>
        </div>

        <!-- Appliances -->
        <div class="appliances control" style="display: block">
          <div class="appliance">
            <input type="checkbox" name="a" id="a" />
            <label for="a">
              <i class="l"></i>
              <strong>Flujo de agua</strong>
              <span data-o="Abierto" data-c="Cerrado"></span>
              <small></small>
            </label>
          </div>

          <div class="appliance">
            <input type="checkbox" name="a" id="b" />
            <label for="b">
              <i class="r"></i>
              <strong>Enviar lecturas</strong>
              <span data-o="Abierto" data-c="Cerrado"></span>
              <small></small>
            </label>
          </div>
          
              <div class="appliance">
                <label>
                  <i class="l"></i>
                  <strong>¿Se detecto agua?</strong>
                  <span id="agua" style="font-size: 1rem">--</span>
                  <small></small>
                </label>
              </div>
        
        </div>

        <div class="appliances datos" style="display: none">
          
        <div class="appliance">
            <label>
                <i class="l"></i>
                <strong>Configuración de válvula</strong>
                <span id="modo" style="font-size: 1rem">--</span>
                <small></small>
            </label>
        </div>
        
        <div class="appliance">
            <label>
                <i class="l"></i>
                <strong>Se cerrará el flujo despues de:</strong>
                <span id="contador" style="font-size: 1rem">Tiempo restante: Cargando...</span>
            </label>
        </div>

        <div class="container2">
            <h1 style="text-align:center; color: black;">Opciones de consumo</h1>
            <form id="optionsForm">
                
                <div>
                    <input type="radio" id="modeMan" name="mode" onclick="autoSelectMode('modeMan')">
                    <label for="manSetting">Configuración Manual:</label>
                    <input type="checkbox" id="manSetting" name="manSetting">
                </div>
                
                <!--<div>-->
                <!--    <input type="radio" id="modeThreshold" name="mode" onclick="autoSelectMode('modeThreshold')">-->
                <!--    <label for="threshold">Cerrar válvula al alcanzar consumo (litros):</label>-->
                <!--    <input type="number" id="threshold" name="threshold" min="1" step="1">-->
                <!--</div>-->
    
                <div>
                    <input type="radio" id="modeTimeLimit" name="mode" onclick="autoSelectMode('modeTimeLimit')">
                    <label for="timeLimit">Cerrar válvula después de (hora):</label>
                    <input type="time" id="timeLimit" name="timeLimit">
                </div>
    
                <div>
                    <input type="radio" id="modeDefault" name="mode" onclick="autoSelectMode('modeDefault')">
                    <label for="defaultSetting">Configuración predeterminada (Automático):</label>
                    <input type="checkbox" id="defaultSetting" name="defaultSetting">
                </div>

                <button type="submit">Aplicar Configuraciones</button>
            </form>
        </div>
        </div>
        
        <div class="appliances info" style="display: none">
            
            <div class="container2">
                <h2 class="ti">Posibles Metales Pesados Detectados</h2>
                <hr style="border: none; height: 2px; background-color: blue; margin-bottom: 25px;">
                <ul class="listaMetales">
                    <li class="liInf1 seguro hidden">
                        <div class="metal">
                            <img src="assets/image/seguro.webp" alt="Seguro">
                            <h3>¡¡Estás a salvo!!</h3>
                            <p>No se detectó la existencia de ningún metal pesado en el agua.</p>
                        </div>
                    </li>
                    <li class="liInf1 plomo hidden">
                        <div class="metal">
                            <img src="assets/image/plomo.webp" alt="Plomo">
                            <h3>Plomo</h3>
                            <p>Posible existencia de este material.</p>
                        </div>
                    </li>
                    <li class="liInf1 cadmio hidden">
                        <div class="metal">
                            <img src="assets/image/cadmio.webp" alt="Cadmio">
                            <h3>Cadmio</h3>
                            <p>Posible existencia de este material.</p>
                        </div>
                    </li>
                    <li class="liInf1 arsenico hidden">
                        <div class="metal">
                            <img src="assets/image/arsenico.webp" alt="Arsénico">
                            <h3>Arsénico</h3>
                            <p>Posible existencia de este material.</p>
                        </div>
                    </li>
                    <li class="liInf1 cromo hidden">
                        <div class="metal">
                            <img src="assets/image/cromo.webp" alt="Cromo">
                            <h3>Cromo</h3>
                            <p>Posible existencia de este material.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="container2">
                <h2 class="ti">Consejos para Ahorrar Agua Según tus Datos</h2>
                <hr style="border: none; height: 2px; background-color: blue; margin-bottom: 25px;">
                <ul class="listaInf2">
                    <li class="liInf2 bajo">Cierra el grifo mientras te cepillas los dientes o te afeitas.</li>
                    <li class="liInf2 moderado">Instala dispositivos de bajo consumo en inodoros, grifos y duchas.</li>
                    <li class="liInf2 moderado">Repara fugas en grifos, tuberías y accesorios sanitarios de inmediato.</li>
                    <li class="liInf2 alto">Usa la lavadora y el lavavajillas solo cuando estén llenos.</li>
                    <li class="liInf2 alto">Capta y reutiliza el agua de lluvia para regar plantas o limpiar.</li>
                    <li class="liInf2 alto">Reduce el tiempo de ducha y considera instalar una ducha de bajo flujo.</li>
                </ul>
            </div>
            
            <div class="container2">
                <h2 class="ti">Posibles Riesgos de Consumir el Agua Actual</h2>
                <hr style="border: none; height: 2px; background-color: blue; margin-bottom: 25px;">
                <ul class="listaInf">
                    <li class="liInf"><strong>Calidad Del Agua: Excelente</strong> Los riesgos son mínimos, pero podrían surgir problemas si la infraestructura de distribución se contamina o si no se mantiene adecuadamente.</li>
                    <li class="liInf"><strong>Calidad Del Agua: Buena</strong> Pequeños riesgos para poblaciones sensibles, como personas con sistemas inmunológicos debilitados, niños o ancianos, especialmente si el agua tiene trazas de ciertos químicos.</li>
                    <li class="liInf"><strong>Calidad Del Agua: Moderada</strong> Riesgos potenciales incluyen enfermedades gastrointestinales para personas con sensibilidades, aunque el riesgo para la población en general es bajo.</li>
                    <li class="liInf"><strong>Calidad Del Agua: Pobre</strong> Mayor riesgo de enfermedades como diarrea, infecciones estomacales, y posible exposición a metales pesados u otros contaminantes, lo que la hace inadecuada para beber sin tratamiento adicional.</li>
                </ul>
            </div>
            
            <div class="container2">
                <h2 class="ti">Calidad del Agua Según sus PPM</h2>
                <hr style="border: none; height: 2px; background-color: blue; margin-bottom: 25px;">
                <ul>
                    <li class="liInf"><strong>¿Qué es PPM?</strong> Es la cantidad de materia, sales y/o metales presente en el agua, se mide en (Partes Por Millón).</li>
                    <li class="liInf"><strong>0 - 50 ppm:</strong> Excelente calidad, agua muy pura.</li>
                    <li class="liInf"><strong>51 - 100 ppm:</strong> Buena calidad, adecuada para el consumo diario.</li>
                    <li class="liInf"><strong>101 - 200 ppm:</strong> Calidad moderada, aceptable para consumo.</li>
                    <li class="liInf"><strong>201 - 300 ppm:</strong> Calidad regular, posible sabor y olor perceptibles.</li>
                    <li class="liInf"><strong>301 - 500 ppm:</strong> Calidad pobre, no recomendada para consumo prolongado.</li>
                    <li class="liInf"><strong>501 ppm en adelante:</strong> Calidad inaceptable, puede contener niveles altos de contaminantes.</li>
                </ul>
            </div>
        </div>

        <div class="appliances graficas" style="display: none">
            
          <h1 style="text-align: center">Consumo</h1>
          <div class="graficos">
          <br>
          <select id="selectMesConsumo">
              <option value="" selected disabled>Selecciona un mes</option>
              <option value="02">Enero</option>
              <option value="02">Febrero</option>
              <option value="03">Marzo</option>
              <option value="04">Abril</option>
              <option value="05">Mayo</option>
              <!-- Agrega el resto de los meses aquí -->
          </select>
            <div class="chart-container">
              <canvas id="grafica1"></canvas>
            </div>
          </div>

          <h1 style="text-align: center">Impurezas</h1>
          <div class="graficos">
              <br>
            <select id="selectMesPpm">
              <option value="" selected disabled>Selecciona un mes</option>
              <option value="02">Enero</option>
              <option value="02">Febrero</option>
              <option value="03">Marzo</option>
              <option value="04">Abril</option>
              <option value="05">Mayo</option>
              <!-- Agrega el resto de los meses aquí -->
            </select>
            <div class="chart-container">
              <canvas id="grafica2"></canvas>
            </div>
          </div>

          <h1 style="text-align: center">Temperatura</h1>
          <div class="graficos">
              <br>
            <select id="selectMesTemp">
              <option value="" selected disabled>Selecciona un mes</option>
              <option value="02">Enero</option>
              <option value="02">Febrero</option>
              <option value="03">Marzo</option>
              <option value="04">Abril</option>
              <option value="05">Mayo</option>
              <!-- Agrega el resto de los meses aquí -->
            </select>
            <div class="chart-container">
              <canvas id="grafica3"></canvas>
            </div>
          </div>
          
        </div>

        <div class="appliances cuenta" style="display: none">
          <div class="container2">
            <form id="profileForm" action="/actualizar_perfil" method="post" enctype="multipart/form-data">
                <div class="profile-section">

                  <label for="user-image">Imagen de perfil:</label><br>
                  <img src="assets/image/usr/<?php echo $userInfo['imgUsu']; ?>" alt="Imagen de perfil" class="profile-image"><br>
                  <input type="file" id="user-image" name="user-image" accept="image/*">
                  <img src="assets/image/240px-Blanco.svg.png" alt="Vista previa de la imagen" class="preview-image" id="preview">

                </div>
                <div class="info-section">
                    <div class="input-group">
                        <label for="user-name">Nombre:</label><br>
                        <input type="text" id="user-name" name="user-name" value="<?php echo $userInfo['nombreUsu']; ?>">
                    </div>
                    <div class="input-group">
                        <label for="user-password">Cambiar contraseña:</label><br>
                        <input type="password" id="user-password" name="user-password">
                    </div>
                </div>
                <div class="button-section">
                    <input type="submit" value="Actualizar Perfil">
                </div>
            </form>
          </div>
        </div>
      </section>
    </div>

    <script src="assets/js/dashboard.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#optionsForm').on('submit', function(event) {
                event.preventDefault();
                
                const formData = new FormData(this);
                formData.append('modeThreshold', $('#modeThreshold').is(':checked') ? $('#threshold').val() : 0);
                formData.append('modeTimeLimit', $('#modeTimeLimit').is(':checked') ? $('#timeLimit').val() : null);
                formData.append('modeDefault', $('#modeDefault').is(':checked') ? ($('#defaultSetting').is(':checked') ? 1 : 0) : 0);
                formData.append('modeMan', $('#modeMan').is(':checked') ? ($('#manSetting').is(':checked') ? 2 : 0) : 0);
                
                $.ajax({
                    type: "POST",
                    url: "controlador/controlador.php?accion=configurarValvula",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response);
                        alert("Configuración guardada con éxito!");
                    },
                    error: function (error) {
                        console.error(error);
                        alert("Error al guardar la configuración.");
                    }
                });
            });
                
            $('input[type=number], input[type=time], input[type=checkbox]').on('input change', function() {
                let modeId = $(this).closest('div').find('input[type=radio]').attr('id');
                $('#' + modeId).prop('checked', true);
            });
        });
                
        function autoSelectMode(modeId) {
            $('#' + modeId).prop('checked', true);
        }
    </script>

    <?php
        $consumoDiario = [];
        foreach ($historicos as $dato) {
            $fecha = new DateTime($dato['fecha']);
            $mes = $fecha->format('m'); // Obtener solo el mes
            if (!isset($consumoDiario[$mes])) {
                $consumoDiario[$mes] = [];
            }
            $dia = $fecha->format('j'); // Obtener solo el día del mes
            // Agregamos el consumo al array correspondiente al mes y al día
            $consumoDiario[$mes][$dia] = $dato['consumo'];
        }
    ?>

    <?php
        $ppmDiario = [];
        $tempDiario = [];
        
        foreach ($historicos as $dato) {
            $fecha = new DateTime($dato['fecha']);
            $mes = $fecha->format('m'); // Obtener solo el mes
            $dia = $fecha->format('j'); // Obtener solo el día del mes
            
            if (!isset($ppmDiario[$mes])) {
                $ppmDiario[$mes] = [];
            }
            if (!isset($tempDiario[$mes])) {
                $tempDiario[$mes] = [];
            }
            
            // Agregamos el ppm al array correspondiente al mes y al día
            $ppmDiario[$mes][$dia] = $dato['ppm'];
            // Agregamos la temperatura al array correspondiente al mes y al día
            $tempDiario[$mes][$dia] = $dato['temp'];
        }
    ?>

    <script>
        var consumoDiario = <?php echo json_encode($consumoDiario); ?>;

        const selectMes = document.getElementById('selectMesConsumo');
        selectMes.addEventListener('change', function() {
            const mesSeleccionado = selectMes.value;
            const datosMes = consumoDiario[mesSeleccionado] || {};
            progressChart.data.labels = Object.keys(datosMes);
            progressChart.data.datasets[0].data = Object.values(datosMes);
            progressChart.update();
        });
    
        var datasets = [{
            label: 'Consumo',
            data: [],
            borderColor: getRandomColor(), // Puedes definir una función para obtener colores aleatorios
            backgroundColor: getRandomColor(),
        }];
    
        const ctx = document.getElementById('grafica1').getContext('2d');
        const progressChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Día'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Consumo'
                        }
                    }
                }
            }
        });
    </script>

    <script>
        // grafica 2
        var ppmDiario = <?php echo json_encode($ppmDiario); ?>;

        const selectMesPpm = document.getElementById('selectMesPpm');
        selectMesPpm.addEventListener('change', function() {
            const mesSeleccionado = selectMesPpm.value;
            const datosMes = ppmDiario[mesSeleccionado] || {};
            grafica2.data.labels = Object.keys(datosMes);
            grafica2.data.datasets[0].data = Object.values(datosMes);
            grafica2.update();
        });
        
        var datasetsPpm = [{
            label: 'Partes por millon',
            data: [],
            borderColor: getRandomColor(), // Utiliza la misma función getRandomColor para los colores
            backgroundColor: getRandomColor(), // Puedes ajustar el color según tus preferencias
        }];
        
        const ctx2 = document.getElementById("grafica2").getContext("2d");
        const grafica2 = new Chart(ctx2, {
            type: 'bar', // Puedes cambiar a 'line' si prefieres una gráfica de línea
            data: {
                labels: [],
                datasets: datasetsPpm
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Día'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'PPM'
                        }
                    }
                }
            }
        });


        //grafica 3
        var tempDiario = <?php echo json_encode($tempDiario); ?>;
        const selectMesTemp = document.getElementById('selectMesTemp');
        selectMesTemp.addEventListener('change', function() {
            const mesSeleccionado = selectMesTemp.value;
            const datosMes = tempDiario[mesSeleccionado] || {};
            grafica3.data.labels = Object.keys(datosMes);
            grafica3.data.datasets[0].data = Object.values(datosMes);
            grafica3.update();
        });
        
        var datasetsTemp = [{
            label: 'Temperatura',
            data: [],
            borderColor: getRandomColor(), // Utiliza la misma función getRandomColor para los colores
            backgroundColor: getRandomColor(), // Ajuste el color para diferenciar de otras gráficas
        }];
        
        const ctx3 = document.getElementById('grafica3').getContext('2d');
        const grafica3 = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: [],
                datasets: datasetsTemp
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Día'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Temperatura (°C)'
                        }
                    }
                }
            }
        });


        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
    
    //consejos
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Definir el nivel de consumo en litros
            const consumoActual = 150;
        
            // Determinar el nivel de consumo basado en rangos
            let nivelConsumo;
        
            if (consumoActual < 300) {
                nivelConsumo = 'bajo';
            } else if (consumoActual >= 300 && consumoActual < 680) {
                nivelConsumo = 'moderado';
            } else {
                nivelConsumo = 'alto';
            }
        
            // Mostrar consejos según el nivel de consumo
            function mostrarConsejosAgua() {
                // Ocultar todos los elementos inicialmente
                const todosLosConsejos = document.querySelectorAll('.listaInf2 .liInf2');
                todosLosConsejos.forEach((consejo) => {
                    consejo.style.display = 'none';
                });
        
                // Mostrar solo los consejos que corresponden al nivel de consumo actual
                const consejosRelevantes = document.querySelectorAll(`.listaInf2 .${nivelConsumo}`);
                consejosRelevantes.forEach((consejo) => {
                    consejo.style.display = 'list-item';
                });
            }
        
            // Llamar a la función para mostrar los consejos filtrados
            mostrarConsejosAgua();
        });
    </script>

  </body>
</html>
