# Torneo de Tennis API

API REST para gestionar y simular torneos de tenis con eliminación directa.  
Permite registrar jugadores, crear torneos y simular enfrentamientos hasta determinar un ganador.  

## Características  
- Simulación de torneos de eliminación directa.  
- Categorías masculina y femenina con factores específicos de desempeño.  
- Generación automática de enfrentamientos a partir de los jugadores.  
- API RESTful con endpoints para gestionar torneos, partidos y jugadores.

## Requisitos  
- PHP 8+  
- Laravel 10+  
- Composer  
- MySQL  

## Instalación  
1. Clonar el repositorio:  
   ```sh
   git clone https://github.com/hadominguez/torneo-tennis.git
   cd torneo-tennis
   ```
2. Instalar dependencias:  
   ```sh
   composer install
   ```
3. Configurar variables de entorno:  
   ```sh
   cp .env.example .env
   ```
   Luego, editar `.env` con los datos de la base de datos.  
4. Generar la clave de la aplicación:  
   ```sh
   php artisan key:generate
   ```
5. Ejecutar migraciones y sembrar datos de prueba:  
   ```sh
   php artisan migrate --seed
   ```
6. Iniciar el servidor de desarrollo:  
   ```sh
   php artisan serve
   ```

## Endpoints principales  

### **Torneos**  
| Método  | Endpoint | Descripción |
|---------|---------|-------------|
| GET | `/tournaments` | Obtener todos los torneos |
| POST | `/tournaments` | Crear un nuevo torneo |
| GET | `/tournaments/{id}` | Obtener un torneo por ID |
| PUT | `/tournaments/{id}` | Actualizar un torneo |
| DELETE | `/tournaments/{id}` | Eliminar un torneo |
| GET | `/tournaments/{id}/start` | Iniciar un torneo |

### **Partidos**  
| Método  | Endpoint | Descripción |
|---------|---------|-------------|
| GET | `/matches` | Obtener todos los partidos |
| POST | `/matches` | Crear un nuevo partido |
| GET | `/matches/{id}` | Obtener un partido por ID |
| PUT | `/matches/{id}` | Actualizar un partido |
| DELETE | `/matches/{id}` | Eliminar un partido |

### **Jugadores**  
| Método  | Endpoint | Descripción |
|---------|---------|-------------|
| GET | `/players` | Obtener todos los jugadores |
| POST | `/players` | Crear un nuevo jugador |
| GET | `/players/{id}` | Obtener un jugador por ID |
| PUT | `/players/{id}` | Actualizar un jugador |
| DELETE | `/players/{id}` | Eliminar un jugador |

## Reglas del Torneo  
- Formato de **eliminación directa** (el perdedor queda eliminado).  
- Solo se permite una cantidad de jugadores que sea **potencia de 2**.  
- Categorías:  
  - **Masculino**: Influenciado por fuerza y velocidad.  
  - **Femenino**: Influenciado por tiempo de reacción.  
- Factores aleatorios pueden influir en el resultado de un partido.  
- No existen los empates.  

## Ejemplo de Uso  
1. Registrar jugadores.  
2. Crear un torneo y asignar jugadores.  
3. Iniciar el torneo (`/tournaments/{id}/start`).  
4. Consultar el ganador al finalizar.  

## Testing  
Para ejecutar los tests unitarios:  
```sh
php artisan test
```