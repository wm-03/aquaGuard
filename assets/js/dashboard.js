//manejo de vistas
document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll(".category a");

  links.forEach((link) => {
    link.addEventListener("click", function (event) {
      event.preventDefault();

      // Remover la clase "active" de todos los enlaces
      links.forEach((link) => {
        link.classList.remove("active");
      });

      // Agregar la clase "active" al enlace clicado
      this.classList.add("active");

      // Ocultar todas las categorías
      const appliances = document.querySelectorAll(".appliances");
      appliances.forEach((appliance) => {
        appliance.style.display = "none";
      });

      // Mostrar la categoría correspondiente al enlace clicado
      const category = this.getAttribute("data-category");
      const activeAppliances = document.querySelector(
        `.appliances.${category}`
      );
      activeAppliances.style.display = "block";
    });
  });
});

function getRandomColor() {
  var letters = "0123456789ABCDEF";
  var color = "#";
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

// Manejar la previsualización de la imagen nueva
document.getElementById("user-image").addEventListener("change", function (e) {
  var preview = document.getElementById("preview");
  var file = e.target.files[0];
  var reader = new FileReader();

  reader.onload = function (e) {
    preview.src = e.target.result;
  };

  if (file) {
    reader.readAsDataURL(file);
  }
});

// control de calidad
document.addEventListener("DOMContentLoaded", function() {
    var datos="";
    var datos2="";
    
    function getMetales() {
        $.ajax({
            url: "controlador/controlador.php?accion=getMetales",
            type: "GET",
            success: function(response) {
                datos2 = JSON.parse(response);
                var pCd = datos2.pCd;
                var pZn = datos2.pZn;
                var pCr = datos2.pCr;
                var pPb = datos2.pPb;

                updateMetales(pCd, pZn, pCr, pPb);
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los datos", error, "Estado:", status, "XHR:", xhr);
            }
        });
    }
            
    function fetchAndUpdateWaterQuality() {
        $.ajax({
            url: "controlador/controlador.php?accion=getData",
            type: "GET",
            success: function(response) {
                datos = JSON.parse(response);
                var temperature = parseFloat(datos.temperatura);
                var consumption = parseFloat(datos.consumo);
                var ppm = parseFloat(datos.ppm);

                updateWaterQuality(temperature, consumption, ppm);
            },
            error: function(error) {
                console.error("Error al obtener los datos", error);
            }
        });
    }
        
    function updateWaterQuality(temperature, consumption,ppm) {
        var qualityText = "Calidad Del Agua: Buena";
        var iconClass = "icon-good"; // Esta clase deberá ser definida en tu CSS

        // Condiciones para calidad moderada
        if ((temperature > 30 && temperature <= 45) || (ppm > 150 && ppm <= 300)) {
            qualityText = "Calidad Del Agua: Moderada";
            iconClass = "icon-moderate";
        }
        // Condiciones para calidad pobre
        if (temperature > 45 || ppm > 300) {
            qualityText = "Calidad Del Agua: Pobre";
            iconClass = "icon-poor";
        }
        // Alerta por temperatura alta: peligro por agua caliente
        if (temperature > 60) {
            qualityText = "Precaución Agua Caliente";
            iconClass = "icon-hot"; // Asumimos que tienes un ícono específico para esto
        }
        // // Alerta por consumo excesivo
        if (consumption > 700) {
            qualityText = "Consumo Excesivo";
            iconClass = "icon-excessive"; // Asumimos que tienes un ícono específico para esto
        }
        // Condición excepcionalmente buena
        if (temperature <= 25 && ppm <= 100) {
            qualityText = "Calidad Del Agua: Excelente";
            iconClass = "icon-excellent"; // Asumimos que tienes un ícono específico para esto
        }

        // Actualizar el título y el icono
        document.getElementById('waterQualityTitle').textContent = qualityText;
        var iconElement = document.getElementById('waterQualityIcon');
        iconElement.className = "icon-quality " +iconClass; // Asegúrate de que las clases icon-w y iconClass estén bien definidas
        
        
        // ------------------------- filtar consejos segun la calidad -----------------------------
        
        var calidadActual = qualityText; // Cambia este valor según la calidad real

        function mostrarCalidadAgua() {
            // Selecciona todos los elementos <li> dentro de la lista
            const items = document.querySelectorAll('.listaInf .liInf');
        
            items.forEach((item) => {
                // Verifica si el texto del <li> coincide con la calidad actual
                if (item.innerText.includes(calidadActual)) {
                    item.style.display = 'list-item'; // Mostrar el elemento
                } else {
                    item.style.display = 'none'; // Ocultar el elemento
                }
            });
        }
        
        // Ejecutar la función para filtrar los elementos
        mostrarCalidadAgua();
        
        // ------------------------------------------------------------------------------------------
    }
    
    function updateMetales(pCd, pZn, pCr, pPb) {
        // Convertir valores de string a booleanos, asumiendo que '1' es true y '0' es false
        var detectaPlomo = parseInt(pCd) === 1;
        var detectaCadmio = parseInt(pZn) === 1;
        var detectaArsenico = parseInt(pCr) === 1;
        var detectaCromo = parseInt(pPb) === 1;
        
        function mostrarMetalesPesados() {
            // Obtenemos todos los elementos `<li>`
            const todosLosMetales = document.querySelectorAll('.listaMetales .liInf1');
        
            // Ocultamos todos los metales inicialmente
            todosLosMetales.forEach((metal) => {
                metal.classList.add('hidden');
            });
        
            // Plomo
            if (detectaPlomo) {
                document.querySelector('.listaMetales .plomo').classList.remove('hidden');
            }
        
            // Cadmio
            if (detectaCadmio) {
                document.querySelector('.listaMetales .cadmio').classList.remove('hidden');
            }
        
            // Arsénico
            if (detectaArsenico) {
                document.querySelector('.listaMetales .arsenico').classList.remove('hidden');
            }
        
            // Cromo
            if (detectaCromo) {
                document.querySelector('.listaMetales .cromo').classList.remove('hidden');
            }
        
            // Si no se detectó ningún metal, mostramos "estás a salvo"
            if (!detectaPlomo & !detectaCadmio & !detectaArsenico & !detectaCromo) {
                document.querySelector('.listaMetales .seguro').classList.remove('hidden');
            }
        }
        
        // Llamar a la función para mostrar los metales filtrados
        mostrarMetalesPesados(); // Se llama directamente, no en el DOMContentLoaded
    }
    
    function combinedFunction() {
        updateWaterQuality();
        getMetales();
    }
        
    // Llama a esta función cada cierto tiempo para actualizar los datos
    setInterval(combinedFunction, 2000);  // Actualiza cada 2 segundos
});
        
        
// fecha
const mesesAbreviados = ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic",];

var date = document.getElementById("date");
const fechaActual = new Date();
const dia = fechaActual.getDate();
const mes = mesesAbreviados[fechaActual.getMonth()];
const anio = fechaActual.getFullYear();
const fechaFormateada = dia + " " + mes + " " + anio;
date.innerHTML = fechaFormateada;
      
      
//mostrar datos
document.addEventListener("DOMContentLoaded", function () {
    const c1 = document.getElementById('a');
    const c2 = document.getElementById('b');
    var datos = "";
    
    // Función para calcular el tiempo restante hasta una hora específica
    function contadorTiempo(horaObjetivo) {
        var ahora = new Date();
        var objetivo = new Date();
        objetivo.setHours(parseInt(horaObjetivo.split(':')[0]));
        objetivo.setMinutes(parseInt(horaObjetivo.split(':')[1]));
        objetivo.setSeconds(parseInt(horaObjetivo.split(':')[2]));
    
        var diferencia = objetivo - ahora;
    
        if (diferencia > 0) {
            var segundosRestantes = Math.floor((diferencia / 1000) % 60);
            var minutosRestantes = Math.floor((diferencia / 1000 / 60) % 60);
            var horasRestantes = Math.floor((diferencia / (1000 * 60 * 60)) % 24);
            document.getElementById('contador').innerText = `${horasRestantes}h ${minutosRestantes}m ${segundosRestantes}s`;
        } else {
            document.getElementById('contador').innerText = "¡Tiempo alcanzado!";
        }
    }
    
    // Función para el contador basado en consumo
    function contadorConsumo(consumoActual, umbral) {
        var consumo = parseFloat(consumoActual);
        var limite = parseFloat(umbral);
        var restante = limite - consumo;
    
        if (restante <= 0) {
            document.getElementById('contador').innerText = "Umbral de consumo alcanzado";
        } else {
            document.getElementById('contador').innerText = `Faltan ${restante.toFixed(2)} L para alcanzar el límite`;
        }
    }
    
    // Función para inicializar el contador basado en el modo configurado
    function iniciarContador(datos) {
        if (datos.modo_tiempo_limite && datos.modo_tiempo_limite !== "00:00:00") {
            contadorTiempo(datos.modo_tiempo_limite);  // Inicia el contador de tiempo
            setInterval(function() {
                contadorTiempo(datos.modo_tiempo_limite);
            }, 1000);  // Actualiza cada segundo
        } else if (datos.modo_umbral !== null && datos.modo_umbral !== "0") {
            contadorConsumo(datos.consumo, datos.modo_umbral);  // Inicia el contador de consumo
            setInterval(function() {
                contadorConsumo(datos.consumo, datos.modo_umbral);
            }, 1000);  // Actualiza cada segundo
        }
    }


    function mostrarTemperatura() {
        $.ajax({
            type: 'GET',
            url: 'controlador/controlador.php?accion=getData',
            success: function(response) {
                datos = JSON.parse(response);
                document.getElementById('temperature').innerText = datos.temperatura + "°C";
                document.getElementById('consumo').innerText = datos.consumo;
                document.getElementById('ppm').innerText = datos.ppm;
                document.getElementById('agua').innerText = datos.agua;

                // Actualizar estado de los checkboxes basados en datos recibidos
                if (datos.flujo == 0) {
                  c1.checked = false;
                } else {
                  c1.checked = true;
                }
                if (datos.lecturas == 0) {
                  c2.checked = false;
                } else {
                  c2.checked = true;
                }

                // Determinar el modo de configuración basado en las condiciones específicas
                var modoConfig = determinarModoConfiguracion(datos);
                document.getElementById('modo').innerText = modoConfig;
                
                // Iniciar contador para el tiempo límite
                iniciarContador(datos);
            },
            error: function(error) {
                console.error("Error al obtener datos:", error);
            }
        });
    }
    
    mostrarTemperatura(); // Llama a la función al cargar para asegurar que se cargan los datos

    setInterval(mostrarTemperatura, 2000);
      
    function determinarModoConfiguracion(datos) {
        // Modo predeterminado
        
        if (datos.modo_predeterminado == 1) {
            return 'Configurado por defecto';
        }
        // modo manual
        if (datos.modo_predeterminado == 2) {
            return 'Configuración manual';
        }
        // Modo por umbral de consumo
        if (datos.modo_umbral !== null && datos.modo_umbral !== "0") {
            return 'Configurado por límite de consumo';
        }
        // Modo por tiempo
        if (datos.modo_tiempo_limite && datos.modo_tiempo_limite !== "00:00:00") {
            return 'Configurado por tiempo';
        }

        // Si no se cumplen las condiciones anteriores
        return '--';
    }
    
    // ------------------------------------------------------------------------------------------------------


    const checkboxFlujo = document.getElementById('a');
    const checkboxLecturas = document.getElementById('b');

    c1.addEventListener("change", changeFlujo);
    c2.addEventListener("change", changeLecturas);

    function changeFlujo() {
        changeF(this.checked ? 1 : 0);
    }

    function changeLecturas() {
        changeL(this.checked ? 1 : 0);
    }

    function changeF(valor) {
        $.ajax({
            type: 'POST',
            url: 'controlador/controlador.php?accion=changeFlujo',
            data: {
              valor: valor
            },
            success: function(response) {
                console.error(response);
                window.location.reload();
            },
            error: function(error) {
                console.error(error);
            }
        });
    }

    function changeL(valor) {
        $.ajax({
            type: 'POST',
            url: 'controlador/controlador.php?accion=changeLecturas',
            data: {
                valor: valor
            },
            success: function(response) {
                console.error(response);
                window.location.reload();
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
});


//editar perfil
document.getElementById("profileForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Evita que el formulario se envíe de forma predeterminada
    editarPerfil(); // Llama a tu función personalizada
});

function editarPerfil() {
    var nombre = document.getElementById("user-name").value;
    var pass = document.getElementById("user-password").value;
    // Obtener el archivo de la entrada de imagen
    var imagenInput = document.getElementById("user-image");
    var formData = new FormData();
    if (nombre != "") {
      formData.append("nombre", nombre);
    }
    if (pass != "") {
      formData.append("pass", pass);
    }
    var imagenInput = document.getElementById("user-image");
    var imagen = imagenInput ? imagenInput.files[0] : null; // Verifica si el campo de imagen existe
    if (imagen) {
      formData.append("imagen", imagen); // Asegúrate de que el nombre sea 'imagen'
    }
    $.ajax({
      type: "POST",
      url: "controlador/controlador.php?accion=editarUsuario",
      data: formData,
      contentType: false, // Importante: desactiva la configuración de contenido
      processData: false, // Importante: evita que jQuery procese los datos
      success: function (response) {
        console.log(response);
        window.alert("Pefil actualizado");
        // window.location.reload();
      },
      error: function (error) {
        // Manejar errores de la solicitud AJAX
        console.error(error);
      },
    });
}