<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar restricciones de clave foránea para truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncar la tabla
        Asignatura::truncate();

        // Reactivar restricciones
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Máximo largo para el código de asignatura
        $maxLength = 20;

        // Listado de asignaturas por programa_id
        $pensum = [
            // Universidad del Cauca
            1 => [ // Ing. Electrónica y Telecomunicaciones
                'Circuitos Eléctricos', 'Electrónica Analógica', 'Electrónica Digital',
                'Comunicación Digital', 'Microcontroladores', 'Procesamiento de Señales',
                'Control Automático', 'Redes de Computadoras', 'Proyecto Electrónico I'
            ],
            2 => [ // Ing. Civil
                'Matemáticas I', 'Topografía', 'Mecánica de Materiales',
                'Hidráulica', 'Estructuras I', 'Materiales de Construcción',
                'Geotecnia', 'Transporte y Vías', 'Gestión de Proyectos'
            ],
            3 => [ // Ing. de Sistemas
                'Fundamentos de Programación', 'Estructuras de Datos', 'Sistemas Operativos',
                'Redes de Computadores', 'Bases de Datos', 'Ingeniería de Software',
                'Análisis de Algoritmos', 'Desarrollo Web', 'Sistemas Distribuidos'
            ],
            4 => [ // Ing. en Automática Industrial
                'Control de Procesos', 'Instrumentación Industrial', 'Sistemas SCADA',
                'Robótica Industrial', 'Electrónica de Potencia', 'PLC y Modulación',
                'Redes Industriales', 'Mantenimiento Industrial', 'Metrología'
            ],
            5 => [ // Ing. Física
                'Matemáticas I', 'Física I', 'Física II',
                'Termodinámica', 'Mecánica Cuántica', 'Ondas y Óptica',
                'Física Computacional', 'Electrónica para Físicos', 'Láseres y Fotónica'
            ],

            // FUP
            6 => [ // Ing. de Sistemas
                'Programación I', 'Estructuras de Datos', 'Arquitectura de Computadores',
                'Bases de Datos', 'Ingeniería de Software', 'Redes y Comunicaciones',
                'Sistemas Operativos', 'Seguridad Informática', 'Gestión de Proyectos'
            ],
            7 => [ // Ing. Industrial
                'Investigación de Operaciones', 'Estadística Aplicada', 'Gestión de Calidad',
                'Procesos de Manufactura', 'Ingeniería de Métodos', 'Logística',
                'Gestión de la Producción', 'Seguridad Industrial', 'Ergonomía'
            ],
            8 => [ // Arquitectura
                'Diseño Arquitectónico', 'Historia de la Arquitectura', 'Construcción I',
                'Dibujo Arquitectónico', 'Urbanismo', 'Tecnología de la Construcción',
                'Paisajismo', 'BIM', 'Diseño Urbano'
            ],

            // Colegio Mayor del Cauca
            9  => [ // Ing. Informática
                'Fundamentos de Programación', 'Estructuras de Datos', 'Sistemas Operativos',
                'Bases de Datos', 'Redes de Computadores', 'Desarrollo Web',
                'Programación Móvil', 'Inteligencia Artificial', 'Seguridad Informática'
            ],
            10 => [ // Ing. Electrónica
                'Circuitos Eléctricos', 'Electrónica Analógica', 'Electrónica Digital',
                'Microcontroladores', 'Señales y Sistemas', 'Electrónica de Potencia',
                'Control Automático', 'Redes de Comunicaciones', 'Proyecto Electrónico I'
            ],
            11 => [ // Tecnología en Desarrollo de Software
                'Análisis de Requisitos', 'Diseño de Software', 'Programación Orientada a Objetos',
                'Bases de Datos', 'Desarrollo Web', 'Pruebas de Software',
                'Metodologías Ágiles', 'DevOps Básico', 'Cloud Computing', 'Proyecto Integrador'
            ],

            // SENA Regional Cauca (IDs 16–23)
            16 => [ // Tecnólogo en Análisis y Desarrollo de Software
                'Analizar Requerimientos', 'Diseñar Soluciones Software', 'Desarrollar Aplicaciones',
                'Realizar Pruebas de Software', 'Implementar Front‑end', 'Desplegar en Servidor',
                'Mantener Software', 'Documentar Proyectos'
            ],
            17 => [ // Técnico en Sistemas
                'Instalar Software', 'Configurar Hardware', 'Administrar Sistemas Operativos',
                'Montar Redes Locales', 'Mantenimiento Preventivo', 'Administrar Cuentas de Usuario',
                'Instalación de Periféricos', 'Monitoreo de Sistemas'
            ],
            18 => [ // Tecnólogo en Gestión de Redes de Datos
                'Diseñar Topologías de Red', 'Configurar Routers y Switches', 'Implementar VLAN',
                'Asegurar Redes', 'Monitorizar Tráfico', 'Administrar VPN',
                'Optimizar Rendimiento', 'Gestionar Proyectos de Redes'
            ],
            19 => [ // Tecnólogo en Producción Multimedia
                'Diseñar Gráficos', 'Edición de Video', 'Creación de Animaciones',
                'Producción de Audio', 'Maquetación Web', 'Modelado 3D',
                'UX/UI Básico', 'Publicación Multimedia'
            ],
            20 => [ // Técnico en Programación de Software
                'Codificar Módulos', 'Depurar Código', 'Gestionar Repositorios',
                'Documentar Código', 'Ejecutar Compilaciones', 'Pruebas Unitarias',
                'Asistir en Análisis', 'Soporte a Usuario'
            ],
            21 => [ // Tecnólogo en Implementación de Infraestructura TIC
                'Instalar Servidores', 'Configurar Redes', 'Desplegar Servicios',
                'Virtualización', 'Administrar Almacenamiento', 'Asegurar Infraestructura',
                'Automatizar Despliegues', 'Monitorizar Servicios'
            ],
            22 => [ // Tecnólogo en Gestión de Seguridad y Salud en el Trabajo
                'Identificar Riesgos', 'Implementar Medidas Preventivas', 'Elaborar Planes SST',
                'Capacitar Personal', 'Realizar Auditorías', 'Gestionar Incidentes',
                'Reportar Accidentes', 'Actualizar Normatividad'
            ],
            23 => [ // Tecnólogo en Gestión de Proyectos de Desarrollo de Software
                'Definir Alcance', 'Elaborar Cronogramas', 'Administrar Recursos',
                'Gestionar Riesgos', 'Liderar Equipos', 'Controlar Calidad',
                'Comunicar Stakeholders', 'Entregar Entregables'
            ],
        ];

        // Lista de IDs de programas del SENA (institución 2)
        $programasSena = [16, 17, 18, 19, 20, 21, 22, 23];

        foreach ($pensum as $programaId => $asigs) {
            $esSena = in_array($programaId, $programasSena);

            foreach ($asigs as $index => $nombre) {
                // Generar un código único combinando programa_id y un hash corto del nombre
                $slug = Str::slug($nombre, '_');
                // Limitar la longitud del slug para asegurar que el código no exceda $maxLength
                $codigoLength = strlen($programaId . '_') + 10; // 10 es un tamaño seguro para la parte del slug
                $codigo = $programaId . '_' . strtoupper(substr($slug, 0, min(strlen($slug), $maxLength - $codigoLength)));

                // Asegurar que el código no exceda $maxLength
                if (strlen($codigo) > $maxLength) {
                    $codigo = substr($codigo, 0, $maxLength);
                }

                // Configuración para programas del SENA vs no-SENA
                if ($esSena) {
                    // Para programas del SENA
                    Asignatura::updateOrCreate(
                        ['codigo_asignatura' => $codigo],
                        [
                            'programa_id'             => $programaId,
                            'nombre'                  => $nombre,
                            'tipo'                    => 'Competencia',
                            'creditos'                => null,
                            'horas_sena'              => $this->calcularHorasSena($nombre),
                            'semestre'                => null, // No se asigna semestre al SENA
                            'tiempo_presencial'       => null,
                            'tiempo_independiente'    => null,
                            'horas_totales_semanales' => null,
                            'modalidad'               => 'Práctico',
                            'metodologia'             => 'Presencial',
                            'created_at'              => now(),
                            'updated_at'              => now(),
                        ]
                    );
                } else {
                    // Para programas que no son del SENA
                    $creditos = $this->calcularCreditos($nombre);
                    $tiempoPresencial = $this->calcularTiempoPresencial($creditos, $nombre);
                    $tiempoIndependiente = $creditos * 2;
                    $horasTotalesSemanales = $tiempoPresencial + $tiempoIndependiente;

                    Asignatura::updateOrCreate(
                        ['codigo_asignatura' => $codigo],
                        [
                            'programa_id'             => $programaId,
                            'nombre'                  => $nombre,
                            'tipo'                    => 'Materia',
                            'creditos'                => $creditos,
                            'horas_sena'              => null,
                            'semestre'                => floor($index / 3) + 1,
                            'tiempo_presencial'       => $tiempoPresencial,
                            'tiempo_independiente'    => $tiempoIndependiente,
                            'horas_totales_semanales' => $horasTotalesSemanales,
                            'modalidad'               => $this->determinarModalidad($nombre),
                            'metodologia'             => 'Presencial',
                            'created_at'              => now(),
                            'updated_at'              => now(),
                        ]
                    );
                }
            }
        }

        // AUTÓNOMA DEL CAUCA - Ingeniería de Software
        $programaId = 12; // ID del programa de Ingeniería de Software en la Autónoma
        $codigoBase = 'IS'; // Prefijo base para Ingeniería de Software

        $pensum = [
            1 => ['Matemáticas I', 'Física I', 'Programación I', 'Comunicación Oral y Escrita', 'Fundamentos de Ingeniería de Software'],
            2 => ['Matemáticas II', 'Física II', 'Programación II', 'Lógica y Matemática Discreta', 'Arquitectura de Computadores'],
            3 => ['Estructuras de Datos', 'Bases de Datos I', 'Ingeniería de Requisitos', 'Probabilidad y Estadística', 'Sistemas Operativos'],
            4 => ['Bases de Datos II', 'Diseño de Software', 'Ingeniería de Software I', 'Análisis y Diseño de Algoritmos', 'Redes de Computadores'],
            5 => ['Lenguajes de Programación', 'Seguridad Informática', 'Gestión de Proyectos de Software', 'Desarrollo Web', 'Electiva Profesional I'],
            6 => ['Ingeniería de Software II', 'Desarrollo de Aplicaciones Móviles', 'Computación en la Nube', 'Interacción Humano-Computador', 'Electiva Profesional II'],
            7 => ['Inteligencia Artificial', 'Arquitectura de Software', 'Auditoría y Normatividad en Software', 'Electiva Profesional III', 'Práctica Empresarial I'],
            8 => ['Minería de Datos', 'DevOps y Automatización', 'Ética Profesional', 'Electiva Profesional IV', 'Práctica Empresarial II'],
            9 => ['Trabajo de Grado', 'Emprendimiento y Nuevas Tecnologías'],
        ];

        foreach ($pensum as $semestre => $asignaturas) {
            foreach ($asignaturas as $index => $nombre) {
                // Generar código único para evitar duplicados, asegurando que no exceda $maxLength
                $codigo = $codigoBase . '_S' . $semestre . '_M' . ($index + 1);

                // Asegurar que el código no exceda $maxLength
                if (strlen($codigo) > $maxLength) {
                    $codigo = substr($codigo, 0, $maxLength);
                }

                // Calcular créditos y tiempos más realistas
                $creditos = $this->calcularCreditos($nombre);
                $tiempoPresencial = $this->calcularTiempoPresencial($creditos, $nombre);
                $tiempoIndependiente = $creditos * 2;
                $horasTotalesSemanales = $tiempoPresencial + $tiempoIndependiente;

                Asignatura::updateOrCreate(
                    ['codigo_asignatura' => $codigo],
                    [
                        'programa_id' => $programaId,
                        'nombre' => $nombre,
                        'tipo' => 'Materia',
                        'creditos' => $creditos,
                        'semestre' => $semestre,
                        'horas_sena' => null,
                        'tiempo_presencial' => $tiempoPresencial,
                        'tiempo_independiente' => $tiempoIndependiente,
                        'horas_totales_semanales' => $horasTotalesSemanales,
                        'modalidad' => $this->determinarModalidad($nombre),
                        'metodologia' => 'Presencial',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Calcula el número de créditos basado en el nombre de la asignatura
     */
    private function calcularCreditos($nombre)
    {
        // Materias con mayor intensidad académica
        $materiasAvanzadas = [
            'Proyecto', 'Trabajo de Grado', 'Práctica', 'Tesis', 'Laboratorio',
            'Diseño', 'Arquitectura', 'Inteligencia Artificial', 'Bases de Datos',
            'Desarrollo Web', 'Computación en la Nube', 'Desarrollo de Aplicaciones'
        ];

        // Materias básicas o introductorias
        $materiasBasicas = [
            'Fundamentos', 'Introducción', 'Comunicación', 'Oral', 'Escrita',
            'Ética', 'Emprendimiento'
        ];

        // Verificar si el nombre contiene palabras clave
        foreach ($materiasAvanzadas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(4, 5); // Materias avanzadas: 4-5 créditos
            }
        }

        foreach ($materiasBasicas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(2, 3); // Materias básicas: 2-3 créditos
            }
        }

        return rand(3, 4); // Materias regulares: 3-4 créditos
    }

    /**
     * Calcula el tiempo presencial basado en los créditos y el nombre de la asignatura
     */
    private function calcularTiempoPresencial($creditos, $nombre)
    {
        // Palabras clave para materias con mayor componente práctico
        $componentePractico = [
            'Laboratorio', 'Práctica', 'Proyecto', 'Taller', 'Desarrollo',
            'Implementación', 'Diseño', 'Programación', 'DevOps', 'Front-end'
        ];

        // Verificar si la materia tiene componente práctico
        foreach ($componentePractico as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                // Para materias prácticas: más tiempo presencial
                return ceil($creditos * 0.75);
            }
        }

        // Para materias más teóricas
        return ceil($creditos * 0.5);
    }

    /**
     * Determina la modalidad basado en el nombre de la asignatura
     */
    private function determinarModalidad($nombre)
    {
        // Palabras clave para materias principalmente teóricas
        $materiasTeoricas = [
            'Matemáticas', 'Física', 'Historia', 'Teoría', 'Lógica',
            'Ética', 'Normatividad', 'Probabilidad', 'Estadística'
        ];

        // Palabras clave para materias principalmente prácticas
        $materiasPracticas = [
            'Laboratorio', 'Taller', 'Práctica', 'Desarrollo', 'Implementación',
            'Pruebas', 'Programación', 'Diseño', 'DevOps', 'Front-end'
        ];

        // Verificar si la materia es principalmente teórica
        foreach ($materiasTeoricas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return 'Teórico';
            }
        }

        // Verificar si la materia es principalmente práctica
        foreach ($materiasPracticas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return 'Práctico';
            }
        }

        // Por defecto, se considera teórico-práctico
        return 'Teórico-Práctico';
    }

    /**
     * Calcula las horas SENA basado en el nombre de la competencia
     */
    private function calcularHorasSena($nombre)
    {
        // Competencias más complejas generalmente tienen más horas
        $competenciasComplejas = [
            'Desarrollar', 'Implementar', 'Gestionar', 'Diseñar', 'Administrar',
            'Liderar', 'Desplegar', 'Virtualización', 'Optimizar'
        ];

        // Competencias básicas o más simples
        $competenciasBasicas = [
            'Instalar', 'Configurar', 'Documentar', 'Monitorear', 'Reportar',
            'Mantener', 'Asistir'
        ];

        // Verificar complejidad de la competencia
        foreach ($competenciasComplejas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(160, 240); // Competencias complejas: 160-240 horas
            }
        }

        foreach ($competenciasBasicas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(80, 140); // Competencias básicas: 80-140 horas
            }
        }

        return rand(120, 200); // Competencias estándar: 120-200 horas
    }
}
