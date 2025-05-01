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
        $this->crearContenidoPorNombre('Circuitos Eléctricos', 1,
            'Análisis de Circuitos Resistivos',
            'El estudiante será capaz de aplicar las leyes de Kirchhoff para resolver circuitos resistivos y entender los conceptos fundamentales de corriente, voltaje y potencia.',
            'Este tema cubre el análisis de circuitos eléctricos utilizando las leyes de Kirchhoff, teoremas de circuitos y técnicas de simplificación. Se analizan circuitos con resistencias, capacitores e inductores en régimen transitorio y permanente.'
        );

        $this->crearContenidoPorNombre('Electrónica Analógica', 1,
            'Dispositivos Semiconductores',
            'El estudiante será capaz de analizar circuitos con diodos, transistores y amplificadores operacionales.',
            'Se estudian los dispositivos semiconductores como diodos, transistores BJT, FET y amplificadores operacionales. Se analizan circuitos básicos y aplicaciones prácticas de estos componentes.'
        );

        $this->crearContenidoPorNombre('Electrónica Digital', 1,
            'Sistemas Combinacionales y Secuenciales',
            'El estudiante podrá diseñar e implementar circuitos lógicos combinacionales y secuenciales.',
            'Este tema aborda los sistemas numéricos, álgebra de Boole, compuertas lógicas, minimización de funciones, circuitos combinacionales y secuenciales como flip-flops, contadores y registros.'
        );

        $this->crearContenidoPorNombre('Microcontroladores', 1,
            'Arquitectura y Programación de Microcontroladores',
            'El estudiante podrá programar microcontroladores y diseñar sistemas embebidos básicos.',
            'Se estudia la arquitectura interna de los microcontroladores, programación en lenguaje C y ensamblador, periféricos, interrupciones y protocolos de comunicación como UART, SPI e I2C.'
        );

        // Ingeniería Civil (programa_id = 2)
        $this->crearContenidoPorNombre('Topografía', 2,
            'Métodos de Levantamiento Topográfico',
            'El estudiante podrá realizar levantamientos topográficos utilizando diferentes instrumentos y técnicas.',
            'Este tema cubre los métodos de medición de distancias, ángulos y elevaciones, así como el uso de equipos como estación total, nivel y GPS. Se estudian también los métodos de representación del terreno.'
        );

        $this->crearContenidoPorNombre('Mecánica de Materiales', 2,
            'Esfuerzo y Deformación',
            'El estudiante podrá calcular esfuerzos y deformaciones en elementos estructurales sometidos a diferentes cargas.',
            'Se estudian los conceptos de esfuerzo, deformación, propiedades mecánicas de los materiales, carga axial, torsión, flexión y deflexión de vigas, así como esfuerzos combinados.'
        );

        // Ingeniería de Sistemas (programa_id = 3)
        $this->crearContenidoPorNombre('Fundamentos de Programación', 3,
            'Algoritmos y Estructuras de Control',
            'El estudiante podrá diseñar algoritmos y codificarlos utilizando estructuras de control en un lenguaje de programación.',
            'Este tema aborda los conceptos fundamentales de la programación, incluyendo variables, tipos de datos, operadores, estructuras de control condicionales y repetitivas, así como la modularización mediante funciones.'
        );

        $this->crearContenidoPorNombre('Estructuras de Datos', 3,
            'Estructuras de Datos Lineales y No Lineales',
            'El estudiante podrá implementar y utilizar estructuras de datos eficientes para diferentes problemas computacionales.',
            'Se estudian las estructuras de datos como arreglos, listas enlazadas, pilas, colas, árboles, grafos y tablas hash, así como sus algoritmos de manipulación y aplicaciones prácticas.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas de la FUP
     */
    private function crearContenidosFUP(): void
    {
        // Ing. de Sistemas (programa_id = 6)
        $this->crearContenidoPorNombre('Programación I', 6,
            'Introducción a la Programación',
            'El estudiante podrá desarrollar algoritmos básicos y traducirlos a un lenguaje de programación.',
            'Este tema introduce los conceptos fundamentales de la programación, como variables, tipos de datos, estructuras de control, arreglos y funciones utilizando un lenguaje de alto nivel.'
        );

        $this->crearContenidoPorNombre('Bases de Datos', 6,
            'Diseño y Modelado de Bases de Datos Relacionales',
            'El estudiante podrá diseñar bases de datos relacionales aplicando normalización y utilizar SQL para consultas.',
            'Se estudian los conceptos de modelado de datos, modelo entidad-relación, normalización, lenguaje SQL para definición, manipulación y consulta de datos, y transacciones.'
        );

        // Ing. Industrial (programa_id = 7)
        $this->crearContenidoPorNombre('Investigación de Operaciones', 7,
            'Programación Lineal',
            'El estudiante podrá formular y resolver problemas de optimización mediante programación lineal.',
            'Este tema cubre la formulación de modelos de programación lineal, método simplex, dualidad, análisis de sensibilidad y aplicaciones en problemas de transporte, asignación y redes.'
        );

        // Arquitectura (programa_id = 8)
        $this->crearContenidoPorNombre('Diseño Arquitectónico', 8,
            'Principios del Diseño Arquitectónico',
            'El estudiante podrá aplicar principios fundamentales de diseño en proyectos arquitectónicos.',
            'Se estudian los conceptos de forma, espacio, escala, proporción, ritmo, jerarquía y organización espacial, así como metodologías de diseño y representación arquitectónica.'
        );

        $this->crearContenidoPorNombre('Historia de la Arquitectura', 8,
            'Arquitectura Clásica y Renacentista',
            'El estudiante podrá identificar y analizar las características de la arquitectura clásica y renacentista.',
            'Este tema aborda la evolución de la arquitectura desde la antigüedad hasta el Renacimiento, estudiando sus características estilísticas, técnicas constructivas, contexto histórico y cultural.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas del Colegio Mayor del Cauca
     */
    private function crearContenidosColegioMayor(): void
    {
        // Ing. Informática (programa_id = 9)
        $this->crearContenidoPorNombre('Fundamentos de Programación', 9,
            'Paradigmas de Programación',
            'El estudiante podrá identificar y aplicar diferentes paradigmas de programación en la solución de problemas.',
            'Este tema cubre los paradigmas de programación estructurada, orientada a objetos, funcional y lógica, sus características, ventajas y aplicaciones prácticas.'
        );

        $this->crearContenidoPorNombre('Bases de Datos', 9,
            'Sistemas de Gestión de Bases de Datos',
            'El estudiante podrá diseñar, implementar y administrar bases de datos utilizando un SGBD.',
            'Se estudian los sistemas de gestión de bases de datos, arquitectura, diseño lógico y físico, normalización, SQL avanzado, optimización de consultas y administración de bases de datos.'
        );

        // Ing. Electrónica (programa_id = 10)
        $this->crearContenidoPorNombre('Circuitos Eléctricos', 10,
            'Análisis de Circuitos en Corriente Alterna',
            'El estudiante podrá analizar circuitos eléctricos en régimen permanente sinusoidal.',
            'Este tema aborda el análisis de circuitos en el dominio de la frecuencia, fasores, impedancia, admitancia, potencia en AC, circuitos resonantes y filtros pasivos.'
        );

        // Tecnología en Desarrollo de Software (programa_id = 11)
        $this->crearContenidoPorNombre('Análisis de Requisitos', 11,
            'Ingeniería de Requisitos',
            'El estudiante podrá identificar, analizar, especificar y validar requisitos de software siguiendo metodologías estándar.',
            'Se estudian las técnicas de elicitación, análisis, especificación, validación y gestión de requisitos, así como la documentación mediante historias de usuario y casos de uso.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas de la Universidad Autónoma del Cauca
     */
    private function crearContenidosAutonoma(): void
    {
        // Ing. de Software (programa_id = 12)
        $this->crearContenidoPorNombre('Matemáticas I', 12,
            'Cálculo Diferencial',
            'El estudiante podrá comprender y aplicar los conceptos y técnicas del cálculo diferencial en una variable.',
            'Este tema cubre los conceptos de límites, continuidad, derivadas y sus aplicaciones en problemas de optimización, trazado de curvas y aproximaciones lineales.'
        );

        $this->crearContenidoPorNombre('Física I', 12,
            'Mecánica Newtoniana',
            'El estudiante podrá aplicar las leyes de Newton para resolver problemas de movimiento de partículas y cuerpos rígidos.',
            'Se estudian los conceptos de cinemática, dinámica de partículas, trabajo y energía, conservación del momento lineal y angular, y movimiento circular.'
        );

        $this->crearContenidoPorNombre('Programación I', 12,
            'Fundamentos de Algoritmos y Programación',
            'El estudiante podrá desarrollar algoritmos y programas básicos utilizando un lenguaje de programación.',
            'Este tema introduce los conceptos de algoritmos, variables, tipos de datos, operadores, estructuras de control, arreglos y funciones en un lenguaje orientado a objetos.'
        );

        $this->crearContenidoPorNombre('Comunicación Oral y Escrita', 12,
            'Técnicas de Comunicación Efectiva',
            'El estudiante podrá comunicarse efectivamente de forma oral y escrita en contextos académicos y profesionales.',
            'Se estudian las técnicas de redacción, argumentación, exposición oral, comprensión lectora y producción de textos académicos y técnicos.'
        );

        $this->crearContenidoPorNombre('Fundamentos de Ingeniería de Software', 12,
            'Introducción a la Ingeniería de Software',
            'El estudiante podrá comprender los conceptos fundamentales y procesos de la ingeniería de software.',
            'Este tema aborda la definición, características y áreas de la ingeniería de software, ciclos de vida, procesos de desarrollo, roles y metodologías tradicionales y ágiles.'
        );

        $this->crearContenidoPorNombre('Matemáticas II', 12,
            'Cálculo Integral',
            'El estudiante podrá comprender y aplicar los conceptos y técnicas del cálculo integral en una variable.',
            'Se estudian los conceptos de antiderivadas, integración definida e indefinida, métodos de integración, aplicaciones geométricas y físicas de la integral, y series numéricas.'
        );

        $this->crearContenidoPorNombre('Física II', 12,
            'Electricidad y Magnetismo',
            'El estudiante podrá comprender y aplicar los conceptos de electrostática, electrodinámica y magnetismo.',
            'Este tema cubre los campos eléctricos, ley de Coulomb, potencial eléctrico, capacitancia, corriente, resistencia, circuitos DC, campos magnéticos y ley de Faraday.'
        );

        $this->crearContenidoPorNombre('Programación II', 12,
            'Programación Orientada a Objetos',
            'El estudiante podrá aplicar el paradigma de programación orientada a objetos en el desarrollo de software.',
            'Se estudian los conceptos de clases, objetos, herencia, polimorfismo, encapsulamiento, abstracción, interfaces y manejo de excepciones en un lenguaje orientado a objetos.'
        );

        // Continuar con más asignaturas de Ing. de Software...
        $this->crearContenidoPorNombre('Estructuras de Datos', 12,
            'Implementación y Análisis de Estructuras de Datos',
            'El estudiante podrá implementar y analizar diferentes estructuras de datos y sus algoritmos asociados.',
            'Este tema aborda la implementación, análisis de complejidad y aplicaciones de estructuras de datos como listas, pilas, colas, árboles, tablas hash y grafos.'
        );

        $this->crearContenidoPorNombre('Bases de Datos I', 12,
            'Modelado y Diseño de Bases de Datos',
            'El estudiante podrá diseñar e implementar modelos de datos utilizando el enfoque relacional.',
            'Se estudian los conceptos de modelado entidad-relación, modelo relacional, normalización, álgebra relacional y lenguaje SQL para definición y manipulación de datos.'
        );

        $this->crearContenidoPorNombre('Ingeniería de Requisitos', 12,
            'Técnicas de Elicitación y Análisis de Requisitos',
            'El estudiante podrá aplicar técnicas para identificar, especificar y validar requisitos de software.',
            'Este tema cubre las técnicas de elicitación de requisitos, análisis, especificación, validación, priorización y gestión de cambios de requisitos.'
        );

        $this->crearContenidoPorNombre('Desarrollo Web', 12,
            'Desarrollo de Aplicaciones Web',
            'El estudiante podrá diseñar e implementar aplicaciones web utilizando tecnologías front-end y back-end.',
            'Se estudian tecnologías como HTML, CSS, JavaScript, frameworks front-end, lenguajes back-end, servicios web, APIs RESTful y despliegue de aplicaciones.'
        );

        $this->crearContenidoPorNombre('Inteligencia Artificial', 12,
            'Fundamentos de Inteligencia Artificial y Aprendizaje Automático',
            'El estudiante podrá aplicar técnicas de inteligencia artificial y aprendizaje automático en la solución de problemas.',
            'Este tema aborda búsqueda heurística, representación del conocimiento, razonamiento, aprendizaje supervisado y no supervisado, redes neuronales y procesamiento de lenguaje natural.'
        );

        $this->crearContenidoPorNombre('Arquitectura de Software', 12,
            'Diseño y Evaluación de Arquitecturas de Software',
            'El estudiante podrá diseñar, documentar y evaluar arquitecturas de software para diferentes tipos de sistemas.',
            'Se estudian los patrones arquitectónicos, estilos, vistas, documentación, evaluación mediante atributos de calidad y métodos como ATAM, y arquitecturas emergentes.'
        );
    }

    /**
     * Crea contenidos programáticos para asignaturas del SENA
     */
    private function crearContenidosSENA(): void
    {
        // Tecnólogo en Análisis y Desarrollo de Software (programa_id = 16)
        $this->crearContenidoPorNombre('Analizar Requerimientos', 16,
            'Análisis de Requerimientos de Software',
            'El aprendiz será capaz de identificar, documentar y gestionar requisitos de software según metodologías vigentes.',
            'Este tema aborda la definición, clasificación y priorización de requisitos, técnicas de elicitación, documentación con historias de usuario y casos de uso, y herramientas de gestión.'
        );

        $this->crearContenidoPorNombre('Diseñar Soluciones Software', 16,
            'Diseño de Soluciones de Software',
            'El aprendiz será capaz de diseñar soluciones de software aplicando patrones y principios de diseño.',
            'Se estudian los diagramas UML, patrones de diseño, arquitectura de software, diseño de interfaces, bases de datos y componentes según especificaciones técnicas.'
        );

        // Técnico en Sistemas (programa_id = 17)
        $this->crearContenidoPorNombre('Instalar Software', 17,
            'Instalación y Configuración de Software',
            'El aprendiz será capaz de instalar y configurar software según requerimientos y normas técnicas.',
            'Este tema cubre la instalación de sistemas operativos, aplicaciones de oficina, utilitarios, licenciamiento, configuración básica y resolución de problemas post-instalación.'
        );

        // Tecnólogo en Gestión de Redes de Datos (programa_id = 18)
        $this->crearContenidoPorNombre('Diseñar Topologías de Red', 18,
            'Diseño de Topologías de Red',
            'El aprendiz será capaz de diseñar topologías de red según requerimientos y mejores prácticas.',
            'Se estudian topologías físicas y lógicas, direccionamiento IP, subredes, enrutamiento, cableado estructurado, equipos de conectividad y simulación de redes.'
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
        $asignatura = Asignatura::where('nombre', $nombreAsignatura)
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
