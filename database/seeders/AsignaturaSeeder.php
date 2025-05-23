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
            // UNIVERSIDAD DEL CAUCA
            1 => [ // Ing. Electrónica y Telecomunicaciones
                1 => ['Cálculo Diferencial', 'Álgebra Lineal', 'Física I', 'Química General', 'Introducción a la Ingeniería', 'Inglés I'],
                2 => ['Cálculo Integral', 'Física II', 'Circuitos Eléctricos I', 'Programación I', 'Inglés II', 'Expresión Gráfica'],
                3 => ['Ecuaciones Diferenciales', 'Física III', 'Circuitos Eléctricos II', 'Electrónica Analógica I', 'Probabilidad y Estadística', 'Materiales Eléctricos'],
                4 => ['Análisis Complejo', 'Electrónica Analógica II', 'Electrónica Digital I', 'Señales y Sistemas I', 'Campos Electromagnéticos', 'Métodos Numéricos'],
                5 => ['Electrónica Digital II', 'Señales y Sistemas II', 'Sistemas de Comunicaciones I', 'Microprocesadores', 'Control Automático I', 'Economía'],
                6 => ['Sistemas de Comunicaciones II', 'Procesamiento Digital de Señales', 'Microcontroladores', 'Control Automático II', 'Redes de Computadores', 'Gestión Empresarial'],
                7 => ['Comunicaciones Digitales', 'Sistemas Embebidos', 'Antenas y Propagación', 'Instrumentación Electrónica', 'Electiva Profesional I', 'Formulación de Proyectos'],
                8 => ['Comunicaciones Móviles', 'Redes de Telecomunicaciones', 'Sistemas de Control Digital', 'Electiva Profesional II', 'Seminario de Investigación', 'Ética Profesional'],
                9 => ['Proyecto de Grado I', 'Electiva Profesional III', 'Práctica Académica'],
                10 => ['Proyecto de Grado II', 'Electiva Profesional IV']
            ],

            2 => [ // Ing. Civil
                1 => ['Cálculo Diferencial', 'Álgebra Lineal', 'Física I', 'Química General', 'Dibujo de Ingeniería', 'Introducción a la Ingeniería Civil'],
                2 => ['Cálculo Integral', 'Física II', 'Estática', 'Topografía I', 'Geología', 'Expresión Oral y Escrita'],
                3 => ['Cálculo Vectorial', 'Dinámica', 'Resistencia de Materiales I', 'Topografía II', 'Materiales de Construcción', 'Probabilidad y Estadística'],
                4 => ['Ecuaciones Diferenciales', 'Mecánica de Fluidos', 'Resistencia de Materiales II', 'Análisis Estructural I', 'Geotecnia I', 'Métodos Numéricos'],
                5 => ['Hidrología', 'Hidráulica', 'Análisis Estructural II', 'Concreto Reforzado I', 'Geotecnia II', 'Vías I'],
                6 => ['Acueductos', 'Alcantarillados', 'Concreto Reforzado II', 'Estructuras Metálicas', 'Pavimentos', 'Vías II'],
                7 => ['Tratamiento de Aguas', 'Puentes', 'Construcción I', 'Programación y Control de Obras', 'Ingeniería Sísmica', 'Electiva Profesional I'],
                8 => ['Ingeniería Sanitaria', 'Construcción II', 'Evaluación de Proyectos', 'Gerencia de Construcción', 'Electiva Profesional II', 'Seminario de Investigación'],
                9 => ['Proyecto de Grado I', 'Práctica Académica', 'Electiva Profesional III'],
                10 => ['Proyecto de Grado II', 'Ética Profesional']
            ],

            3 => [ // Ing. Ambiental
                1 => ['Cálculo Diferencial', 'Química General', 'Biología General', 'Introducción a la Ingeniería Ambiental', 'Expresión Oral y Escrita', 'Dibujo Técnico'],
                2 => ['Cálculo Integral', 'Química Orgánica', 'Microbiología', 'Física I', 'Estadística', 'Ecología General'],
                3 => ['Ecuaciones Diferenciales', 'Química Analítica', 'Física II', 'Geología Ambiental', 'Meteorología y Climatología', 'Economía Ambiental'],
                4 => ['Fisicoquímica', 'Mecánica de Fluidos', 'Hidrología', 'Química Ambiental', 'Evaluación de Impacto Ambiental', 'Métodos Numéricos'],
                5 => ['Termodinámica', 'Hidráulica', 'Contaminación Atmosférica', 'Contaminación del Agua', 'Gestión Ambiental', 'Sistemas de Información Geográfica'],
                6 => ['Procesos Unitarios', 'Tratamiento de Aguas Residuales', 'Control de Emisiones', 'Contaminación del Suelo', 'Auditoría Ambiental', 'Biotecnología Ambiental'],
                7 => ['Tratamiento de Agua Potable', 'Residuos Sólidos', 'Modelación Ambiental', 'Restauración Ecológica', 'Legislación Ambiental', 'Electiva Profesional I'],
                8 => ['Planeación Ambiental', 'Energías Alternativas', 'Toxicología Ambiental', 'Formulación de Proyectos', 'Electiva Profesional II', 'Seminario de Investigación'],
                9 => ['Proyecto de Grado I', 'Práctica Académica', 'Electiva Profesional III'],
                10 => ['Proyecto de Grado II', 'Ética Profesional']
            ],

            4 => [ // Ing. de Sistemas
                1 => ['Cálculo Diferencial', 'Álgebra Lineal', 'Lógica Matemática', 'Introducción a la Programación', 'Introducción a la Ingeniería de Sistemas', 'Inglés I'],
                2 => ['Cálculo Integral', 'Matemáticas Discretas', 'Programación I', 'Arquitectura de Computadores', 'Física I', 'Inglés II'],
                3 => ['Cálculo Vectorial', 'Probabilidad y Estadística', 'Programación II', 'Estructuras de Datos', 'Sistemas Digitales', 'Expresión Oral y Escrita'],
                4 => ['Ecuaciones Diferenciales', 'Investigación de Operaciones', 'Programación Orientada a Objetos', 'Sistemas Operativos', 'Bases de Datos I', 'Redes de Computadores I'],
                5 => ['Análisis Numérico', 'Análisis y Diseño de Algoritmos', 'Ingeniería de Software I', 'Bases de Datos II', 'Redes de Computadores II', 'Economía'],
                6 => ['Inteligencia Artificial', 'Ingeniería de Software II', 'Sistemas Distribuidos', 'Seguridad Informática', 'Desarrollo Web', 'Gestión de Proyectos'],
                7 => ['Computación Gráfica', 'Arquitectura de Software', 'Sistemas de Información', 'Electiva Profesional I', 'Seminario de Investigación I', 'Formulación de Proyectos'],
                8 => ['Minería de Datos', 'Auditoría de Sistemas', 'Electiva Profesional II', 'Electiva Profesional III', 'Seminario de Investigación II', 'Ética Profesional'],
                9 => ['Proyecto de Grado I', 'Práctica Académica', 'Electiva Profesional IV'],
                10 => ['Proyecto de Grado II']
            ],

            5 => [ // Ing. en Automática Industrial
                1 => ['Cálculo Diferencial', 'Álgebra Lineal', 'Física I', 'Química General', 'Dibujo Técnico', 'Introducción a la Automática'],
                2 => ['Cálculo Integral', 'Física II', 'Circuitos Eléctricos I', 'Programación I', 'Materiales de Ingeniería', 'Expresión Oral y Escrita'],
                3 => ['Ecuaciones Diferenciales', 'Física III', 'Circuitos Eléctricos II', 'Electrónica Analógica', 'Mecánica de Fluidos', 'Probabilidad y Estadística'],
                4 => ['Métodos Numéricos', 'Electrónica Digital', 'Electrónica de Potencia', 'Termodinámica', 'Señales y Sistemas', 'Instrumentación I'],
                5 => ['Control Clásico', 'Microprocesadores', 'Máquinas Eléctricas', 'Instrumentación II', 'Sistemas Hidráulicos y Neumáticos', 'Economía'],
                6 => ['Control Moderno', 'Microcontroladores', 'Accionamientos Eléctricos', 'PLC y Automatización', 'Robótica I', 'Gestión Industrial'],
                7 => ['Control Digital', 'Sistemas SCADA', 'Robótica II', 'Redes Industriales', 'Mantenimiento Industrial', 'Electiva Profesional I'],
                8 => ['Control Avanzado', 'Visión Artificial', 'Simulación de Procesos', 'Seguridad Industrial', 'Electiva Profesional II', 'Seminario de Investigación'],
                9 => ['Proyecto de Grado I', 'Práctica Académica', 'Electiva Profesional III'],
                10 => ['Proyecto de Grado II', 'Ética Profesional']
            ],

            // FUNDACIÓN UNIVERSITARIA DE POPAYÁN (FUP)
            6 => [ // Ing. de Sistemas
                1 => ['Matemáticas I', 'Lógica Matemática', 'Introducción a la Programación', 'Fundamentos de Computación', 'Comunicación Oral y Escrita', 'Inglés I'],
                2 => ['Matemáticas II', 'Matemáticas Discretas', 'Programación I', 'Arquitectura del Computador', 'Física I', 'Inglés II'],
                3 => ['Matemáticas III', 'Estadística', 'Programación II', 'Estructuras de Datos', 'Sistemas Digitales', 'Metodología de la Investigación'],
                4 => ['Investigación de Operaciones', 'Programación Orientada a Objetos', 'Sistemas Operativos', 'Base de Datos I', 'Redes I', 'Física II'],
                5 => ['Análisis Numérico', 'Ingeniería de Software I', 'Base de Datos II', 'Redes II', 'Programación Web', 'Economía'],
                6 => ['Inteligencia Artificial', 'Ingeniería de Software II', 'Sistemas Distribuidos', 'Seguridad Informática', 'Programación Móvil', 'Administración'],
                7 => ['Minería de Datos', 'Arquitectura de Software', 'Sistemas de Información', 'Electiva Profesional I', 'Gerencia de Proyectos', 'Constitución Política'],
                8 => ['Auditoría de Sistemas', 'Electiva Profesional II', 'Electiva Profesional III', 'Seminario de Investigación', 'Emprendimiento', 'Ética Profesional'],
                9 => ['Proyecto de Grado I', 'Práctica Empresarial'],
                10 => ['Proyecto de Grado II']
            ],

            7 => [ // Ing. Industrial
                1 => ['Matemáticas I', 'Física I', 'Química General', 'Introducción a la Ingeniería Industrial', 'Comunicación Oral y Escrita', 'Inglés I'],
                2 => ['Matemáticas II', 'Física II', 'Dibujo Técnico', 'Materiales de Ingeniería', 'Contabilidad', 'Inglés II'],
                3 => ['Matemáticas III', 'Estadística I', 'Resistencia de Materiales', 'Procesos de Manufactura I', 'Microeconomía', 'Metodología de la Investigación'],
                4 => ['Investigación de Operaciones I', 'Estadística II', 'Termodinámica', 'Procesos de Manufactura II', 'Macroeconomía', 'Psicología Organizacional'],
                5 => ['Investigación de Operaciones II', 'Control Estadístico de Calidad', 'Ingeniería de Métodos', 'Sistemas de Manufactura', 'Costos Industriales', 'Administración'],
                6 => ['Simulación', 'Gestión de Calidad Total', 'Estudio del Trabajo', 'Logística', 'Finanzas', 'Mercadeo'],
                7 => ['Planeación y Control de la Producción', 'Diseño y Distribución de Plantas', 'Cadena de Suministro', 'Evaluación de Proyectos', 'Electiva Profesional I', 'Constitución Política'],
                8 => ['Mantenimiento Industrial', 'Seguridad Industrial', 'Gestión Ambiental', 'Formulación de Proyectos', 'Electiva Profesional II', 'Ética Profesional'],
                9 => ['Proyecto de Grado I', 'Práctica Empresarial', 'Seminario de Investigación'],
                10 => ['Proyecto de Grado II', 'Emprendimiento']
            ],

            8 => [ // Arquitectura
                1 => ['Dibujo Arquitectónico I', 'Geometría Descriptiva', 'Taller de Diseño I', 'Historia de la Arquitectura I', 'Matemáticas I', 'Comunicación Oral y Escrita'],
                2 => ['Dibujo Arquitectónico II', 'Taller de Diseño II', 'Historia de la Arquitectura II', 'Matemáticas II', 'Física I', 'Inglés I'],
                3 => ['Taller de Diseño III', 'Construcción I', 'Historia de la Arquitectura III', 'Topografía', 'Física II', 'Inglés II'],
                4 => ['Taller de Diseño IV', 'Construcción II', 'Estructuras I', 'Instalaciones I', 'Teoría de la Arquitectura I', 'Metodología de la Investigación'],
                5 => ['Taller de Diseño V', 'Construcción III', 'Estructuras II', 'Instalaciones II', 'Teoría de la Arquitectura II', 'Presupuestos'],
                6 => ['Taller de Diseño VI', 'Construcción IV', 'Estructuras III', 'Urbanismo I', 'Paisajismo', 'Administración de Obras'],
                7 => ['Taller de Diseño VII', 'Urbanismo II', 'Restauración', 'Electiva Profesional I', 'Programación Arquitectónica', 'Legislación'],
                8 => ['Taller de Diseño VIII', 'Urbanismo III', 'Electiva Profesional II', 'Formulación de Proyectos', 'Ética Profesional', 'Constitución Política'],
                9 => ['Proyecto de Grado I', 'Práctica Profesional', 'Seminario de Investigación'],
                10 => ['Proyecto de Grado II', 'Emprendimiento']
            ],

            // INSTITUCIÓN UNIVERSITARIA COLEGIO MAYOR DEL CAUCA (UNIMAYOR)
            9 => [ // Ing. Informática
                1 => ['Matemáticas I', 'Lógica Matemática', 'Introducción a la Programación', 'Fundamentos de Informática', 'Comunicación Escrita', 'Inglés I'],
                2 => ['Matemáticas II', 'Álgebra Lineal', 'Programación I', 'Arquitectura de Computadores', 'Física I', 'Inglés II'],
                3 => ['Matemáticas III', 'Estadística', 'Programación II', 'Estructuras de Datos', 'Sistemas Digitales', 'Metodología de la Investigación'],
                4 => ['Investigación de Operaciones', 'Programación Orientada a Objetos', 'Sistemas Operativos', 'Base de Datos I', 'Redes de Computadores I', 'Ética'],
                5 => ['Análisis de Algoritmos', 'Ingeniería de Software I', 'Base de Datos II', 'Redes de Computadores II', 'Desarrollo Web I', 'Economía'],
                6 => ['Inteligencia Artificial', 'Ingeniería de Software II', 'Programación Móvil', 'Seguridad Informática', 'Desarrollo Web II', 'Gestión de Proyectos'],
                7 => ['Arquitectura de Software', 'Sistemas Distribuidos', 'Computación Gráfica', 'Electiva Profesional I', 'Seminario de Investigación I', 'Emprendimiento'],
                8 => ['Auditoría de Sistemas', 'Minería de Datos', 'Electiva Profesional II', 'Electiva Profesional III', 'Seminario de Investigación II', 'Constitución Política'],
                9 => ['Proyecto de Grado I', 'Práctica Profesional'],
                10 => ['Proyecto de Grado II']
            ],

            10 => [ // Ing. Multimedia
                1 => ['Matemáticas I', 'Física I', 'Fundamentos de Multimedia', 'Dibujo Técnico', 'Comunicación Visual', 'Inglés I'],
                2 => ['Matemáticas II', 'Física II', 'Programación I', 'Diseño Gráfico Digital', 'Color y Composición', 'Inglés II'],
                3 => ['Matemáticas III', 'Estadística', 'Programación II', 'Animación 2D', 'Fotografía Digital', 'Metodología de la Investigación'],
                4 => ['Álgebra Lineal', 'Arquitectura de Computadores', 'Estructuras de Datos', 'Animación 3D I', 'Edición de Video', 'Comunicación Audiovisual'],
                5 => ['Análisis de Algoritmos', 'Sistemas Operativos', 'Bases de Datos', 'Animación 3D II', 'Producción de Audio', 'Psicología del Color'],
                6 => ['Redes de Computadores', 'Programación Web', 'Modelado 3D', 'Efectos Visuales', 'Diseño de Interfaces', 'Gestión de Proyectos'],
                7 => ['Sistemas Multimedia', 'Realidad Virtual', 'Videojuegos I', 'Postproducción', 'Electiva Profesional I', 'Mercadeo Digital'],
                8 => ['Computación Gráfica', 'Realidad Aumentada', 'Videojuegos II', 'Electiva Profesional II', 'Seminario de Investigación', 'Ética'],
9 => ['Proyecto de Grado I', 'Práctica Profesional', 'Emprendimiento'],
                10 => ['Proyecto de Grado II', 'Constitución Política']
            ],

            11 => [ // Tecnología en Desarrollo de Software
                1 => ['Matemáticas Básicas', 'Lógica de Programación', 'Fundamentos de Computación', 'Comunicación Escrita', 'Inglés Técnico I'],
                2 => ['Matemáticas Aplicadas', 'Programación I', 'Sistemas Operativos', 'Base de Datos I', 'Inglés Técnico II'],
                3 => ['Estadística', 'Programación II', 'Estructuras de Datos', 'Base de Datos II', 'Metodología de la Investigación'],
                4 => ['Programación Orientada a Objetos', 'Análisis y Diseño de Sistemas', 'Programación Web I', 'Redes de Computadores', 'Ética Profesional'],
                5 => ['Ingeniería de Software', 'Programación Web II', 'Programación Móvil', 'Seguridad Informática', 'Gestión de Proyectos'],
                6 => ['Proyecto Integrador', 'Práctica Empresarial', 'Emprendimiento', 'Constitución Política']
            ],

            // CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA (UNIAUTÓNOMA)
            12 => [ // Ing. de Software y Computación
                1 => ['Matemáticas I', 'Física I', 'Programación I', 'Comunicación Oral y Escrita', 'Fundamentos de Ingeniería de Software'],
                2 => ['Matemáticas II', 'Física II', 'Programación II', 'Lógica y Matemática Discreta', 'Arquitectura de Computadores'],
                3 => ['Estructuras de Datos', 'Bases de Datos I', 'Ingeniería de Requisitos', 'Probabilidad y Estadística', 'Sistemas Operativos'],
                4 => ['Bases de Datos II', 'Diseño de Software', 'Ingeniería de Software I', 'Análisis y Diseño de Algoritmos', 'Redes de Computadores'],
                5 => ['Lenguajes de Programación', 'Seguridad Informática', 'Gestión de Proyectos de Software', 'Desarrollo Web', 'Electiva Profesional I'],
                6 => ['Ingeniería de Software II', 'Desarrollo de Aplicaciones Móviles', 'Computación en la Nube', 'Interacción Humano-Computador', 'Electiva Profesional II'],
                7 => ['Inteligencia Artificial', 'Arquitectura de Software', 'Auditoría y Normatividad en Software', 'Electiva Profesional III', 'Práctica Empresarial I'],
                8 => ['Minería de Datos', 'DevOps y Automatización', 'Ética Profesional', 'Electiva Profesional IV', 'Práctica Empresarial II'],
                9 => ['Trabajo de Grado', 'Emprendimiento y Nuevas Tecnologías']
            ],

            13 => [ // Ing. Electrónica
                1 => ['Cálculo Diferencial', 'Álgebra Lineal', 'Física I', 'Química General', 'Introducción a la Ingeniería', 'Inglés I'],
                2 => ['Cálculo Integral', 'Física II', 'Circuitos Eléctricos I', 'Programación I', 'Dibujo Técnico', 'Inglés II'],
                3 => ['Ecuaciones Diferenciales', 'Física III', 'Circuitos Eléctricos II', 'Electrónica Analógica I', 'Probabilidad y Estadística', 'Materiales Eléctricos'],
                4 => ['Métodos Numéricos', 'Electrónica Analógica II', 'Electrónica Digital I', 'Señales y Sistemas I', 'Campos Electromagnéticos', 'Economía'],
                5 => ['Electrónica Digital II', 'Señales y Sistemas II', 'Sistemas de Comunicaciones', 'Microprocesadores', 'Control Automático', 'Administración'],
                6 => ['Microcontroladores', 'Procesamiento Digital de Señales', 'Instrumentación Electrónica', 'Electrónica de Potencia', 'Sistemas Embebidos', 'Gestión de Proyectos'],
                7 => ['Robótica', 'Comunicaciones Digitales', 'Control Digital', 'Electiva Profesional I', 'Seminario de Investigación', 'Formulación de Proyectos'],
                8 => ['Automatización Industrial', 'Electiva Profesional II', 'Electiva Profesional III', 'Práctica Empresarial', 'Ética Profesional', 'Emprendimiento'],
                9 => ['Proyecto de Grado I'],
                10 => ['Proyecto de Grado II']
            ],

            14 => [ // Ing. Civil
                1 => ['Cálculo Diferencial', 'Álgebra Lineal', 'Física I', 'Química General', 'Dibujo de Ingeniería', 'Introducción a la Ingeniería Civil'],
                2 => ['Cálculo Integral', 'Física II', 'Estática', 'Topografía I', 'Geología', 'Expresión Oral y Escrita'],
                3 => ['Cálculo Vectorial', 'Dinámica', 'Resistencia de Materiales I', 'Topografía II', 'Materiales de Construcción', 'Probabilidad y Estadística'],
                4 => ['Ecuaciones Diferenciales', 'Mecánica de Fluidos', 'Resistencia de Materiales II', 'Análisis Estructural I', 'Geotecnia I', 'Métodos Numéricos'],
                5 => ['Hidrología', 'Hidráulica', 'Análisis Estructural II', 'Concreto Reforzado I', 'Geotecnia II', 'Vías I'],
                6 => ['Acueductos', 'Alcantarillados', 'Concreto Reforzado II', 'Estructuras Metálicas', 'Pavimentos', 'Vías II'],
                7 => ['Tratamiento de Aguas', 'Construcción I', 'Puentes', 'Programación y Control de Obras', 'Electiva Profesional I', 'Gestión de Proyectos'],
                8 => ['Ingeniería Sísmica', 'Construcción II', 'Evaluación de Proyectos', 'Electiva Profesional II', 'Seminario de Investigación', 'Ética Profesional'],
                9 => ['Proyecto de Grado I', 'Práctica Empresarial'],
                10 => ['Proyecto de Grado II', 'Emprendimiento']
            ],

            15 => [ // Ing. Energética
                1 => ['Cálculo Diferencial', 'Álgebra Lineal', 'Física I', 'Química General', 'Introducción a la Ingeniería Energética', 'Inglés I'],
                2 => ['Cálculo Integral', 'Física II', 'Termodinámica I', 'Circuitos Eléctricos', 'Programación', 'Inglés II'],
                3 => ['Ecuaciones Diferenciales', 'Física III', 'Termodinámica II', 'Mecánica de Fluidos I', 'Transferencia de Calor', 'Probabilidad y Estadística'],
                4 => ['Métodos Numéricos', 'Electrotecnia', 'Mecánica de Fluidos II', 'Máquinas Térmicas', 'Combustibles y Combustión', 'Economía'],
                5 => ['Máquinas Eléctricas', 'Energías Renovables I', 'Centrales Térmicas', 'Instalaciones Eléctricas', 'Instrumentación y Control', 'Administración'],
                6 => ['Sistemas de Potencia', 'Energías Renovables II', 'Centrales Hidroeléctricas', 'Cogeneración', 'Eficiencia Energética', 'Gestión Energética'],
                7 => ['Distribución de Energía', 'Energía Solar', 'Energía Eólica', 'Almacenamiento de Energía', 'Electiva Profesional I', 'Formulación de Proyectos'],
                8 => ['Mercados Energéticos', 'Auditoría Energética', 'Electiva Profesional II', 'Seminario de Investigación', 'Práctica Empresarial', 'Ética Profesional'],
                9 => ['Proyecto de Grado I', 'Emprendimiento'],
                10 => ['Proyecto de Grado II']
            ],

            16 => [ // Ing. Ambiental y de Saneamiento
                1 => ['Cálculo Diferencial', 'Química General', 'Biología General', 'Introducción a la Ingeniería Ambiental', 'Expresión Oral y Escrita', 'Inglés I'],
                2 => ['Cálculo Integral', 'Química Orgánica', 'Microbiología', 'Física I', 'Estadística', 'Inglés II'],
                3 => ['Ecuaciones Diferenciales', 'Química Analítica', 'Física II', 'Geología Ambiental', 'Meteorología', 'Ecología'],
                4 => ['Fisicoquímica', 'Mecánica de Fluidos', 'Hidrología', 'Química Ambiental', 'Evaluación de Impacto Ambiental', 'Métodos Numéricos'],
                5 => ['Termodinámica', 'Hidráulica', 'Contaminación Atmosférica', 'Contaminación del Agua', 'Gestión Ambiental', 'SIG Ambiental'],
                6 => ['Procesos Unitarios', 'Tratamiento de Aguas Residuales', 'Control de Emisiones', 'Contaminación del Suelo', 'Auditoría Ambiental', 'Biotecnología Ambiental'],
                7 => ['Tratamiento de Agua Potable', 'Residuos Sólidos', 'Modelación Ambiental', 'Restauración Ecológica', 'Legislación Ambiental', 'Electiva Profesional I'],
                8 => ['Planeación Ambiental', 'Energías Limpias', 'Toxicología Ambiental', 'Formulación de Proyectos', 'Electiva Profesional II', 'Seminario de Investigación'],
                9 => ['Proyecto de Grado I', 'Práctica Empresarial', 'Ética Profesional'],
                10 => ['Proyecto de Grado II', 'Emprendimiento']
            ],

            // SENA REGIONAL CAUCA
            17 => [ // Tecnólogo en Análisis y Desarrollo de Software
                'Analizar los Requerimientos del Cliente',
                'Aplicar Herramientas Ofimáticas',
                'Aplicar Estructura del Lenguaje de Programación',
                'Modelar Bases de Datos',
                'Desarrollar la Interfaz que Permita la Interacción',
                'Construir el Sistema que Cumpla con los Requerimientos',
                'Realizar Mantenimiento de Software',
                'Documentar el Sistema de Información',
                'Realizar Pruebas del Sistema de Información',
                'Implementar la Estructura de la Base de Datos',
                'Desarrollar Componentes de Acceso a Datos',
                'Publicar el Sistema de Información'
            ],

            18 => [ // Técnico en Sistemas
                'Ensamblar y Desensamblar los Componentes Hardware',
                'Instalar el Sistema Operativo y Software de Aplicación',
                'Aplicar Herramientas Ofimáticas',
                'Configurar la Conectividad entre Dispositivos',
                'Implementar la Estructura de la Red de Acuerdo con un Diseño Preestablecido',
                'Realizar Mantenimiento Preventivo y Predictivo',
                'Instalar y Configurar el Cableado Estructurado',
                'Realizar el Diagnóstico y Mantenimiento de los Equipos de Cómputo',
                'Participar en el Proceso de Actualización Tecnológica',
                'Aplicar Controles de Acceso a la Información'
            ],

            19 => [ // Tecnólogo en Gestión de Redes de Datos
                'Construir el Sistema que Cumpla con los Requerimientos',
                'Diseñar la Red de Acuerdo con las Necesidades',
                'Configurar los Dispositivos de Red de Acuerdo con los Estándares',
                'Verificar el Estado de la Operación del Sistema',
                'Implementar las Políticas de Seguridad en la Red',
                'Monitorear el Tráfico de la Red',
                'Planear Actividades de Mejora en la Red de Datos',
                'Diagnosticar Fallas en la Red'
            ],

            20 => [ // Tecnólogo en Producción Multimedia
                'Planificar la Producción Multimedia',
                'Diseñar Recursos Multimedia',
                'Desarrollar Animaciones Multimedia',
                'Integrar Elementos Multimedia',
                'Construir Aplicaciones Multimedia Interactivas',
                'Publicar Contenidos Multimedia',
                'Realizar Post-producción de Audio y Video',
                'Evaluar el Producto Multimedia'
            ],

            21 => [ // Técnico en Programación de Software
                'Construir Algoritmos Aplicando Metodologías de Desarrollo',
                'Identificar las Estructuras de Datos',
                'Codificar el Módulo de Software',
                'Realizar Pruebas de los Módulos de Software',
                'Generar la Documentación del Módulo de Software',
                'Participar en el Proceso de Implantación del Software',
                'Realizar Mantenimiento del Módulo de Software',
                'Aplicar Buenas Prácticas de Calidad'
            ],

            22 => [ // Tecnólogo en Implementación de Infraestructura TIC
                'Implementar la Estructura de la Red',
                'Instalar Componentes Software de Acuerdo con el Análisis de Requerimientos',
                'Configurar el Hardware de Acuerdo con el Análisis de Requerimientos',
                'Implementar el Cableado Estructurado',
                'Realizar Mantenimiento Preventivo y Predictivo que Garantice el Funcionamiento del Hardware',
                'Implementar Políticas de Seguridad',
                'Gestionar la Información de Acuerdo con las Necesidades de la Organización',
                'Participar en el Proceso de Actualización Tecnológica'
            ],

            23 => [ // Tecnólogo en Gestión de la Seguridad y Salud en el Trabajo
                'Identificar Peligros y Evaluar Riesgos',
                'Elaborar Procedimientos e Instructivos de Trabajo Seguro',
                'Planificar Acciones de Promoción y Prevención',
                'Implementar Actividades de Promoción y Prevención',
                'Evaluar Indicadores del Sistema de Gestión',
                'Gestionar el Mejoramiento Continuo del Sistema',
                'Participar en la Investigación de Incidentes',
                'Fomentar Prácticas y Comportamientos Seguros'
            ],

            24 => [ // Tecnólogo en Gestión de Proyectos de Desarrollo de Software
                'Planificar el Proyecto de Software',
                'Definir los Requerimientos del Sistema',
                'Coordinar las Actividades del Equipo de Desarrollo',
                'Gestionar los Recursos del Proyecto',
                'Controlar los Cambios del Sistema de Información',
                'Evaluar el Sistema de Información',
                'Implantar el Sistema de Información',
                'Gestionar la Calidad del Proyecto de Software'
            ]
        ];

        // Lista de IDs de programas del SENA (institución 2)
        $programasSena = [17, 18, 19, 20, 21, 22, 23, 24];

        foreach ($pensum as $programaId => $contenido) {
            $esSena = in_array($programaId, $programasSena);

            if ($esSena) {
                // Para programas del SENA (estructura plana - array simple)
                foreach ($contenido as $index => $nombre) {
                    // Verificar que $nombre sea string
                    if (!is_string($nombre)) {
                        continue; // Saltar si no es string
                    }

                    $codigo = $this->generarCodigoAsignatura($programaId, $nombre, $maxLength, $index + 1);

                    Asignatura::updateOrCreate(
                        ['codigo_asignatura' => $codigo],
                        [
                            'programa_id' => $programaId,
                            'nombre' => $nombre,
                            'tipo' => 'Competencia',
                            'creditos' => null,
                            'horas_sena' => $this->calcularHorasSena($nombre),
                            'semestre' => floor($index / ceil(count($contenido) / 4)) + 1,
                            'tiempo_presencial' => null,
                            'tiempo_independiente' => null,
                            'horas_totales_semanales' => null,
                            'modalidad' => 'Práctico',
                            'metodologia' => 'Presencial',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            } else {
                // Para programas universitarios (estructura por semestres)
                foreach ($contenido as $semestre => $asignaturas) {
                    foreach ($asignaturas as $index => $nombre) {
                        // Verificar que $nombre sea string
                        if (!is_string($nombre)) {
                            continue; // Saltar si no es string
                        }

                        $codigo = $this->generarCodigoAsignatura($programaId, $nombre, $maxLength, null, $semestre, $index + 1);

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
                                'horas_sena' => null,
                                'semestre' => $semestre,
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
        }
    }

    /**
     * Genera un código único para la asignatura
     */
    private function generarCodigoAsignatura($programaId, $nombre, $maxLength, $secuencia = null, $semestre = null, $orden = null)
    {
        if ($semestre && $orden) {
            // Para programas universitarios: PROG_S1_M1
            $codigo = "P{$programaId}_S{$semestre}_M{$orden}";
        } else {
            // Para programas SENA: PROG_COMP_1
            $codigo = "P{$programaId}_C" . ($secuencia ?: 1);
        }

        // Asegurar que el código no exceda la longitud máxima
        if (strlen($codigo) > $maxLength) {
            $codigo = substr($codigo, 0, $maxLength);
        }

        return $codigo;
    }

    /**
     * Calcula el número de créditos basado en el nombre de la asignatura
     */
    private function calcularCreditos($nombre)
    {
        // Verificar que $nombre sea string
        if (!is_string($nombre)) {
            return 3; // Valor por defecto
        }

        // Materias con mayor intensidad académica (4-5 créditos)
        $materiasAvanzadas = [
            'Proyecto', 'Trabajo de Grado', 'Práctica', 'Tesis', 'Laboratorio', 'Diseño',
            'Arquitectura', 'Inteligencia Artificial', 'Bases de Datos', 'Desarrollo Web',
            'Computación en la Nube', 'Desarrollo de Aplicaciones', 'Análisis Estructural',
            'Concreto Reforzado', 'Estructuras', 'Construcción', 'Urbanismo', 'Taller de Diseño',
            'Control Automático', 'Procesamiento', 'Sistemas de Comunicaciones', 'Electrónica',
            'Tratamiento', 'Modelación', 'Simulación', 'Automatización', 'Robótica'
        ];

        // Materias básicas o introductorias (2-3 créditos)
        $materiasBasicas = [
            'Fundamentos', 'Introducción', 'Comunicación', 'Oral', 'Escrita', 'Ética',
            'Emprendimiento', 'Inglés', 'Constitución', 'Metodología', 'Historia'
        ];

        // Materias matemáticas y de ciencias básicas (3-4 créditos)
        $materiasMatematicas = [
            'Cálculo', 'Álgebra', 'Matemáticas', 'Física', 'Química', 'Estadística',
            'Probabilidad', 'Ecuaciones Diferenciales', 'Métodos Numéricos'
        ];

        // Verificar categoría de la materia
        foreach ($materiasAvanzadas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(4, 5);
            }
        }

        foreach ($materiasBasicas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(2, 3);
            }
        }

        foreach ($materiasMatematicas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(3, 4);
            }
        }

        return rand(3, 4); // Materias regulares: 3-4 créditos
    }

    /**
     * Calcula el tiempo presencial basado en los créditos y el nombre de la asignatura
     */
    private function calcularTiempoPresencial($creditos, $nombre)
    {
        // Verificar que $nombre sea string
        if (!is_string($nombre)) {
            return $creditos; // Valor por defecto
        }

        // Materias con mayor componente práctico necesitan más tiempo presencial
        $componentePractico = [
            'Laboratorio', 'Práctica', 'Proyecto', 'Taller', 'Desarrollo', 'Implementación',
            'Diseño', 'Programación', 'DevOps', 'Construcción', 'Dibujo', 'Topografía',
            'Instalaciones', 'Electrónica', 'Circuitos', 'Instrumentación', 'Control',
            'Manufactura', 'Procesos', 'Sistemas', 'Redes', 'Base de Datos'
        ];

        // Materias principalmente teóricas
        $componenteTeorico = [
            'Historia', 'Teoría', 'Fundamentos', 'Introducción', 'Matemáticas', 'Cálculo',
            'Álgebra', 'Física', 'Química', 'Ética', 'Administración', 'Economía', 'Gestión'
        ];

        foreach ($componentePractico as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return min($creditos + 1, 6); // Máximo 6 horas presenciales
            }
        }

        foreach ($componenteTeorico as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return max($creditos - 1, 2); // Mínimo 2 horas presenciales
            }
        }

        return $creditos; // Por defecto, créditos = horas presenciales
    }

    /**
     * Determina la modalidad basado en el nombre de la asignatura
     */
    private function determinarModalidad($nombre)
    {
        // Verificar que $nombre sea string
        if (!is_string($nombre)) {
            return 'Teórico-Práctico'; // Valor por defecto
        }

        // Materias principalmente teóricas
        $materiasTeoricas = [
            'Historia', 'Teoría', 'Fundamentos', 'Introducción', 'Matemáticas', 'Cálculo',
            'Álgebra Lineal', 'Física I', 'Física II', 'Química General', 'Ética',
            'Administración', 'Economía', 'Gestión', 'Legislación', 'Normatividad',
            'Probabilidad', 'Estadística', 'Metodología', 'Comunicación', 'Inglés'
        ];

        // Materias principalmente prácticas
        $materiasPracticas = [
            'Laboratorio', 'Taller', 'Práctica', 'Desarrollo', 'Implementación', 'Programación',
            'Diseño', 'DevOps', 'Dibujo', 'Topografía', 'Construcción', 'Instalaciones',
            'Proyecto', 'Trabajo de Grado', 'Seminario', 'Electrónica', 'Circuitos'
        ];

        // Verificar si es principalmente teórica
        foreach ($materiasTeoricas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return 'Teórico';
            }
        }

        // Verificar si es principalmente práctica
        foreach ($materiasPracticas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return 'Práctico';
            }
        }

        return 'Teórico-Práctico'; // Por defecto
    }

    /**
     * Calcula las horas SENA basado en el nombre de la competencia
     */
    private function calcularHorasSena($nombre)
    {
        // Verificar que $nombre sea string
        if (!is_string($nombre)) {
            return 120; // Valor por defecto
        }

        // Competencias más complejas (200-280 horas)
        $competenciasComplejas = [
            'Desarrollar', 'Implementar', 'Gestionar', 'Diseñar', 'Administrar', 'Liderar',
            'Construir', 'Planificar', 'Coordinar', 'Evaluar', 'Analizar', 'Modelar'
        ];

        // Competencias básicas (80-150 horas)
        $competenciasBasicas = [
            'Instalar', 'Configurar', 'Documentar', 'Monitorear', 'Reportar', 'Mantener',
            'Asistir', 'Aplicar', 'Identificar', 'Participar', 'Realizar', 'Generar'
        ];

        // Competencias intermedias (160-220 horas)
        $competenciasIntermedias = [
            'Controlar', 'Verificar', 'Diagnosticar', 'Integrar', 'Publicar', 'Fomentar',
            'Elaborar', 'Codificar', 'Ensamblar', 'Implantar'
        ];

        foreach ($competenciasComplejas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(200, 280);
            }
        }

        foreach ($competenciasBasicas as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(80, 150);
            }
        }

        foreach ($competenciasIntermedias as $palabra) {
            if (stripos($nombre, $palabra) !== false) {
                return rand(160, 220);
            }
        }

        return rand(120, 200); // Por defecto
    }
}
