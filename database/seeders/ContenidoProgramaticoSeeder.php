<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\ContenidoProgramatico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContenidoProgramaticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Crear contenido programático para cada asignatura
        ContenidoProgramatico::create([
            'asignatura_id' => 1, // Circuitos Eléctricos
            'tema' => 'Análisis de Circuitos Resistivos',
            'resultados_aprendizaje' => 'El estudiante será capaz de aplicar las leyes de Kirchhoff para resolver circuitos resistivos.',
            'descripcion' => 'Este tema cubre el análisis de circuitos eléctricos utilizando las leyes de Kirchhoff y técnicas de simplificación.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 2, // Matemáticas I
            'tema' => 'Cálculo Diferencial',
            'resultados_aprendizaje' => 'El estudiante podrá resolver problemas de derivadas y aplicarlas en situaciones reales.',
            'descripcion' => 'Introducción al cálculo diferencial, incluyendo límites, continuidad y derivadas.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 3, // Fundamentos de Programación
            'tema' => 'Estructuras de Control',
            'resultados_aprendizaje' => 'El estudiante podrá implementar estructuras de control en un lenguaje de programación.',
            'descripcion' => 'Este tema aborda las estructuras de control como condicionales y bucles en programación.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 4, // Control de Procesos
            'tema' => 'Modelado de Sistemas Dinámicos',
            'resultados_aprendizaje' => 'El estudiante podrá modelar sistemas dinámicos utilizando ecuaciones diferenciales.',
            'descripcion' => 'Se estudian los métodos de modelado de sistemas dinámicos y su representación gráfica.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 5, // Física I
            'tema' => 'Cinemática de Partículas',
            'resultados_aprendizaje' => 'El estudiante podrá describir el movimiento de partículas en una dimensión.',
            'descripcion' => 'Este tema cubre los conceptos básicos de la cinemática, incluyendo velocidad y aceleración.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 6, // Programación I
            'tema' => 'Introducción a la Programación',
            'resultados_aprendizaje' => 'El estudiante podrá escribir programas simples en un lenguaje de programación.',
            'descripcion' => 'Se introducen los conceptos básicos de programación, incluyendo variables, tipos de datos y estructuras de control.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 7, // Diseño Arquitectónico
            'tema' => 'Principios del Diseño',
            'resultados_aprendizaje' => 'El estudiante podrá aplicar principios de diseño en proyectos arquitectónicos.',
            'descripcion' => 'Este tema cubre los fundamentos del diseño arquitectónico y su aplicación práctica.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 8, // Historia de la Arquitectura
            'tema' => 'Arquitectura Clásica',
            'resultados_aprendizaje' => 'El estudiante podrá identificar y analizar las características de la arquitectura clásica.',
            'descripcion' => 'Se estudian los estilos arquitectónicos de la antigüedad y su influencia en la arquitectura moderna.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 9, // Fundamentos de Programación (Ing. Informática)
            'tema' => 'Algoritmos y Estructuras de Datos',
            'resultados_aprendizaje' => 'El estudiante podrá diseñar y analizar algoritmos básicos y estructuras de datos.',
            'descripcion' => 'Este tema cubre los conceptos fundamentales de algoritmos y estructuras de datos en programación.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 10, // Circuitos Eléctricos (Ing. Electrónica)
            'tema' => 'Teoremas de Circuitos',
            'resultados_aprendizaje' => 'El estudiante podrá aplicar teoremas de circuitos para resolver problemas eléctricos.',
            'descripcion' => 'Se estudian teoremas como el de Thévenin y Norton, y su aplicación en circuitos eléctricos.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 11, // Análisis de Requisitos (Tecnología en Desarrollo de Software)
            'tema' => 'Técnicas de Recolección de Requisitos',
            'resultados_aprendizaje' => 'El estudiante podrá identificar y documentar requisitos de software utilizando diversas técnicas.',
            'descripcion' => 'Este tema cubre las técnicas de entrevistas, encuestas y talleres para la recolección de requisitos.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 12, // Matemáticas II
            'tema' => 'Álgebra Lineal',
            'resultados_aprendizaje' => 'El estudiante podrá resolver sistemas de ecuaciones lineales y aplicar matrices.',
            'descripcion' => 'Introducción al álgebra lineal, incluyendo operaciones con matrices y determinantes.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 13, // Estructuras de Datos
            'tema' => 'Listas y Árboles',
            'resultados_aprendizaje' => 'El estudiante podrá implementar y manipular listas y árboles en un lenguaje de programación.',
            'descripcion' => 'Este tema cubre la implementación de estructuras de datos como listas enlazadas y árboles binarios.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 14, // Ingeniería de Software
            'tema' => 'Ciclo de Vida del Software',
            'resultados_aprendizaje' => 'El estudiante podrá describir las fases del ciclo de vida del desarrollo de software.',
            'descripcion' => 'Se estudian las diferentes metodologías de desarrollo de software y su aplicación práctica.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 15, // Redes de Computadores
            'tema' => 'Modelos de Red',
            'resultados_aprendizaje' => 'El estudiante podrá explicar los modelos de red OSI y TCP/IP.',
            'descripcion' => 'Este tema cubre la arquitectura de redes y los protocolos de comunicación.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 16, // Control Automático
            'tema' => 'Sistemas de Control en Tiempo Continuo',
            'resultados_aprendizaje' => 'El estudiante podrá analizar y diseñar sistemas de control en tiempo continuo.',
            'descripcion' => 'Se estudian los principios de control y su aplicación en sistemas dinámicos.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 17, // Seguridad Informática
            'tema' => 'Amenazas y Vulnerabilidades',
            'resultados_aprendizaje' => 'El estudiante podrá identificar y mitigar amenazas y vulnerabilidades en sistemas informáticos.',
            'descripcion' => 'Este tema cubre los tipos de amenazas y las mejores prácticas de seguridad informática.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 18, // Gestión de Proyectos
            'tema' => 'Planificación de Proyectos',
            'resultados_aprendizaje' => 'El estudiante podrá elaborar un plan de proyecto utilizando herramientas de gestión.',
            'descripcion' => 'Se estudian las técnicas de planificación y control de proyectos, incluyendo el uso de diagramas de Gantt.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 19, // Desarrollo Web
            'tema' => 'HTML y CSS',
            'resultados_aprendizaje' => 'El estudiante podrá crear páginas web utilizando HTML y CSS.',
            'descripcion' => 'Este tema cubre la estructura y el diseño de páginas web con HTML y CSS.',
        ]);

        ContenidoProgramatico::create([
            'asignatura_id' => 20, // Pruebas de Software
            'tema' => 'Tipos de Pruebas',
            'resultados_aprendizaje' => 'El estudiante podrá identificar y aplicar diferentes tipos de pruebas de software.',
            'descripcion' => 'Se estudian las pruebas unitarias, de integración y de sistema, y su importancia en el desarrollo de software.',
        ]);

        }
    }

