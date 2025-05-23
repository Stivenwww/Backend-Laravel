<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\ContenidoProgramatico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContenidoProgramaticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar restricciones de clave foránea para truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncar la tabla
        ContenidoProgramatico::truncate();

        // Reactivar restricciones
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crear contenidos programáticos para Universidad del Cauca
        $this->crearContenidosUniversidadCauca();

        // Crear contenidos programáticos para FUP
        $this->crearContenidosFUP();

        // Crear contenidos programáticos para Colegio Mayor del Cauca
        $this->crearContenidosColegioMayor();

        // Crear contenidos programáticos para Universidad Autónoma del Cauca
        $this->crearContenidosAutonoma();

        // Crear contenidos programáticos para SENA
        $this->crearContenidosSENA();

        // Verificar si hay asignaturas sin contenidos programáticos y crear básicos
        $this->crearContenidosFaltantes();
    }

    /**
     * Crea contenidos programáticos para asignaturas de la Universidad del Cauca
     */
    private function crearContenidosUniversidadCauca(): void
    {
        // Ingeniería Electrónica y Telecomunicaciones (programa_id = 1)
        $this->crearContenidoPorNombre('Circuitos Eléctricos I', 1,
            'Análisis de Circuitos Resistivos',
            'El estudiante será capaz de aplicar las leyes de Kirchhoff para resolver circuitos resistivos y entender los conceptos fundamentales de corriente, voltaje y potencia.',
            'Este tema cubre el análisis de circuitos eléctricos utilizando las leyes de Kirchhoff, teoremas de circuitos y técnicas de simplificación. Se analizan circuitos con resistencias en serie, paralelo y mixtos.'
        );

        $this->crearContenidoPorNombre('Circuitos Eléctricos II', 1,
            'Análisis de Circuitos en Corriente Alterna',
            'El estudiante será capaz de analizar circuitos eléctricos en régimen permanente sinusoidal usando fasores.',
            'Se estudian los circuitos AC, fasores, impedancia, admitancia, potencia en AC, circuitos resonantes y análisis en el dominio de la frecuencia.'
        );

        $this->crearContenidoPorNombre('Electrónica Analógica I', 1,
            'Dispositivos Semiconductores Básicos',
            'El estudiante será capaz de analizar circuitos con diodos y transistores bipolares.',
            'Se estudian los diodos de unión PN, circuitos rectificadores, transistores BJT, polarización y amplificadores básicos con transistores.'
        );

        $this->crearContenidoPorNombre('Electrónica Analógica II', 1,
            'Amplificadores y Transistores FET',
            'El estudiante será capaz de diseñar amplificadores utilizando transistores FET y amplificadores operacionales.',
            'Este tema aborda los transistores de efecto de campo, amplificadores operacionales, filtros activos y aplicaciones lineales de los op-amps.'
        );

        $this->crearContenidoPorNombre('Electrónica Digital I', 1,
            'Sistemas Combinacionales',
            'El estudiante podrá diseñar e implementar circuitos lógicos combinacionales.',
            'Se estudian los sistemas numéricos, álgebra de Boole, compuertas lógicas, minimización de funciones y diseño de circuitos combinacionales.'
        );

        $this->crearContenidoPorNombre('Electrónica Digital II', 1,
            'Sistemas Secuenciales',
            'El estudiante podrá diseñar circuitos secuenciales síncronos y asíncronos.',
            'Este tema cubre flip-flops, latches, contadores, registros, máquinas de estado finito y diseño de sistemas secuenciales.'
        );

        $this->crearContenidoPorNombre('Microprocesadores', 1,
            'Arquitectura y Programación de Microprocesadores',
            'El estudiante podrá programar microprocesadores y entender su arquitectura interna.',
            'Se estudia la arquitectura de microprocesadores, conjunto de instrucciones, programación en ensamblador, interfaces de entrada/salida y sistemas mínimos.'
        );

        $this->crearContenidoPorNombre('Microcontroladores', 1,
            'Sistemas Embebidos con Microcontroladores',
            'El estudiante podrá diseñar sistemas embebidos utilizando microcontroladores.',
            'Este tema aborda la programación de microcontroladores, periféricos internos, temporizadores, interrupciones, comunicaciones seriales y aplicaciones prácticas.'
        );

        // Ingeniería Civil (programa_id = 2)
        $this->crearContenidoPorNombre('Topografía I', 2,
            'Fundamentos de Topografía',
            'El estudiante podrá realizar mediciones básicas de distancias y ángulos en el terreno.',
            'Se estudian los fundamentos de topografía, instrumentos básicos, medición de distancias, ángulos horizontales y verticales, y cálculos topográficos elementales.'
        );

        $this->crearContenidoPorNombre('Topografía II', 2,
            'Levantamientos Topográficos',
            'El estudiante podrá realizar levantamientos topográficos completos y elaborar planos.',
            'Este tema cubre métodos de levantamiento, poligonación, triangulación, nivelación, curvas de nivel, perfiles longitudinales y transversales.'
        );

        $this->crearContenidoPorNombre('Resistencia de Materiales I', 2,
            'Esfuerzo y Deformación Axial',
            'El estudiante podrá calcular esfuerzos y deformaciones en elementos sometidos a carga axial.',
            'Se estudian los conceptos de esfuerzo, deformación, propiedades mecánicas, carga axial, concentración de esfuerzos y elementos estaticamente indeterminados.'
        );

        $this->crearContenidoPorNombre('Resistencia de Materiales II', 2,
            'Flexión y Torsión',
            'El estudiante podrá analizar elementos estructurales sometidos a flexión y torsión.',
            'Este tema aborda la flexión pura, esfuerzos de flexión, deflexión de vigas, torsión en ejes circulares y combinación de cargas.'
        );

        // Ingeniería Ambiental (programa_id = 3)
        $this->crearContenidoPorNombre('Química Ambiental', 3,
            'Procesos Químicos en el Medio Ambiente',
            'El estudiante podrá comprender los procesos químicos que ocurren en los diferentes compartimentos ambientales.',
            'Se estudian las reacciones químicas en la atmósfera, hidrosfera y geosfera, ciclos biogeoquímicos, contaminantes químicos y su transformación.'
        );

        $this->crearContenidoPorNombre('Evaluación de Impacto Ambiental', 3,
            'Metodologías de Evaluación Ambiental',
            'El estudiante podrá evaluar impactos ambientales de proyectos y proponer medidas de mitigación.',
            'Este tema cubre las metodologías de evaluación, identificación de impactos, matrices de evaluación, estudios de línea base y planes de manejo ambiental.'
        );

        // Ingeniería de Sistemas (programa_id = 4)
        $this->crearContenidoPorNombre('Introducción a la Programación', 4,
            'Fundamentos de Algoritmos',
            'El estudiante podrá diseñar algoritmos básicos y representarlos mediante pseudocódigo y diagramas de flujo.',
            'Este tema introduce los conceptos de algoritmo, variables, tipos de datos, operadores y estructuras de control básicas.'
        );

        $this->crearContenidoPorNombre('Programación I', 4,
            'Programación Estructurada',
            'El estudiante podrá desarrollar programas utilizando un lenguaje de programación de alto nivel.',
            'Se estudian las estructuras de control, arreglos, funciones, recursividad y manejo de archivos en un lenguaje de programación estructurado.'
        );

        $this->crearContenidoPorNombre('Programación II', 4,
            'Estructuras de Datos y Algoritmos',
            'El estudiante podrá implementar estructuras de datos y algoritmos eficientes.',
            'Este tema aborda listas enlazadas, pilas, colas, árboles, algoritmos de ordenamiento y búsqueda, y análisis de complejidad.'
        );

        $this->crearContenidoPorNombre('Estructuras de Datos', 4,
            'Estructuras de Datos Avanzadas',
            'El estudiante podrá implementar y utilizar estructuras de datos complejas.',
            'Se estudian árboles balanceados, grafos, tablas hash, algoritmos sobre grafos y aplicaciones prácticas de las estructuras de datos.'
        );

        // Ingeniería en Automática Industrial (programa_id = 5)
        $this->crearContenidoPorNombre('Control Clásico', 5,
            'Teoría de Control Clásico',
            'El estudiante podrá diseñar controladores utilizando técnicas de control clásico.',
            'Se estudian las funciones de transferencia, análisis de estabilidad, lugar geométrico de las raíces, respuesta en frecuencia y diseño de controladores PID.'
        );

        $this->crearContenidoPorNombre('PLC y Automatización', 5,
            'Programación de Controladores Lógicos Programables',
            'El estudiante podrá programar PLCs y diseñar sistemas de automatización industrial.',
            'Este tema cubre la arquitectura de PLCs, lenguajes de programación, sensores, actuadores, redes industriales y aplicaciones de automatización.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas de la FUP
     */
    private function crearContenidosFUP(): void
    {
        // Ing. de Sistemas (programa_id = 6)
        $this->crearContenidoPorNombre('Introducción a la Programación', 6,
            'Fundamentos de Programación',
            'El estudiante podrá desarrollar algoritmos básicos y traducirlos a un lenguaje de programación.',
            'Este tema introduce los conceptos fundamentales de la programación, como variables, tipos de datos, estructuras de control y funciones.'
        );

        $this->crearContenidoPorNombre('Programación I', 6,
            'Programación Estructurada',
            'El estudiante podrá desarrollar programas utilizando el paradigma de programación estructurada.',
            'Se estudian las estructuras de control, arreglos, funciones, modularización y buenas prácticas de programación.'
        );

        $this->crearContenidoPorNombre('Base de Datos I', 6,
            'Fundamentos de Bases de Datos',
            'El estudiante podrá diseñar bases de datos relacionales básicas.',
            'Este tema cubre el modelo entidad-relación, modelo relacional, normalización básica y lenguaje SQL para consultas simples.'
        );

        $this->crearContenidoPorNombre('Base de Datos II', 6,
            'Bases de Datos Avanzadas',
            'El estudiante podrá diseñar y administrar bases de datos complejas.',
            'Se estudian técnicas avanzadas de SQL, procedimientos almacenados, triggers, optimización de consultas y administración de bases de datos.'
        );

        // Ing. Industrial (programa_id = 7)
        $this->crearContenidoPorNombre('Investigación de Operaciones I', 7,
            'Programación Lineal',
            'El estudiante podrá formular y resolver problemas de optimización mediante programación lineal.',
            'Este tema cubre la formulación de modelos, método simplex, dualidad y análisis de sensibilidad en programación lineal.'
        );

        $this->crearContenidoPorNombre('Investigación de Operaciones II', 7,
            'Programación Entera y Teoría de Colas',
            'El estudiante podrá resolver problemas de optimización discreta y analizar sistemas de colas.',
            'Se estudian técnicas de programación entera, teoría de colas, simulación y modelos de inventarios.'
        );

        // Arquitectura (programa_id = 8)
        $this->crearContenidoPorNombre('Taller de Diseño I', 8,
            'Fundamentos del Diseño Arquitectónico',
            'El estudiante podrá aplicar principios básicos de diseño en ejercicios arquitectónicos simples.',
            'Este tema introduce los conceptos de espacio, forma, función, escala y proporción en el diseño arquitectónico.'
        );

        $this->crearContenidoPorNombre('Historia de la Arquitectura I', 8,
            'Arquitectura Antigua y Clásica',
            'El estudiante podrá identificar las características de la arquitectura antigua y clásica.',
            'Se estudia la evolución arquitectónica desde las civilizaciones antiguas hasta el período clásico grecorromano.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas del Colegio Mayor del Cauca
     */
    private function crearContenidosColegioMayor(): void
    {
        // Ing. Informática (programa_id = 9)
        $this->crearContenidoPorNombre('Introducción a la Programación', 9,
            'Algoritmos y Lógica de Programación',
            'El estudiante podrá desarrollar algoritmos y aplicar lógica de programación.',
            'Este tema aborda los fundamentos algorítmicos, pseudocódigo, diagramas de flujo y estructuras de control básicas.'
        );

        $this->crearContenidoPorNombre('Base de Datos I', 9,
            'Diseño de Bases de Datos Relacionales',
            'El estudiante podrá diseñar bases de datos utilizando el modelo relacional.',
            'Se estudian el modelado entidad-relación, normalización, diseño lógico y físico de bases de datos.'
        );

        // Ing. Multimedia (programa_id = 10)
        $this->crearContenidoPorNombre('Fundamentos de Multimedia', 10,
            'Conceptos Básicos de Multimedia',
            'El estudiante podrá comprender los fundamentos teóricos de los sistemas multimedia.',
            'Este tema cubre los tipos de medios, digitalización, compresión, formatos y estándares multimedia.'
        );

        $this->crearContenidoPorNombre('Diseño Gráfico Digital', 10,
            'Principios de Diseño Gráfico',
            'El estudiante podrá aplicar principios de diseño gráfico en medios digitales.',
            'Se estudian los elementos del diseño, teoría del color, tipografía, composición y herramientas de diseño digital.'
        );

        // Tecnología en Desarrollo de Software (programa_id = 11)
        $this->crearContenidoPorNombre('Lógica de Programación', 11,
            'Fundamentos de Lógica Computacional',
            'El aprendiz será capaz de aplicar lógica computacional para resolver problemas.',
            'Este tema introduce los conceptos de algoritmo, estructuras de datos básicas y técnicas de resolución de problemas.'
        );

        $this->crearContenidoPorNombre('Programación I', 11,
            'Programación Básica',
            'El aprendiz podrá desarrollar programas básicos utilizando un lenguaje de programación.',
            'Se estudian variables, operadores, estructuras de control, arreglos y funciones en un lenguaje de alto nivel.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas de la Universidad Autónoma del Cauca
     */
    private function crearContenidosAutonoma(): void
    {
        // Ing. de Software y Computación (programa_id = 12)
        $this->crearContenidoPorNombre('Matemáticas I', 12,
            'Cálculo Diferencial',
            'El estudiante podrá comprender y aplicar los conceptos del cálculo diferencial.',
            'Este tema cubre límites, continuidad, derivadas, aplicaciones de derivadas y optimización.'
        );

        $this->crearContenidoPorNombre('Física I', 12,
            'Mecánica Clásica',
            'El estudiante podrá aplicar las leyes de la mecánica clásica.',
            'Se estudian cinemática, dinámica, trabajo, energía, momentum y movimiento circular.'
        );

        $this->crearContenidoPorNombre('Programación I', 12,
            'Introducción a la Programación',
            'El estudiante podrá desarrollar programas básicos en un lenguaje orientado a objetos.',
            'Este tema introduce variables, tipos de datos, estructuras de control, arreglos y métodos.'
        );

        $this->crearContenidoPorNombre('Fundamentos de Ingeniería de Software', 12,
            'Introducción a la Ingeniería de Software',
            'El estudiante podrá comprender los conceptos fundamentales de la ingeniería de software.',
            'Se estudian procesos de desarrollo, ciclos de vida, metodologías y principios de la ingeniería de software.'
        );

        // Ing. Electrónica (programa_id = 13)
        $this->crearContenidoPorNombre('Circuitos Eléctricos I', 13,
            'Análisis de Circuitos DC',
            'El estudiante podrá analizar circuitos eléctricos de corriente continua.',
            'Este tema cubre las leyes de Kirchhoff, teoremas de circuitos, análisis nodal y de mallas.'
        );

        $this->crearContenidoPorNombre('Electrónica Analógica I', 13,
            'Dispositivos Semiconductores',
            'El estudiante podrá analizar circuitos con dispositivos semiconductores básicos.',
            'Se estudian diodos, transistores BJT, amplificadores y aplicaciones básicas.'
        );

        // Ing. Civil (programa_id = 14)
        $this->crearContenidoPorNombre('Estática', 14,
            'Equilibrio de Partículas y Cuerpos Rígidos',
            'El estudiante podrá analizar sistemas en equilibrio estático.',
            'Este tema cubre fuerzas, momentos, equilibrio, diagramas de cuerpo libre y análisis de estructuras simples.'
        );

        $this->crearContenidoPorNombre('Topografía I', 14,
            'Fundamentos de Topografía',
            'El estudiante podrá realizar mediciones topográficas básicas.',
            'Se estudian instrumentos topográficos, medición de distancias, ángulos y elaboración de planos básicos.'
        );

        // Ing. Energética (programa_id = 15)
        $this->crearContenidoPorNombre('Termodinámica I', 15,
            'Primera Ley de la Termodinámica',
            'El estudiante podrá aplicar la primera ley de la termodinámica.',
            'Este tema cubre conceptos básicos, sistemas, propiedades, procesos y la primera ley para sistemas cerrados y abiertos.'
        );

        $this->crearContenidoPorNombre('Energías Renovables I', 15,
            'Fundamentos de Energías Renovables',
            'El estudiante podrá comprender los principios de las energías renovables.',
            'Se estudian tipos de energías renovables, potencial energético, tecnologías y aspectos económicos.'
        );

        // Ing. Ambiental y de Saneamiento (programa_id = 16)
        $this->crearContenidoPorNombre('Química Ambiental', 16,
            'Procesos Químicos Ambientales',
            'El estudiante podrá comprender los procesos químicos en el medio ambiente.',
            'Este tema aborda reacciones químicas ambientales, contaminantes, transformaciones y ciclos biogeoquímicos.'
        );

        $this->crearContenidoPorNombre('Contaminación del Agua', 16,
            'Fuentes y Control de Contaminación Hídrica',
            'El estudiante podrá identificar fuentes de contaminación del agua y proponer medidas de control.',
            'Se estudian tipos de contaminantes hídricos, fuentes puntuales y difusas, efectos ambientales y tecnologías de tratamiento.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas del SENA
     */
    private function crearContenidosSENA(): void
    {
        // Tecnólogo en Análisis y Desarrollo de Software (programa_id = 17)
        $this->crearContenidoPorNombre('Analizar los Requerimientos del Cliente', 17,
            'Análisis de Requerimientos de Software',
            'El aprendiz será capaz de identificar, documentar y gestionar requisitos de software.',
            'Esta competencia aborda técnicas de elicitación, análisis, especificación y validación de requerimientos funcionales y no funcionales.'
        );

        $this->crearContenidoPorNombre('Construir el Sistema que Cumpla con los Requerimientos', 17,
            'Desarrollo de Sistemas de Información',
            'El aprendiz será capaz de desarrollar sistemas de información que satisfagan los requerimientos.',
            'Se estudian metodologías de desarrollo, patrones de diseño, implementación y pruebas de software.'
        );

        // Técnico en Sistemas (programa_id = 18)
        $this->crearContenidoPorNombre('Ensamblar y Desensamblar los Componentes Hardware', 18,
            'Mantenimiento de Hardware',
            'El aprendiz será capaz de ensamblar, desensamblar y mantener componentes de hardware.',
            'Esta competencia cubre arquitectura de computadores, componentes internos, compatibilidad y técnicas de ensamble.'
        );

        $this->crearContenidoPorNombre('Instalar el Sistema Operativo y Software de Aplicación', 18,
            'Instalación y Configuración de Software',
            'El aprendiz podrá instalar y configurar sistemas operativos y aplicaciones.',
            'Se estudian tipos de sistemas operativos, procesos de instalación, configuración básica y resolución de problemas.'
        );

        // Tecnólogo en Gestión de Redes de Datos (programa_id = 19)
        $this->crearContenidoPorNombre('Diseñar la Red de Acuerdo con las Necesidades', 19,
            'Diseño de Redes de Datos',
            'El aprendiz será capaz de diseñar topologías de red según requerimientos organizacionales.',
            'Esta competencia aborda topologías, protocolos, direccionamiento IP, cableado estructurado y equipos de conectividad.'
        );

        $this->crearContenidoPorNombre('Configurar los Dispositivos de Red de Acuerdo con los Estándares', 19,
            'Configuración de Equipos de Red',
            'El aprendiz podrá configurar dispositivos de red siguiendo estándares internacionales.',
            'Se estudian switches, routers, puntos de acceso, VLANs, enrutamiento y protocolos de red.'
        );

        // Tecnólogo en Producción Multimedia (programa_id = 20)
        $this->crearContenidoPorNombre('Planificar la Producción Multimedia', 20,
            'Gestión de Proyectos Multimedia',
            'El aprendiz será capaz de planificar proyectos de producción multimedia.',
            'Esta competencia cubre metodologías de gestión, cronogramas, recursos, presupuestos y equipos multimedia.'
        );

        $this->crearContenidoPorNombre('Diseñar Recursos Multimedia', 20,
            'Diseño de Contenidos Multimedia',
            'El aprendiz podrá diseñar recursos multimedia aplicando principios de diseño.',
            'Se estudian elementos de diseño, color, tipografía, composición y herramientas de diseño multimedia.'
        );

        // Técnico en Programación de Software (programa_id = 21)
        $this->crearContenidoPorNombre('Construir Algoritmos Aplicando Metodologías de Desarrollo', 21,
            'Desarrollo de Algoritmos',
            'El aprendiz será capaz de construir algoritmos eficientes para resolver problemas.',
            'Esta competencia aborda lógica de programación, estructuras de datos, algoritmos y metodologías de desarrollo.'
        );

        $this->crearContenidoPorNombre('Codificar el Módulo de Software', 21,
            'Programación de Módulos de Software',
            'El aprendiz podrá codificar módulos de software siguiendo estándares de calidad.',
            'Se estudian lenguajes de programación, buenas prácticas, documentación de código y control de versiones.'
        );

        // Tecnólogo en Implementación de Infraestructura TIC (programa_id = 22)
        $this->crearContenidoPorNombre('Implementar la Estructura de la Red', 22,
            'Implementación de Infraestructura de Red',
            'El aprendiz será capaz de implementar infraestructuras de red según especificaciones.',
            'Esta competencia cubre instalación de cableado, configuración de equipos, protocolos y servicios de red.'
        );

        $this->crearContenidoPorNombre('Configurar el Hardware de Acuerdo con el Análisis de Requerimientos', 22,
            'Configuración de Hardware',
            'El aprendiz podrá configurar hardware según análisis de requerimientos.',
            'Se estudian servidores, estaciones de trabajo, dispositivos móviles, compatibilidad y optimización.'
        );

        // Tecnólogo en Gestión de la Seguridad y Salud en el Trabajo (programa_id = 23)
        $this->crearContenidoPorNombre('Identificar Peligros y Evaluar Riesgos', 23,
            'Identificación y Evaluación de Riesgos Laborales',
            'El aprendiz será capaz de identificar peligros y evaluar riesgos en el ambiente laboral.',
            'Esta competencia aborda metodologías de identificación, matrices de riesgo, valoración y priorización de riesgos.'
        );

        $this->crearContenidoPorNombre('Planificar Acciones de Promoción y Prevención', 23,
            'Planificación de Programas de SST',
            'El aprendiz podrá planificar programas de promoción y prevención en seguridad y salud.',
            'Se estudian planes de acción, cronogramas, recursos, indicadores y seguimiento de programas de SST.'
        );

        // Tecnólogo en Gestión de Proyectos de Desarrollo de Software (programa_id = 24)
        $this->crearContenidoPorNombre('Planificar el Proyecto de Software', 24,
            'Planificación de Proyectos de Software',
            'El aprendiz será capaz de planificar proyectos de desarrollo de software.',
            'Esta competencia cubre metodologías de gestión, cronogramas, recursos, riesgos y comunicaciones del proyecto.'
        );

        $this->crearContenidoPorNombre('Coordinar las Actividades del Equipo de Desarrollo', 24,
            'Coordinación de Equipos de Desarrollo',
            'El aprendiz podrá coordinar equipos de desarrollo aplicando técnicas de liderazgo.',
            'Se estudian técnicas de liderazgo, comunicación, resolución de conflictos y metodologías ágiles.'
        );
    }

    /**
     * Verifica y crea contenidos programáticos para asignaturas sin contenidos
     */
    private function crearContenidosFaltantes(): void
    {
        // Obtener todas las asignaturas
        $asignaturas = Asignatura::all();

        foreach ($asignaturas as $asignatura) {
            // Verificar si la asignatura ya tiene contenido programático
            $tieneContenido = ContenidoProgramatico::where('asignatura_id', $asignatura->id_asignatura)->exists();

            if (!$tieneContenido) {
                // Crear un contenido programático básico
                ContenidoProgramatico::create([
                    'asignatura_id' => $asignatura->id_asignatura,
                    'tema' => 'Contenido General de ' . $asignatura->nombre,
                    'resultados_aprendizaje' => 'El estudiante podrá comprender y aplicar los conceptos fundamentales de ' . $asignatura->nombre . '.',
                    'descripcion' => 'Este curso aborda los fundamentos teóricos y prácticos de ' . $asignatura->nombre . ', preparando al estudiante para aplicar estos conocimientos en situaciones reales.'
                ]);
            }
        }
    }

    /**
     * Crea contenido programático buscando la asignatura por nombre
     */
    private function crearContenidoPorNombre($nombreAsignatura, $programaId, $tema, $resultadosAprendizaje, $descripcion): void
    {
        // Buscar la asignatura por nombre y programa_id
        $asignatura = Asignatura::where('nombre', 'LIKE', '%' . $nombreAsignatura . '%')
                              ->where('programa_id', $programaId)
                              ->first();

        if ($asignatura) {
            // Crear contenido programático
            ContenidoProgramatico::create([
                'asignatura_id' => $asignatura->id_asignatura,
                'tema' => $tema,
                'resultados_aprendizaje' => $resultadosAprendizaje,
                'descripcion' => $descripcion
            ]);
        }
    }
}
