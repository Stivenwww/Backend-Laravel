# 📦 Backend Laravel - Proyecto del Sistema de homologaciones de la Corporación Universitaria Autónoma del Cauca 

Este es el backend de un proyecto construido con el framework **Laravel 10**. La API está diseñada para servir como base sólida para aplicaciones modernas, incluyendo autenticación, controladores RESTful y manejo de errores estructurado.

---

## 🚀 Tecnologías y dependencias clave

- **PHP** >= 8.1
- **Laravel Framework** v10.48.29
- **Laravel Sanctum** – Autenticación basada en tokens para SPAs y APIs móviles.
- **Tymon JWT Auth** – Autenticación con JSON Web Tokens.
- **Guzzle HTTP** – Cliente HTTP para consumir APIs externas.
- **Carbon** – Librería para manejo de fechas y tiempos.
- **Faker** – Generación de datos falsos para pruebas.
- **Laravel Tinker** – Consola interactiva de Laravel.
- **Spatie Ignition** – Pantalla de errores avanzada.

---

## ⚙️ Requisitos del sistema

- PHP >= 8.1
- Composer
- MySQL o PostgreSQL
- Extensiones PHP:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo

---

## 📂 Instalación del proyecto

1. Clona este repositorio:

git clone https://github.com/Stivenwww/Backend-Laravel.git
cd tu_proyecto

2. Instala las dependencias con Composer:

composer install

3. Copia el archivo de entorno y configúralo:

cp .env.example .env

4. Genera la clave de la aplicación:

php artisan key:generate

5. Configura la base de datos en el archivo .env.

6. Ejecuta las migraciones (y seeds si es necesario):

php artisan migrate --seed

7. (Opcional) Si usas JWT:

php artisan jwt:secret

8. Inicia el servidor de desarrollo:

php artisan serve

---

## 🧱 Estructura del proyecto

├── app/ 
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/ 
│   │   ├── Controllers/ 
│   │   ├── Middleware/ 
│   ├── Mail/
│   ├── Models/ 
│   ├── Providers/
│   ├── View/
├── bootstrap/
├── config/ 
├── database/ 
│   ├── data/
│   ├── factories/
│   ├── migrations/ 
│   ├── seeders/ 
├── logs/
├── public/
│   ├── node_modules/
│   └── resources/
│       └── views/
├── resources/
│   └── views/
├── routes/ 
│   ├── api.php 
│   ├── channels.php
│   ├── console.php
│   └── web.php 
├── storage/
├── tests/
│   ├── Feature/
│   └── Unit/
├── vendor/
├── .env 
├── .env.example
├── .gitattributes
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── dsad
├── package-lock.json
├── phpunit.xml

---

## API Endpoints 🔚
1. Autenticación

POST /api/auth/login - Inicio de sesión
POST /api/auth/register - Registro de usuario
POST /api/auth/logout - Cierre de sesión (requiere JWT)
POST /api/auth/refresh - Refrescar token JWT (requiere JWT)

2. Países

GET /api/paises - Obtener todos los países
GET /api/paises/{id} - Obtener país por ID
POST /api/paises - Crear un nuevo país
PUT /api/paises/{id} - Actualizar un país
DELETE /api/paises/{id} - Eliminar un país

3. Departamentos

GET /api/departamentos - Obtener todos los departamentos
GET /api/departamentos/{id} - Obtener departamento por ID
POST /api/departamentos - Crear un nuevo departamento
PUT /api/departamentos/{id} - Actualizar un departamento
DELETE /api/departamentos/{id} - Eliminar un departamento

4. Municipios

GET /api/municipios - Obtener todos los municipios
GET /api/municipios/{id} - Obtener municipio por ID
POST /api/municipios - Crear un nuevo municipio
PUT /api/municipios/{id} - Actualizar un municipio
DELETE /api/municipios/{id} - Eliminar un municipio

5. Instituciones

GET /api/instituciones - Obtener todas las instituciones
GET /api/instituciones/{id} - Obtener institución por ID
POST /api/instituciones - Crear una nueva institución
PUT /api/instituciones/{id} - Actualizar una institución
DELETE /api/instituciones/{id} - Eliminar una institución

6. Facultades

GET /api/facultades - Obtener todas las facultades
GET /api/facultades/{id} - Obtener facultad por ID
POST /api/facultades - Crear una nueva facultad
PUT /api/facultades/{id} - Actualizar una facultad
DELETE /api/facultades/{id} - Eliminar una facultad

7. Programas

GET /api/programas - Obtener todos los programas
GET /api/programas/{id} - Obtener programa por ID
POST /api/programas - Crear un nuevo programa
PUT /api/programas/{id} - Actualizar un programa
DELETE /api/programas/{id} - Eliminar un programa

8. Asignaturas

GET /api/asignaturas - Obtener todas las asignaturas
GET /api/asignaturas/{id} - Obtener asignatura por ID
POST /api/asignaturas - Crear una nueva asignatura
PUT /api/asignaturas/{id} - Actualizar una asignatura
DELETE /api/asignaturas/{id} - Eliminar una asignatura
GET /api/asignaturas/programa/{id_programa} - Obtener asignaturas por programa
GET /api/asignaturas/programa/{id_programa}/asignatura/{id_asignatura} - Obtener asignatura específica de un programa

9. Contenidos Programáticos

GET /api/contenidos-programaticos - Obtener todos los contenidos programáticos
GET /api/contenidos-programaticos/{id} - Obtener contenido programático por ID
POST /api/contenidos-programaticos - Crear un nuevo contenido programático
PUT /api/contenidos-programaticos/{id} - Actualizar un contenido programático
DELETE /api/contenidos-programaticos/{id} - Eliminar un contenido programático

10. Solicitudes

GET /api/solicitudes - Obtener todas las solicitudes
GET /api/solicitudes/{id} - Obtener solicitud por ID
POST /api/solicitudes - Crear una nueva solicitud
PUT /api/solicitudes/{id} - Actualizar una solicitud
DELETE /api/solicitudes/{id} - Eliminar una solicitud

11. Solicitud-Asignaturas

GET /api/solicitud-asignaturas - Obtener todas las relaciones solicitud-asignatura
GET /api/solicitud-asignaturas/{id} - Obtener relación solicitud-asignatura por ID
POST /api/solicitud-asignaturas - Crear una nueva relación solicitud-asignatura
PUT /api/solicitud-asignaturas/{id} - Actualizar una relación solicitud-asignatura
DELETE /api/solicitud-asignaturas/{id} - Eliminar una relación solicitud-asignatura

12. Homologación-Asignaturas

GET /api/homologacion-asignaturas - Obtener todas las homologaciones de asignaturas
GET /api/homologacion-asignaturas/{id} - Obtener homologación de asignatura por ID
POST /api/homologacion-asignaturas - Crear una nueva homologación de asignatura
PUT /api/homologacion-asignaturas/{id} - Actualizar una homologación de asignatura
DELETE /api/homologacion-asignaturas/{id} - Eliminar una homologación de asignatura

13. Historial de Homologaciones

GET /api/historial-homologaciones - Obtener todo el historial de homologaciones
GET /api/historial-homologaciones/{id} - Obtener registro de historial por ID
POST /api/historial-homologaciones - Crear un nuevo registro en el historial
PUT /api/historial-homologaciones/{id} - Actualizar un registro del historial
DELETE /api/historial-homologaciones/{id} - Eliminar un registro del historial

14. Usuarios

GET /api/usuarios - Obtener todos los usuarios
GET /api/usuarios/{id} - Obtener usuario por ID
POST /api/usuarios - Crear un nuevo usuario
PUT /api/usuarios/{id} - Actualizar un usuario
DELETE /api/usuarios/{id} - Eliminar un usuario

15. Roles

GET /api/roles - Obtener todos los roles
GET /api/roles/{id} - Obtener rol por ID
POST /api/roles - Crear un nuevo rol
PUT /api/roles/{id} - Actualizar un rol
DELETE /api/roles/{id} - Eliminar un rol

16. Documentos

GET /api/documentos - Obtener todos los documentos
GET /api/documentos/{id} - Obtener documento por ID
POST /api/documentos - Crear un nuevo documento
PUT /api/documentos/{id} - Actualizar un documento
DELETE /api/documentos/{id} - Eliminar un documento

---

## 🔐 Autenticación
Este proyecto implementa autenticación usando:

Laravel Sanctum (por defecto)

Tymon JWT Auth (opcional)

Puedes elegir uno u otro según tus necesidades.

---

## 🧪 Testing

Ejecuta las pruebas con:

php artisan test

---

## ✅ Comandos útiles

php artisan route:list           # Ver todas las rutas
php artisan migrate:fresh --seed # Reinicia la BD con datos de prueba
php artisan storage:link         # Crear enlace simbólico para almacenamiento
php artisan config:cache         # Cachear configuración

---

## 🧑‍💻 Autores

Copyright (c) 2025 Bryan David Yepes Ordoñez, Brayner Stiven Trochez Ordoñez, Deiby Alejandro Ramirez Galvis, Julian Alejandro Clavijo Reyes, Karen Verónica Mancilla Solarte, Laura Valentina Solarte Muñoz

---

## ©️ Licencia

Licencia
Este proyecto está licenciado bajo [MIT]
